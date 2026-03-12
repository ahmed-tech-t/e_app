<?php

namespace App\Application\Services;

use App\Traits\CodeGenerator;
use Illuminate\Log\Logger;
use Illuminate\Support\Facades\Log;
abstract class BaseService
{
    // The child class must define the repository
    protected object $repo;
    protected string $entityClass;

    protected $defaultCodeChar;

    use CodeGenerator;

    public function findAll()
    {
        return ($this->repo)->findAll();
    }



    public function getPaginatedItems($perPage)
    {
        return ($this->repo)->getPaginatedItems($perPage);
    }

    public function search($dto, $perPage)
    {
        return ($this->repo)->search($dto, $perPage);
    }

    public function findById(int $id)
    {
        return ($this->repo)->findById($id);
    }

    public function destroy(int $id)
    {
        return ($this->repo)->destroy($id);
    }

    public function codeExists(string $code): bool
    {
        return ($this->repo)->codeExists($code);
    }


    public function update($dto, int $id)
    {
        $entity = ($this->repo)->findById($id);
        return ($this->repo)->update($entity->update($dto->toArray()));
    }


    public function create($dto)
    {
        $entity = ($this->entityClass)::create($dto->toArray());
        $entity->code = $this->getCode($this->defaultCodeChar ?? $entity->name_en ?? $entity->name);

        return ($this->repo)->create($entity);
    }

    public function getCode($name): string
    {
        do {
            $code = $this->generateCode(name: $name ?? 'ABC');
        } while (($this->repo)->codeExists($code));
        return $code;
    }
}