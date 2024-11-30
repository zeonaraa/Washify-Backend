<?php

namespace App\Http\Controllers;

use App\Models\Paket;
use Illuminate\Http\Request;
use App\Interfaces\PaketRepositoryInterface;
use App\Http\Requests\StorePaketRequest;
use App\Http\Requests\UpdatePaketRequest;
use App\Classes\ApiResponseClass;
use App\Http\Resources\PaketResource;

class PaketController extends Controller
{

    protected $paketRepository;

    public function __construct(PaketRepositoryInterface $paketRepository)
    {
        $this->paketRepository = $paketRepository;
    }

    private function checkAdmin($method)
    {
        if ($method !== 'index' && auth()->user()->role !== 'admin') {
            return response()->json([
                'message' => 'Unauthorized. Only admin can perform this action.',
            ], 403);
        }
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $paket = $this->paketRepository->index();
        return ApiResponseClass::sendResponse(PaketResource::collection($paket),'',200);
    }

    public function show($id)
    {
        if ($response = $this->checkAdmin(__FUNCTION__)) {
            return $response;
        }

        $paket = $this->paketRepository->getById($id);
        return ApiResponseClass::sendResponse(new PaketResource($paket), 'Paket GetByID Success', 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePaketRequest $request)
    {
        if ($response = $this->checkAdmin(__FUNCTION__)) {
            return $response;
        }

        $validated = $request->validated();
        $paket = $this->paketRepository->store($validated);
        return ApiResponseClass::sendResponse(new PaketResource($paket), 'Paket Create Success', 201);

    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePaketRequest $request, $id)
    {
        if ($response = $this->checkAdmin(__FUNCTION__)) {
            return $response;
        }

        $validated = $request->validated();
        $paket = $this->paketRepository->update($validated, $id);
        return ApiResponseClass::sendResponse(new PaketResource($paket), 'Update Update Success', 200);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        if ($response = $this->checkAdmin(__FUNCTION__)) {
            return $response;
        }

        $this->paketRepository->delete($id);
        return ApiResponseClass::sendResponse('Paket Delete Success', 204);
    }
}
