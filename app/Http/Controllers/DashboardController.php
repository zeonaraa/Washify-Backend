<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\Member;
use App\Models\Outlet;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Classes\ApiResponseClass;

class DashboardController extends Controller
{
    private function checkAdmin($method)
    {
        if (!in_array(auth()->user()->role, ['admin', 'kasir', 'owner'])) {
            return response()->json([
                'message' => 'Unauthorized. Only admin, kasir and owner can perform this action.',
            ], 403);
        }
    }
    public function index()
{
    if ($response = $this->checkAdmin(__FUNCTION__)) {
        return $response;
    }

    $user = auth()->user();
    $userName = $user->nama ?? 'Guest';
    $userRole = $user->role ?? 'Unknown';

    // 1. Jumlah Transaksi Hari Ini
    $tanggalHariIni = Carbon::today();
    $jumlahTransaksiHariIni = Transaksi::whereDate('tgl', $tanggalHariIni)->count();

    // Transaksi Hari Ini Bulan Lalu
    $tanggalHariIniBulanLalu = Carbon::today()->subMonth();
    $jumlahTransaksiHariIniBulanLalu = Transaksi::whereDate('tgl', $tanggalHariIniBulanLalu)->count();

    // Persentase Perubahan Transaksi Hari Ini
    $percentTransaksiHariIni = $jumlahTransaksiHariIniBulanLalu > 0
        ? (($jumlahTransaksiHariIni - $jumlahTransaksiHariIniBulanLalu) / $jumlahTransaksiHariIniBulanLalu) * 100
        : 0;

    // 2. Pendapatan Hari Ini
    $pendapatanHarian = Transaksi::whereDate('tgl_bayar', today())
        ->where('dibayar', 'dibayar')
        ->sum(DB::raw('
            (SELECT SUM(dt.qty * p.harga)
            FROM tb_detail_transaksi dt
            JOIN tb_paket p ON dt.id_paket = p.id
            WHERE dt.id_transaksi = tb_transaksi.id)
            + tb_transaksi.biaya_tambahan
            - tb_transaksi.diskon
            + tb_transaksi.pajak
        '));

    // Pendapatan Hari Ini Bulan Lalu
    $pendapatanHarianBulanLalu = Transaksi::whereDate('tgl_bayar', $tanggalHariIniBulanLalu)
        ->where('dibayar', 'dibayar')
        ->sum(DB::raw('
            (SELECT SUM(dt.qty * p.harga)
            FROM tb_detail_transaksi dt
            JOIN tb_paket p ON dt.id_paket = p.id
            WHERE dt.id_transaksi = tb_transaksi.id)
            + tb_transaksi.biaya_tambahan
            - tb_transaksi.diskon
            + tb_transaksi.pajak
        '));

    // Persentase Perubahan Pendapatan
    $percentPendapatanHariIni = $pendapatanHarianBulanLalu > 0
        ? (($pendapatanHarian - $pendapatanHarianBulanLalu) / $pendapatanHarianBulanLalu) * 100
        : 0;

    // 3. Jumlah Member
    $jumlahMember = Member::count();
    $jumlahMemberBulanLalu = Member::where('created_at', '<=', $tanggalHariIniBulanLalu->endOfMonth())->count();
    $percentMember = $jumlahMemberBulanLalu > 0
        ? (($jumlahMember - $jumlahMemberBulanLalu) / $jumlahMemberBulanLalu) * 100
        : 0;

    // 4. Jumlah Outlet
    $jumlahOutlet = Outlet::count();
    $jumlahOutletBulanLalu = Outlet::where('created_at', '<=', $tanggalHariIniBulanLalu->endOfMonth())->count();
    $percentOutlet = $jumlahOutletBulanLalu > 0
        ? (($jumlahOutlet - $jumlahOutletBulanLalu) / $jumlahOutletBulanLalu) * 100
        : 0;

    // 5. Status Transaksi
    $statusTransaksi = [
        'baru' => Transaksi::where('status', 'baru')->count(),
        'proses' => Transaksi::where('status', 'proses')->count(),
        'selesai' => Transaksi::where('status', 'selesai')->count(),
        'diambil' => Transaksi::where('status', 'diambil')->count(),
    ];

    // 6. Paket Paling Banyak Dipesan
    $paketPalingBanyak = Transaksi::join('tb_detail_transaksi', 'tb_transaksi.id', '=', 'tb_detail_transaksi.id_transaksi')
        ->join('tb_paket', 'tb_detail_transaksi.id_paket', '=', 'tb_paket.id')
        ->selectRaw('tb_paket.nama_paket, SUM(tb_detail_transaksi.qty) as total_qty')
        ->groupBy('tb_paket.nama_paket')
        ->orderByDesc('total_qty')
        ->first();

    // 7. Top Member Berdasarkan Transaksi
    $topMember = Transaksi::selectRaw('id_member, COUNT(id) as total_transaksi')
        ->groupBy('id_member')
        ->orderByDesc('total_transaksi')
        ->with('member')
        ->first();

    // 8. Notifikasi Transaksi Belum Dibayar
    $transaksiBelumDibayar = Transaksi::where('dibayar', 'belum_dibayar')->count();

    // Return response
    return ApiResponseClass::sendResponse([
        'user' => [
            'name' => $userName,
            'role' => $userRole,
        ],
        'ringkasan_statistik' => [
            'jumlah_transaksi_hari_ini' => $jumlahTransaksiHariIni,
            'pendapatan_hari_ini' => $pendapatanHarian,
            'jumlah_member' => $jumlahMember,
            'jumlah_outlet' => $jumlahOutlet,
            'percent_today_transactions' => round($percentTransaksiHariIni, 2),
            'percent_today_revenue' => round($percentPendapatanHariIni, 2),
            'percent_member' => round($percentMember, 2),
            'percent_outlet' => round($percentOutlet, 2),
            'status_transaksi' => $statusTransaksi,
        ],
        'paket_paling_banyak' => [
            'nama_paket' => $paketPalingBanyak->nama_paket ?? null,
            'total_qty' => $paketPalingBanyak->total_qty ?? 0,
        ],
        'top_member' => [
            'nama_member' => $topMember->member->nama ?? null,
            'total_transaksi' => $topMember->total_transaksi ?? 0,
        ],
        'notifikasi' => [
            'transaksi_belum_dibayar' => $transaksiBelumDibayar,
        ],
    ], 'Dashboard data retrieved successfully', 200);
}

}
