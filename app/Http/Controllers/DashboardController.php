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
    $outletId = $user->id_outlet; // Ambil ID outlet dari user yang login

    // 1. Jumlah Transaksi Hari Ini
    $tanggalHariIni = Carbon::today();
    $jumlahTransaksiHariIni = Transaksi::where('id_outlet', $outletId)
        ->whereDate('tgl', $tanggalHariIni)
        ->count();

    // 2. Pendapatan Hari Ini
    $pendapatanHarian = Transaksi::where('id_outlet', $outletId)
        ->whereDate('tgl_bayar', today())
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

    // 3. Jumlah Member Berdasarkan Outlet
    $jumlahMember = Member::where('id_outlet', $outletId)->count();

    // 4. Jumlah Outlet (Hanya Outlet yang Dimiliki User)
    $jumlahOutlet = Outlet::where('id', $outletId)->count();

    // 5. Status Transaksi
    $statusTransaksi = [
        'baru' => Transaksi::where('id_outlet', $outletId)->where('status', 'baru')->count(),
        'proses' => Transaksi::where('id_outlet', $outletId)->where('status', 'proses')->count(),
        'selesai' => Transaksi::where('id_outlet', $outletId)->where('status', 'selesai')->count(),
        'diambil' => Transaksi::where('id_outlet', $outletId)->where('status', 'diambil')->count(),
    ];

    // 6. Paket Paling Banyak Dipesan
    $paketPalingBanyak = Transaksi::join('tb_detail_transaksi', 'tb_transaksi.id', '=', 'tb_detail_transaksi.id_transaksi')
    ->join('tb_paket', 'tb_detail_transaksi.id_paket', '=', 'tb_paket.id')
    ->where('tb_transaksi.id_outlet', $user->id_outlet) // Pastikan kolom berasal dari tabel `tb_transaksi`
    ->selectRaw('tb_paket.nama_paket, SUM(tb_detail_transaksi.qty) as total_qty')
    ->groupBy('tb_paket.nama_paket')
    ->orderByDesc('total_qty')
    ->first();


    // 7. Top Member Berdasarkan Transaksi di Outlet
    $topMember = Transaksi::where('id_outlet', $outletId)
        ->selectRaw('id_member, COUNT(id) as total_transaksi')
        ->groupBy('id_member')
        ->orderByDesc('total_transaksi')
        ->with('member')
        ->first();

    // 8. Notifikasi Transaksi Belum Dibayar
    $transaksiBelumDibayar = Transaksi::where('id_outlet', $outletId)
        ->where('dibayar', 'belum_dibayar')
        ->count();

    // Return response
    return ApiResponseClass::sendResponse([
        'user' => [
            'name' => $userName,
            'role' => $userRole,
            'outlet_id' => $outletId,
        ],
        'ringkasan_statistik' => [
            'jumlah_transaksi_hari_ini' => $jumlahTransaksiHariIni,
            'pendapatan_hari_ini' => $pendapatanHarian,
            'jumlah_member' => $jumlahMember,
            'jumlah_outlet' => $jumlahOutlet,
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
