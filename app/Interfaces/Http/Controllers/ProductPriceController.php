<?php

namespace App\Interfaces\Http\Controllers;

use App\Application\DTOs\UpdateProductPriceDtoDto;
use App\Application\Services\ProductPriceService;
use App\Interfaces\Http\Requests\Product\price\UpdateProductPriceRequest;
use App\Traits\HttpResponses;

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
}