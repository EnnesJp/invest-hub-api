<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Traits\Access;
use App\Http\Traits\HttpResponses;
use App\Http\Requests\Api\TransactionRequest;
use App\Constants\AuthConstants;
use App\Constants\TransactionConstants;
use App\Models\Transaction;
use App\Repositories\TransactionRepository;
use App\Http\Resources\TransactionResource;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\JsonResponse;

class TransactionController extends Controller
{
    use Access;
    use HttpResponses;

    public function index(Request $request): JsonResponse
    {
        $transactions = auth()
                        ->user()
                        ->transactions()
                        ->latest()
                        ->paginate($request->per_page ?? 20);

        return $this->success(
            TransactionResource::collection($transactions),
            null,
            Response::HTTP_OK,
            $this->getMeta($transactions)
        );
    }

    public function show(Transaction $transaction): JsonResponse
    {
        if (!$this->canAccess($transaction)) {
            return $this->error([], AuthConstants::PERMISSION);
        }

        return $this->success(new TransactionResource($transaction));
    }

    public function store(
        TransactionRequest $request,
        TransactionRepository $repository
    ): JsonResponse {
        $transaction = $repository->create($request->all());

        return $this->success(
            new TransactionResource($transaction),
            TransactionConstants::STORE,
            Response::HTTP_CREATED
        );
    }

    public function update(
        TransactionRequest $request,
        Transaction $transaction,
        TransactionRepository $repository
    ): JsonResponse {
        if (!$this->canAccess($transaction)) {
            return $this->error([], AuthConstants::PERMISSION);
        }

        $repository->update($transaction, $request->all());

        return $this->success(
            new TransactionResource($transaction),
            TransactionConstants::UPDATE
        );
    }

    public function destroy(
        Transaction $transaction,
        TransactionRepository $repository
    ): JsonResponse {
        if (!$this->canAccess($transaction)) {
            return $this->error([], AuthConstants::PERMISSION);
        }

        $repository->delete($transaction);

        return $this->success(
            new TransactionResource($transaction),
            TransactionConstants::DESTROY
        );
    }
}
