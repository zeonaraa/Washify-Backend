<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use App\Models\Paket;
use App\Models\Member;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{

    private function checkAdmin($method)
    {
        if (!in_array(auth()->user()->role, ['admin', 'kasir', 'owner'])) {
            return response()->json([
                'message' => 'Unauthorized. Only admin, kasir and owner can perform this action.',
            ], 403);
        }
    }

    public function getReports()
    {
        if ($response = $this->checkAdmin(__FUNCTION__)) {
            return $response;
        }

        // **1. Paket yang paling banyak dipesan**
        $topPaket = DetailTransaksi::selectRaw('id_paket, SUM(qty) as total_qty')
            ->groupBy('id_paket')
            ->orderByDesc('total_qty')
            ->with('paket')
            ->first();

        // **2. Jumlah Pendapatan Perbulan**
        $pendapatanBulanan = Transaksi::selectRaw('MONTH(tgl_bayar) as bulan, YEAR(tgl_bayar) as tahun, SUM(
            (SELECT SUM(dt.qty * p.harga)
             FROM tb_detail_transaksi dt
             JOIN tb_paket p ON dt.id_paket = p.id
             WHERE dt.id_transaksi = tb_transaksi.id)
             + tb_transaksi.biaya_tambahan
             - tb_transaksi.diskon
             + tb_transaksi.pajak
        ) as total')
            ->where('dibayar', 'dibayar')
            ->groupByRaw('bulan, tahun')
            ->orderByRaw('tahun DESC, bulan DESC')
            ->get();

        // **3. Pendapatan Harian**
        $pendapatanHarian = Transaksi::selectRaw('DATE(tgl_bayar) as tanggal, SUM(
            (SELECT SUM(dt.qty * p.harga)
             FROM tb_detail_transaksi dt
             JOIN tb_paket p ON dt.id_paket = p.id
             WHERE dt.id_transaksi = tb_transaksi.id)
             + tb_transaksi.biaya_tambahan
             - tb_transaksi.diskon
             + tb_transaksi.pajak
        ) as total')
            ->where('dibayar', 'dibayar')
            ->groupByRaw('tanggal')
            ->orderByRaw('tanggal DESC')
            ->get();

        // **4. Top Member yang Paling Sering Melakukan Laundry**
        $topMember = Transaksi::selectRaw('id_member, COUNT(id) as total_transaksi')
            ->groupBy('id_member')
            ->orderByDesc('total_transaksi')
            ->with('member') // Assume member() relation is defined in Transaksi model
            ->first();

        // Return all data in a structured response
        return response()->json([
            'top_paket' => [
                'paket' => $topPaket->paket->nama_paket ?? null,
                'total_qty' => $topPaket->total_qty ?? 0,
            ],
            'pendapatan_bulanan' => $pendapatanBulanan,
            'pendapatan_harian' => $pendapatanHarian,
            'top_member' => [
                'member' => $topMember->member->nama ?? null,
                'total_transaksi' => $topMember->total_transaksi ?? 0,
            ],
        ]);
    }
}
