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
    public function index()
    {
        // **1. Jumlah Transaksi Hari Ini**
        $tanggalHariIni = Carbon::today();
        $jumlahTransaksiHariIni = Transaksi::whereDate('tgl', $tanggalHariIni)->count();

        // **2. Pendapatan Hari Ini**
        $pendapatanHarian = Transaksi::whereDate('tgl_bayar', today())
    ->where('dibayar', 'dibayar') // Hanya hitung yang sudah dibayar
    ->sum(DB::raw('
        (SELECT SUM(dt.qty * p.harga)
         FROM tb_detail_transaksi dt
         JOIN tb_paket p ON dt.id_paket = p.id
         WHERE dt.id_transaksi = tb_transaksi.id)
         + tb_transaksi.biaya_tambahan
         - tb_transaksi.diskon
         + tb_transaksi.pajak
    '));


        // **3. Jumlah Member**
        $jumlahMember = Member::count();

        // **4. Jumlah Outlet**
        $jumlahOutlet = Outlet::count();

        // **5. Status Transaksi**
        $statusTransaksi = [
            'baru' => Transaksi::where('status', 'baru')->count(),
            'proses' => Transaksi::where('status', 'proses')->count(),
            'selesai' => Transaksi::where('status', 'selesai')->count(),
            'diambil' => Transaksi::where('status', 'diambil')->count(),
        ];

        // **6. Paket Paling Banyak Dipesan**
        $paketPalingBanyak = Transaksi::join('tb_detail_transaksi', 'tb_transaksi.id', '=', 'tb_detail_transaksi.id_transaksi')
            ->join('tb_paket', 'tb_detail_transaksi.id_paket', '=', 'tb_paket.id')
            ->selectRaw('tb_paket.nama_paket, SUM(tb_detail_transaksi.qty) as total_qty')
            ->groupBy('tb_paket.nama_paket')
            ->orderByDesc('total_qty')
            ->first();

        // **7. Top Member Berdasarkan Transaksi**
        $topMember = Transaksi::selectRaw('id_member, COUNT(id) as total_transaksi')
            ->groupBy('id_member')
            ->orderByDesc('total_transaksi')
            ->with('member') // Ensure member relation exists in Transaksi model
            ->first();

        // **8. Notifikasi Transaksi Belum Dibayar**
        $transaksiBelumDibayar = Transaksi::where('dibayar', 'belum_dibayar')->count();

        // Return response
        return ApiResponseClass::sendResponse([
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
