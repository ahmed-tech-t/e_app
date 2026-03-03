<?php

namespace App\Domain\Repo;

interface ProductRepo extends BaseRepo
{
    public function findAllByLocation(int $locationId, int $perPage);
    public function findByLocation(int $productId, int $locationId);
}