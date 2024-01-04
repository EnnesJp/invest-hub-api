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
                        ->pagination($request->per_page, $request->page);

        return $this->success(
            $transactions,
            null,
            Response::HTTP_OK
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
        Transaction $transaction
    ): JsonResponse {
        if (!$this->canAccess($transaction)) {
            return $this->error([], AuthConstants::PERMISSION);
        }

        $transaction->update($request->all());

        return $this->success(
            new TransactionResource($transaction),
            TransactionConstants::UPDATE
        );
    }

    public function destroy(Transaction $transaction): JsonResponse
    {
        if (!$this->canAccess($transaction)) {
            return $this->error([], AuthConstants::PERMISSION);
        }

        $transaction->delete();

        return $this->success(
            new TransactionResource($transaction),
            TransactionConstants::DELETE
        );
    }
}
