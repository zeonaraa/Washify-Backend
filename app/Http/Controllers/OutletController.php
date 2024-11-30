<?php

namespace App\Http\Controllers;

use App\Interfaces\OutletsRepositoryInterface;
use App\Http\Requests\StoreOutletRequest;
use App\Http\Requests\UpdateOutletRequest;
use Illuminate\Http\Request;

class OutletController extends Controller
{
    protected $outletsRepository;

    public function __construct(OutletsRepositoryInterface $outletsRepository)
    {
        $this->outletsRepository = $outletsRepository;
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
        $outlets = $this->outletsRepository->index();
        return response()->json($outlets, 200);
    }

    public function show($id)
    {
        if ($response = $this->checkAdmin(__FUNCTION__)) {
            return $response;
        }

        $outlet = $this->outletsRepository->getById($id);
        return response()->json($outlet, 200);
    }

    public function store(StoreOutletRequest $request)
    {
        if ($response = $this->checkAdmin(__FUNCTION__)) {
            return $response;
        }

        $validated = $request->validated();
        $outlet = $this->outletsRepository->store($validated);
        return response()->json($outlet, 201);
    }

    public function update(UpdateOutletRequest $request, $id)
    {
        if ($response = $this->checkAdmin(__FUNCTION__)) {
            return $response;
        }

        $validated = $request->validated();
        $outlet = $this->outletsRepository->update($validated, $id);
        return response()->json($outlet, 200);
    }

    public function destroy($id)
    {
        if ($response = $this->checkAdmin(__FUNCTION__)) {
            return $response;
        }

        $this->outletsRepository->delete($id);
        return response()->json(null, 204);
    }
}
