<?php

namespace App\Http\Controllers;

use App\Interfaces\OutletsRepositoryInterface;
use App\Http\Requests\StoreOutletRequest;
use App\Http\Requests\UpdateOutletRequest;
use Illuminate\Http\Request;
use App\Classes\ApiResponseClass;
use App\Http\Resources\OutletResource;

class OutletController extends Controller
{
    protected $outletsRepository;

    public function __construct(OutletsRepositoryInterface $outletsRepository)
    {
        $this->outletsRepository = $outletsRepository;
    }

    private function checkAdmin($method)
    {
        if ($method !== 'index' && (auth()->user()->role !== 'admin' && auth()->user()->role !== 'kasir' && auth()->user()->role !== 'owner')) {
            return response()->json([
                'message' => 'Unauthorized. Only admin or kasir can perform this action.',
            ], 403);
        }
    }

    public function index()
    {
        $outlets = $this->outletsRepository->index();
        return ApiResponseClass::sendResponse(OutletResource::collection($outlets),'',200);
    }

    public function show($id)
    {
        if ($response = $this->checkAdmin(__FUNCTION__)) {
            return $response;
        }

        $outlet = $this->outletsRepository->getById($id);

        if (!$outlet) {
            return ApiResponseClass::sendResponse(null, 'Outlet not found', 404);
        }

        return ApiResponseClass::sendResponse(new OutletResource($outlet), 'Outlet GetByID Success', 200);
    }

    public function store(StoreOutletRequest $request)
    {
        if ($response = $this->checkAdmin(__FUNCTION__)) {
            return $response;
        }

        $validated = $request->validated();
        $outlet = $this->outletsRepository->store($validated);
        return ApiResponseClass::sendResponse(new OutletResource($outlet), 'Outlet Create Success', 201);
    }

    public function update(UpdateOutletRequest $request, $id)
    {
        if ($response = $this->checkAdmin(__FUNCTION__)) {
            return $response;
        }

        $validated = $request->validated();
        $outlet = $this->outletsRepository->update($validated, $id);
        return ApiResponseClass::sendResponse(new OutletResource($outlet), 'Outlet Update Success', 200);
    }


    public function destroy($id)
    {
        if ($response = $this->checkAdmin(__FUNCTION__)) {
            return $response;
        }

        $this->outletsRepository->delete($id);
        return ApiResponseClass::sendResponse('Outlet Delete Success', 204);
    }
}
