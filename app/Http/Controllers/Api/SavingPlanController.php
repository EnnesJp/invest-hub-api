<?php

namespace App\Http\Controllers\Api;

use App\Http\Traits\Access;
use App\Http\Traits\HttpResponses;
use App\Http\Controllers\Controller;
use App\Constants\AuthConstants;
use App\Http\Resources\SavingPlanResource;
use App\Http\Resources\SelectResource;
use App\Constants\SavingPlanConstants;
use App\Http\Requests\Api\SavingPlanRequest;
use App\Models\SavingPlan;
use App\Repositories\SavingPlanRepository;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SavingPlanController extends Controller
{
    use Access;
    use HttpResponses;

    public function index(Request $request): JsonResponse
    {
        $savingPlans = auth()
            ->user()
            ->savingPlans()
            ->latest()
            ->paginate($request->per_page ?? 20);

        foreach ($savingPlans as &$savingPlan) {
            $savingPlan->total_accumulated = floatval(auth()->user()->assets()->where('saving_plan_id', $savingPlan->id)->sum('value'));
        }

        return $this->success(
            SavingPlanResource::collection($savingPlans),
            null,
            Response::HTTP_OK,
            $this->getMeta($savingPlans)
        );
    }

    public function show(SavingPlan $savingPlan): JsonResponse
    {
        if (!$this->canAccess($savingPlan)) {
            return $this->error([], AuthConstants::PERMISSION);
        }

        return $this->success(new SavingPlanResource($savingPlan));
    }

    public function store(
        SavingPlanRequest $request,
        SavingPlanRepository $repository
    ): JsonResponse {
        $savingPlan = $repository->create($request->all());

        return $this->success(
            new SavingPlanResource($savingPlan),
            SavingPlanConstants::STORE,
            Response::HTTP_CREATED
        );
    }

    public function update(
        SavingPlanRequest $request,
        SavingPlan $savingPlan,
        SavingPlanRepository $repository
    ): JsonResponse {
        if (!$this->canAccess($savingPlan)) {
            return $this->error([], AuthConstants::PERMISSION);
        }

        $savingPlan = $repository->update($savingPlan, $request->all());

        return $this->success(
            new SavingPlanResource($savingPlan),
            SavingPlanConstants::UPDATE,
            Response::HTTP_OK
        );
    }

    public function destroy(
        SavingPlan $savingPlan,
        SavingPlanRepository $repository
    ): JsonResponse {
        if (!$this->canAccess($savingPlan)) {
            return $this->error([], AuthConstants::PERMISSION);
        }

        $repository->delete($savingPlan);

        return $this->success([], SavingPlanConstants::DESTROY);
    }

    public function getSavingPlanSelect(): JsonResponse
    {
        $assets = auth()
            ->user()
            ->savingPlans()
            ->selectRaw('id as value, name as label')
            ->get();

        return $this->success(
            SelectResource::collection($assets),
            null,
            Response::HTTP_OK
        );
    }
}
