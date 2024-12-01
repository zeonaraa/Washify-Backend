<?php

namespace App\Http\Controllers;

use App\Interfaces\TransaksiRepositoryInterface;
use App\Http\Requests\StoreTransaksiRequest;
use App\Http\Requests\UpdateTransaksiRequest;
use Illuminate\Http\Request;
use App\Classes\ApiResponseClass;
use App\Http\Resources\TransaksiResource;

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

    $validated['id_user'] = auth()->id();

    $transaksi = $this->transaksiRepository->store($validated);

    return ApiResponseClass::sendResponse(
        new TransaksiResource($transaksi),
        'Transaksi Create Success',
        201
    );
}


public function update(UpdateTransaksiRequest $request, $id)
{
    if ($response = $this->checkAdmin(__FUNCTION__)) {
        return $response;
    }

    $validated = $request->validated();

    $validated['id_user'] = auth()->id();

    $transaksi = $this->transaksiRepository->update($validated, $id);

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
