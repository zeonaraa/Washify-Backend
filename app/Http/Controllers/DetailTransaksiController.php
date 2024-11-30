<?php

namespace App\Http\Controllers;

use App\Interfaces\DetailTransaksiRepositoryInterface;
use App\Http\Requests\StoreDetailTransaksiRequest;
use App\Http\Requests\UpdateDetailTransaksiRequest;
use Illuminate\Http\Request;
use App\Classes\ApiResponseClass;
use App\Http\Resources\DetailTransaksiResource;

class DetailTransaksiController extends Controller
{
    protected $DetailTransaksiRepository;

    public function __construct(DetailTransaksiRepositoryInterface $DetailTransaksiRepository)
    {
        $this->DetailTransaksiRepository = $DetailTransaksiRepository;
    }

    private function checkAdmin($method)
    {
        if ($method !== 'index' && auth()->user()->role !== 'admin') {
            return response()->json([
                'message' => 'Unauthorized. Only admin can perform this action.',
            ], 403);
        }
    }

    public function index()
    {
        $detailTransaksi = $this->DetailTransaksiRepository->index();
        return ApiResponseClass::sendResponse(DetailTransaksiResource::collection($detailTransaksi),'',200);
    }

    public function show($id)
    {
        if ($response = $this->checkAdmin(__FUNCTION__)) {
            return $response;
        }

        $detailTransaksi = $this->DetailTransaksiRepository->getById($id);
        $transaksi = $detailTransaksi->transaksi;

        $subtotal = $detailTransaksi->paket->harga * $detailTransaksi->qty;

        $subtotal += $transaksi->biaya_tambahan;

        $subtotal -= $transaksi->diskon;

        $subtotal += $transaksi->pajak;

        return ApiResponseClass::sendResponse([
            'detail' => new DetailTransaksiResource($detailTransaksi),
            'subtotal' => $detailTransaksi->paket->harga * $detailTransaksi->qty,
            'biaya_tambahan' => $transaksi->biaya_tambahan,
            'diskon' => $transaksi->diskon,
            'pajak' => $transaksi->pajak,
            'total_harga' => $subtotal,
        ], 'Detail transaksi berhasil ditampilkan.', 200);
    }



    public function store(StoreDetailTransaksiRequest $request)
    {
        if ($response = $this->checkAdmin(__FUNCTION__)) {
            return $response;
        }

        $validated = $request->validated();
        $detailTransaksi = $this->DetailTransaksiRepository->store($validated);
        return ApiResponseClass::sendResponse(new DetailTransaksiResource($detailTransaksi), 'Detail Transaksi Create Success', 201);

    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDetailTransaksiRequest $request, $id)
    {
        if ($response = $this->checkAdmin(__FUNCTION__)) {
            return $response;
        }

        $validated = $request->validated();
        $detailTransaksi = $this->DetailTransaksiRepository->update($validated, $id);
        return ApiResponseClass::sendResponse(new DetailTransaksiResource($detailTransaksi), 'Detail Transaksi Update Success', 200);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        if ($response = $this->checkAdmin(__FUNCTION__)) {
            return $response;
        }

        $this->DetailTransaksiRepository->delete($id);
        return ApiResponseClass::sendResponse('Detail Transaksi Delete Success', 204);
    }
}
