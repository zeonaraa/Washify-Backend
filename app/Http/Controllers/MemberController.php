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

    private function checkAccess($method)
    {
        if (auth()->user()->role === 'kasir' && !in_array($method, ['index', 'show'])) {
            return response()->json([
                'message' => 'Unauthorized. Kasir can only perform index and show actions.',
            ], 403);
        }

        if (auth()->user()->role !== 'admin' && auth()->user()->role !== 'kasir') {
            return response()->json([
                'message' => 'Unauthorized. Only admin or kasir can perform this action.',
            ], 403);
        }

        return null;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Tidak perlu checkAccess karena kasir memiliki akses
        $idOutlet = auth()->user()->id_outlet;
        $members = $this->memberRepository->indexByOutlet($idOutlet);

        return ApiResponseClass::sendResponse(MemberResource::collection($members), '', 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        if ($response = $this->checkAccess(__FUNCTION__)) {
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
        if ($response = $this->checkAccess(__FUNCTION__)) {
            return $response;
        }

        $validated = $request->validated();
        $validated['id_outlet'] = auth()->user()->id_outlet;

        $member = $this->memberRepository->store($validated);

        return ApiResponseClass::sendResponse(new MemberResource($member), 'Member Create Success', 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMemberRequest $request, $id)
    {
        if ($response = $this->checkAccess(__FUNCTION__)) {
            return $response;
        }

        $validated = $request->validated();
        $member = Member::findOrFail($id);

        if ($member->id_outlet !== auth()->user()->id_outlet) {
            return response()->json([
                'message' => 'Unauthorized. You cannot update a member from another outlet.',
            ], 403);
        }

        $member = $this->memberRepository->update($validated, $id);

        return ApiResponseClass::sendResponse(new MemberResource($member), 'Member Update Success', 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        if ($response = $this->checkAccess(__FUNCTION__)) {
            return $response;
        }

        $this->memberRepository->delete($id);
        return ApiResponseClass::sendResponse('Member Delete Success', 204);
    }
}
