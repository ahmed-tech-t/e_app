<?php
namespace App\Infrastructure\Persistence\repo;

use App\Application\Mapper\TestMapper;
use App\Domain\Repo\TestRepo;
use App\Infrastructure\Persistence\Models\Test;


class ETestRepo extends BaseERepo implements TestRepo {
     protected $modelClass = Test::class;
     protected $mapper = TestMapper::class;
}