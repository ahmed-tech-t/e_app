<?php
namespace App\Application\UseCases\Product;

use App\Application\Mapper\ProductMapper;
use App\Domain\Repo\Product\ProductRepo;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class GetAllProductUseCase
{

    public function __construct(private ProductRepo $repo)
    {
    }

    public function execute($currentPage, $perPage): LengthAwarePaginator
    {
        $products = $this->repo->getPaginatedProducts($perPage);

        if ($currentPage > $products->lastPage()) {
            throw new NotFoundHttpException("Page not found , Max page is " . $products->lastPage());
        }

        return $products;
    }
}