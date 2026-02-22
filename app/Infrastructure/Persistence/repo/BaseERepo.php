<?php

namespace App\Infrastructure\Persistence\repo;

use App\Domain\Repo\BaseRepo;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BaseERepo implements BaseRepo
{

    protected $modelClass;
    protected $mapper;

    protected array $defaultRelationships = [];

    protected array $withForPaginate = [];


    public function getPaginatedItems($perPage = 5): LengthAwarePaginator
    {
        $query = ($this->modelClass)::query();
        if (!empty($this->withForPaginate)) {
            $query->with($this->withForPaginate);
        }

        $items = $query->paginate($perPage)
            ->through(
                fn($item) =>
                ($this->mapper)::modelToEntity($item)
            );
        return $items;
    }

    public function codeExists(string $code): bool
    {
        return ($this->modelClass)::where('code', $code)->exists();
    }

    /**
     * @inheritDoc
     */
    public function create($entity)
    {
        return DB::transaction(function () use ($entity) {
            $model = ($this->modelClass)::create($entity->toArray())->refresh();
            return ($this->mapper)::modelToEntity($model);
        });
    }

    /**
     * @inheritDoc
     */
    public function destroy(int $id): string
    {

        $deletedCount = ($this->modelClass)::destroy($id);
        if ($deletedCount === 0) {
            throw new ModelNotFoundException("Item with ID $id not found.");
        }
        return 'item deleted successfully';
    }


    public function findAll()
    {
        $entities = ($this->modelClass)::all()->map(fn($model) => ($this->mapper)::modelToEntity($model));
        return $entities;
    }

    /**
     * @inheritDoc
     */
    public function findById(int $id)
    {

        $query = ($this->modelClass)::query();
        if (!empty($this->defaultRelationships)) {
            $query->with($this->defaultRelationships);
        }

        $model = $query->findOrFail($id);
        return ($this->mapper)::modelToEntity($model);
    }

    /**
     * @inheritDoc
     */
    public function update($entity)
    {
        //  Log::info("Your message here", ['entity' => $entity]);
        $model = ($this->modelClass)::findOrFail($entity->id);
        ($this->modelClass)::where('id', $entity->id)->update($entity->toArray());
        return ($this->mapper)::modelToEntity($model->refresh());
    }
}