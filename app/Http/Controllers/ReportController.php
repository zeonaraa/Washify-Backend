<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Transaksi;
use App\Models\Outlet;
use Illuminate\Support\Facades\DB;
use App\Classes\ApiResponseClass;

class ReportController extends Controller
{
    public function generateMemberReport()
    {
        $members = Member::with('outlet')->get();

        $pdf = Pdf::loadView('reports.member_report', compact('members'))
              ->setPaper('A4', 'portrait');

        return response($pdf->output(), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="Laporan_Members.pdf"');
    }

public function generateReport()
{
    $tanggalHariIni = Carbon::today();
    $tanggalBulanLalu = Carbon::today()->subMonth();

    // Data transaksi bulan ini & bulan lalu
    $transaksiBulanIni = Transaksi::whereMonth('tgl', $tanggalHariIni->month)->count();
    $transaksiBulanLalu = Transaksi::whereMonth('tgl', $tanggalBulanLalu->month)->count();
    $percentTransaksi = $transaksiBulanLalu > 0 ? (($transaksiBulanIni - $transaksiBulanLalu) / $transaksiBulanLalu) * 100 : 0;

    // Pendapatan bulan ini & bulan lalu
    $pendapatanBulanIni = Transaksi::whereMonth('tgl_bayar', $tanggalHariIni->month)
        ->where('dibayar', 'dibayar')
        ->sum(DB::raw('
            (SELECT SUM(dt.qty * p.harga) FROM tb_detail_transaksi dt
            JOIN tb_paket p ON dt.id_paket = p.id
            WHERE dt.id_transaksi = tb_transaksi.id)
            + tb_transaksi.biaya_tambahan - tb_transaksi.diskon + tb_transaksi.pajak
        '));

    $pendapatanBulanLalu = Transaksi::whereMonth('tgl_bayar', $tanggalBulanLalu->month)
        ->where('dibayar', 'dibayar')
        ->sum(DB::raw('
            (SELECT SUM(dt.qty * p.harga) FROM tb_detail_transaksi dt
            JOIN tb_paket p ON dt.id_paket = p.id
            WHERE dt.id_transaksi = tb_transaksi.id)
            + tb_transaksi.biaya_tambahan - tb_transaksi.diskon + tb_transaksi.pajak
        '));

    $percentPendapatan = $pendapatanBulanLalu > 0 ? (($pendapatanBulanIni - $pendapatanBulanLalu) / $pendapatanBulanLalu) * 100 : 0;

    // Jumlah Member & Outlet
    $jumlahMember = Member::count();
    $jumlahOutlet = Outlet::count();

    // Status Transaksi
    $statusTransaksi = [
        'baru' => Transaksi::where('status', 'baru')->count(),
        'proses' => Transaksi::where('status', 'proses')->count(),
        'selesai' => Transaksi::where('status', 'selesai')->count(),
        'diambil' => Transaksi::where('status', 'diambil')->count(),
    ];

    // Paket Paling Populer
    $paketPalingBanyak = Transaksi::join('tb_detail_transaksi', 'tb_transaksi.id', '=', 'tb_detail_transaksi.id_transaksi')
        ->join('tb_paket', 'tb_detail_transaksi.id_paket', '=', 'tb_paket.id')
        ->selectRaw('tb_paket.nama_paket, SUM(tb_detail_transaksi.qty) as total_qty')
        ->groupBy('tb_paket.nama_paket')
        ->orderByDesc('total_qty')
        ->first();

    // Generate PDF
    $pdf = Pdf::loadView('reports.dashboard_report', compact(
        'transaksiBulanIni', 'transaksiBulanLalu', 'percentTransaksi',
        'pendapatanBulanIni', 'pendapatanBulanLalu', 'percentPendapatan',
        'jumlahMember', 'jumlahOutlet', 'statusTransaksi', 'paketPalingBanyak'
    ))->setPaper('A4', 'portrait');

    return $pdf->download('Laporan_Dashboard.pdf');
}


}
