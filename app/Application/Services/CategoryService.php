<?php

namespace App\Application\Services;

use App\Application\DTOs\CategoryDto;
use App\Domain\Entities\CategoryEntity;
use App\Domain\Repo\Product\CategoryRepo;
use App\Traits\CodeGenerator;

class CategoryService
{
    use CodeGenerator;
    public function __construct(private CategoryRepo $repo)
    {
    }

    public function findAll()
    {
        return $this->repo->findAll();
    }

    public function findById(int $id)
    {
        return $this->repo->findById($id);
    }

    public function create(CategoryDto $dto): CategoryEntity
    {
        $entity = CategoryEntity::create($dto->toArray());
        $entity->code = $this->getCode($entity->name_en);

        return $this->repo->create($entity);
    }

    public function update(CategoryDto $dto, int $id)
    {
        $entity = $this->repo->findById($id);
        return $this->repo->update($entity->update($dto->toArray()));
    }

    public function destory(int $id)
    {
        return $this->repo->destory($id);
    }

    public function codeExists(string $code): bool
    {
        return $this->repo->codeExists($code);
    }

    public function getCode($name): string
    {
        do {
            $code = $this->generateCode(name: $name ?? 'ABC');
        } while ($this->repo->codeExists($code));
        return $code;
    }
}