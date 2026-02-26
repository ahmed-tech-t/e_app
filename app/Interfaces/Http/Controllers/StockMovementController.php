<?php

namespace App\Interfaces\Http\Controllers;

use App\Application\Services\StockService;
use App\Interfaces\Http\Requests\StockMovement\TransferProductRequest;
use App\Traits\HttpResponses;

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
}