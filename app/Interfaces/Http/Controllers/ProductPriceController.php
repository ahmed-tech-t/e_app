<?php

namespace App\Interfaces\Http\Controllers;

use App\Application\DTOs\UpdateProductPriceDtoDto;
use App\Application\Services\ProductPriceService;
use App\Infrastructure\Persistence\utils\PriceType;
use App\Interfaces\Http\Requests\Product\price\ProductPriceHistoryRequest;
use App\Interfaces\Http\Requests\Product\price\UpdateProductPriceRequest;
use App\Interfaces\Http\Resources\ProductPriceResource;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\Log;

class ProductPriceController extends Controller
{

    use HttpResponses;

    public function __construct(private ProductPriceService $productPriceService)
    {
    }

    public function setNewPrice(UpdateProductPriceRequest $request)
    {
        $dto = $request->toDto();
        return $this->success($this->productPriceService->setNewPrice($dto));
    }

    public function getProductPriceHistory(ProductPriceHistoryRequest $request)
    {
        $data = $request->validated();
        $type = PriceType::tryFrom($data['type'] ?? null);

        $data = $this->productPriceService->getProductPriceHistory($data['product_id'], $type);
        return $this->success(ProductPriceResource::collection($data));
    }
}