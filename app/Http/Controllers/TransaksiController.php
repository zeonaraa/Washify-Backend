<?php

namespace App\Http\Controllers;

use App\Interfaces\TransaksiRepositoryInterface;
use App\Http\Requests\StoreTransaksiRequest;
use App\Http\Requests\UpdateTransaksiRequest;
use Illuminate\Http\Request;
use App\Classes\ApiResponseClass;
use App\Http\Resources\TransaksiResource;
use Illuminate\Support\Str;

class TransaksiController extends Controller
{
    protected $transaksiRepository;

    public function __construct(TransaksiRepositoryInterface $transaksiRepository)
    {
        $this->transaksiRepository = $transaksiRepository;
    }

    private function checkAdmin($method)
    {
        if (!in_array(auth()->user()->role, ['admin', 'kasir'])) {
            return response()->json([
                'message' => 'Unauthorized. Only admin or kasir can perform this action.',
            ], 403);
        }
    }

    public function index()
    {
        $transaksi = $this->transaksiRepository->index();
        return ApiResponseClass::sendResponse(TransaksiResource::collection($transaksi),'',200);
    }

    public function show($id)
    {
        if ($response = $this->checkAdmin(__FUNCTION__)) {
            return $response;
        }

        $transaksi = $this->transaksiRepository->getById($id);
        return ApiResponseClass::sendResponse(new TransaksiResource($transaksi), 'Transaksi GetByID Success', 200);
    }

    public function store(StoreTransaksiRequest $request)
    {
        if ($response = $this->checkAdmin(__FUNCTION__)) {
            return $response;
        }

        $validated = $request->validated();
        $validated['id_outlet'] = auth()->user()->id_outlet;
        $validated['kode_invoice'] = $this->generateKodeInvoice();
        $validated['id_user'] = auth()->id();

        $transaksi = $this->transaksiRepository->store($validated);

        if ($request->has('details') && is_array($request->details)) {
            foreach ($request->details as $detail) {
                $transaksi->detailTransaksi()->create([
                    'id_paket' => $detail['id_paket'],
                    'qty' => $detail['qty'],
                    'keterangan' => $detail['keterangan'] ?? null,
                ]);
            }
        }

        return ApiResponseClass::sendResponse(
            new TransaksiResource($transaksi),
            'Transaksi Create Success',
            201
        );
    }

protected function generateKodeInvoice()
{
    $date = now()->format('Ymd');
    $random = strtoupper(Str::random(6));
    return "INV-{$date}-{$random}";
}

public function update(UpdateTransaksiRequest $request, $id)
{
    if ($response = $this->checkAdmin(__FUNCTION__)) {
        return $response;
    }

    $validated = $request->validated();
    $validated['id_outlet'] = auth()->user()->id_outlet;
    $validated['id_user'] = auth()->id();

    $transaksi = $this->transaksiRepository->getById($id);
    if ($validated['status'] === 'diambil' && $transaksi->status_pembayaran !== 'dibayar') {
        return ApiResponseClass::sendResponse(
            null,
            'Transaksi tidak dapat diubah menjadi "diambil" karena status pembayaran belum lunas.',
            400
        );
    }
    $transaksi = $this->transaksiRepository->update($validated, $id);


    if ($request->has('details') && is_array($request->details)) {

        $transaksi->detailTransaksi()->delete();

        foreach ($request->details as $detail) {
            $transaksi->detailTransaksi()->create([
                'id_paket' => $detail['id_paket'],
                'qty' => $detail['qty'],
                'keterangan' => $detail['keterangan'] ?? null,
            ]);
        }
    }

    return ApiResponseClass::sendResponse(
        new TransaksiResource($transaksi),
        'Transaksi Update Success',
        200
    );
}



    public function destroy($id)
    {
        if ($response = $this->checkAdmin(__FUNCTION__)) {
            return $response;
        }

        $this->transaksiRepository->delete($id);
        return ApiResponseClass::sendResponse('Transaksi Delete Success', 204);
    }
}
