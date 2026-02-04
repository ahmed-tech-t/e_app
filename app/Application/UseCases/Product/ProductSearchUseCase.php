<?php

namespace App\Application\UseCases\Product;


use App\Application\DTOs\ProductSearchDto;
use App\Domain\Repo\ProductRepo;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProductSearchUseCase
{

    public function __construct(private ProductRepo $repo)
    {
    }

    public function execute(ProductSearchDto $dto)
    {
        $products = $this->repo->search($dto, $dto->perPage);

        if ($dto->page > $products->lastPage()) {
            throw new NotFoundHttpException("Page not found , Max page is " . $products->lastPage());
        }

        return $products;
    }

}