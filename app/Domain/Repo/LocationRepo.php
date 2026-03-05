<?php
namespace App\Domain\Repo;

interface LocationRepo extends BaseRepo
{
    public function productLocations(string $code);
}

