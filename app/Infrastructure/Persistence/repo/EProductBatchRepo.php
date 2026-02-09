<?php
namespace App\Infrastructure\Persistence\repo;

use App\Application\Mapper\ProductBatchMapper;
use App\Domain\Repo\ProductBatchRepo;
use App\Infrastructure\Persistence\Models\ProductBatch;


class EProductBatchRepo extends BaseERepo implements ProductBatchRepo {
     protected $modelClass = ProductBatch::class;
     protected $mapper = ProductBatchMapper::class;
}