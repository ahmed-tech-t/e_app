<?php

namespace App\Interfaces\Http\Controllers;

use App\Application\Services\StockService;
use App\Domain\PaginatorMeta;
use App\Interfaces\Http\Requests\StockMovement\StockMovementSearchRequest;
use App\Interfaces\Http\Requests\StockMovement\TransferProductRequest;
use App\Interfaces\Http\Resources\StockMovementResource;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\Log;

class StockMovementController extends Controller
{
    use HttpResponses;

    public function __construct(
        private StockService $stockService
    ) {

    }
    public function transfer(TransferProductRequest $request)
    {
        $dto = $request->toDto();
        return $this->success($this->stockService->transferProduct($dto));
    }



    public function search(StockMovementSearchRequest $request)
    {
        $dto = $request->toDto();
        $result = $this->stockService->search($dto);
        $meta = new PaginatorMeta($result);
        return $this->success(
            data: StockMovementResource::collection($result),
            meta: $meta->toArray()
        );
    }
}