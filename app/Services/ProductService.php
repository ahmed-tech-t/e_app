<?php
namespace App\Services;

use App\Models\Product;
use App\Traits\HttpResponses;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProductService
{
    use HttpResponses;
    public function getPaginatedProducts($currentPage = 1, $perPage = 5)
    {
        $products = Product::paginate($perPage);
        if ($currentPage > $products->lastPage()) {
            throw new NotFoundHttpException("Page not found , Max page is " . $products->lastPage());
        }

        return $products;
    }
}