<?php

namespace App\Http\Controllers;

use App\Interfaces\UserRepositoryInterface;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Http\Request;
use App\Classes\ApiResponseClass;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    private function checkAdmin($method)
    {

        $user = auth()->user();

        if ($user->role === 'kasir' && $method !== 'show') {
            return response()->json([
                'message' => 'Unauthorized. Kasir can only perform get by id action.',
            ], 403);
        }

        if ($user->role !== 'admin' && $user->role !== 'kasir') {
            return response()->json([
                'message' => 'Unauthorized. Only admin can perform this action.',
            ], 403);
        }

        return null;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if ($response = $this->checkAdmin(__FUNCTION__)) {
            return $response;
        }

        $user = $this->userRepository->index();
        return ApiResponseClass::sendResponse(UserResource::collection($user), '', 200);
    }

    public function show($id)
    {
        if ($response = $this->checkAdmin(__FUNCTION__)) {
            return $response;
        }

        $user = $this->userRepository->getById($id);
        return ApiResponseClass::sendResponse(new UserResource($user), 'User GetByID Success', 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        if ($response = $this->checkAdmin(__FUNCTION__)) {
            return $response;
        }

        $validated = $request->validated();

        $validated['password'] = Hash::make($validated['password']);

        $user = $this->userRepository->store($validated);

        return ApiResponseClass::sendResponse(new UserResource($user), 'User Create Success', 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, $id)
    {
        if ($response = $this->checkAdmin(__FUNCTION__)) {
            return $response;
        }

        $validated = $request->validated();

        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        $user = $this->userRepository->update($validated, $id);

        return ApiResponseClass::sendResponse(new UserResource($user), 'User Update Success', 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        if ($response = $this->checkAdmin(__FUNCTION__)) {
            return $response;
        }

        $this->userRepository->delete($id);
        return ApiResponseClass::sendResponse('User Delete Success', 204);
    }
}
