<?php
namespace App\Domain\Repo;

use Illuminate\Pagination\LengthAwarePaginator;
interface BaseRepo
{
    public function findAll();
    public function findById(int $id);
    public function codeExists(string $code): bool;
    public function getPaginatedItems($perPage): LengthAwarePaginator;
    public function create($entity);
    public function update($entity);
    public function destory(int $id): string;
}