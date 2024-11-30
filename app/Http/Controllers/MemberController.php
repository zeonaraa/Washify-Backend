<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;
use App\Interfaces\MemberRepositoryInterface;
use App\Http\Requests\StoreMemberRequest;
use App\Http\Requests\UpdateMemberRequest;
use App\Classes\ApiResponseClass;
use App\Http\Resources\MemberResource;

class MemberController extends Controller
{

    protected $memberRepository;

    public function __construct(MemberRepositoryInterface $memberRepository)
    {
        $this->memberRepository = $memberRepository;
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
        $member = $this->memberRepository->index();
        return ApiResponseClass::sendResponse(MemberResource::collection($member),'',200);
    }

    public function show($id)
    {
        if ($response = $this->checkAdmin(__FUNCTION__)) {
            return $response;
        }

        $member = $this->memberRepository->getById($id);
        return ApiResponseClass::sendResponse(new MemberResource($member), 'Member GetByID Success', 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMemberRequest $request)
    {
        if ($response = $this->checkAdmin(__FUNCTION__)) {
            return $response;
        }

        $validated = $request->validated();
        $member = $this->memberRepository->store($validated);
        return ApiResponseClass::sendResponse(new MemberResource($member), 'Member Create Success', 201);

    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMemberRequest $request, $id)
    {
        if ($response = $this->checkAdmin(__FUNCTION__)) {
            return $response;
        }

        $validated = $request->validated();
        $member = $this->memberRepository->update($validated, $id);
        return ApiResponseClass::sendResponse(new MemberResource($member), 'Member Update Success', 200);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        if ($response = $this->checkAdmin(__FUNCTION__)) {
            return $response;
        }

        $this->memberRepository->delete($id);
        return ApiResponseClass::sendResponse('Member Delete Success', 204);
    }
}
