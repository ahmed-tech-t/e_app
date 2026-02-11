<?php

namespace App\Interfaces\Http\Controllers;

use App\Application\Services\StockMovementService;
use App\Interfaces\Http\Requests\ProductBatch\StockMovement\StockMovementTransferRequest;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;

class StockMovementController extends Controller
{
    use HttpResponses;
    public function __construct(private StockMovementService $service)
    {

    }

    public function index()
    {
        return $this->service->index();
    }
    public function transfer(StockMovementTransferRequest $request)
    {
        $dto = $request->toDto();
        $this->service->transfer($dto);
        return $this->success();
    }
}