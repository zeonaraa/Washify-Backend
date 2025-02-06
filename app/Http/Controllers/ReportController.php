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

        $idOutlet = auth()->user()->id_outlet ?? null;

        if (!$idOutlet) {
            return response()->json(['error' => 'User does not have an associated outlet'], 403);
        }

        $transaksiBulanIni = Transaksi::where('id_outlet', $idOutlet)
            ->whereMonth('tgl', $tanggalHariIni->month)
            ->count();

        $transaksiBulanLalu = Transaksi::where('id_outlet', $idOutlet)
            ->whereMonth('tgl', $tanggalBulanLalu->month)
            ->count();

        $percentTransaksi = $transaksiBulanLalu > 0 ? (($transaksiBulanIni - $transaksiBulanLalu) / $transaksiBulanLalu) * 100 : 0;

        $pendapatanBulanIni = Transaksi::where('id_outlet', $idOutlet)
            ->whereMonth('tgl_bayar', $tanggalHariIni->month)
            ->where('dibayar', 'dibayar')
            ->sum(DB::raw('
                (SELECT SUM(dt.qty * p.harga) FROM tb_detail_transaksi dt
                JOIN tb_paket p ON dt.id_paket = p.id
                WHERE dt.id_transaksi = tb_transaksi.id)
                + tb_transaksi.biaya_tambahan - tb_transaksi.diskon + tb_transaksi.pajak
            '));

        $pendapatanBulanLalu = Transaksi::where('id_outlet', $idOutlet)
            ->whereMonth('tgl_bayar', $tanggalBulanLalu->month)
            ->where('dibayar', 'dibayar')
            ->sum(DB::raw('
                (SELECT SUM(dt.qty * p.harga) FROM tb_detail_transaksi dt
                JOIN tb_paket p ON dt.id_paket = p.id
                WHERE dt.id_transaksi = tb_transaksi.id)
                + tb_transaksi.biaya_tambahan - tb_transaksi.diskon + tb_transaksi.pajak
            '));

        $percentPendapatan = $pendapatanBulanLalu > 0 ? (($pendapatanBulanIni - $pendapatanBulanLalu) / $pendapatanBulanLalu) * 100 : 0;

        $jumlahMember = Member::where('id_outlet', $idOutlet)->count();
        $jumlahOutlet = Outlet::count();
        $statusTransaksi = [
            'baru' => Transaksi::where('id_outlet', $idOutlet)->where('status', 'baru')->count(),
            'proses' => Transaksi::where('id_outlet', $idOutlet)->where('status', 'proses')->count(),
            'selesai' => Transaksi::where('id_outlet', $idOutlet)->where('status', 'selesai')->count(),
            'diambil' => Transaksi::where('id_outlet', $idOutlet)->where('status', 'diambil')->count(),
        ];

        $paketPalingBanyak = Transaksi::join('tb_detail_transaksi', 'tb_transaksi.id', '=', 'tb_detail_transaksi.id_transaksi')
            ->join('tb_paket', 'tb_detail_transaksi.id_paket', '=', 'tb_paket.id')
            ->where('tb_transaksi.id_outlet', $idOutlet)
            ->selectRaw('tb_paket.nama_paket, SUM(tb_detail_transaksi.qty) as total_qty')
            ->groupBy('tb_paket.nama_paket')
            ->orderByDesc('total_qty')
            ->first();

        $pdf = Pdf::loadView('reports.dashboard_report', compact(
            'transaksiBulanIni', 'transaksiBulanLalu', 'percentTransaksi',
            'pendapatanBulanIni', 'pendapatanBulanLalu', 'percentPendapatan',
            'jumlahMember', 'jumlahOutlet', 'statusTransaksi', 'paketPalingBanyak'
        ))->setPaper('A4', 'portrait');

        return $pdf->download('Laporan_Dashboard.pdf');
    }


}
