<?php

namespace Tests\Unit;

use App\Application\Services\SalesReturnsService;
use Illuminate\Support\Collection;
use PHPUnit\Framework\TestCase;

class SalesReturnsServiceTest extends TestCase
{
    protected SalesReturnsService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new SalesReturnsService(
            repo: $this->createMock('App\Domain\Repo\SalesReturnsRepo'),
            salesReturnItemRepo: $this->createMock('App\Domain\Repo\SalesReturnItemRepo'),
            salesService: $this->createMock('App\Application\Services\SalesService'),
            stockService: $this->createMock('App\Application\Services\StockService'),
            stockMovementRepo: $this->createMock('App\Domain\Repo\StockMovementRepo')
        );
    }

    public function test_update_quantity_with_empty_inputs()
    {
        $saleArray = new Collection;
        $returnedArray = new Collection;

        $result = $this->service->updateQuantity($saleArray, $returnedArray);

        $this->assertInstanceOf(Collection::class, $result);
        $this->assertCount(0, $result);
    }

    public function test_update_quantity_with_sale_items_and_no_returns()
    {
        $saleItems = new Collection([
            (object) ['product_batch_id' => 1, 'quantity' => 10],
            (object) ['product_batch_id' => 2, 'quantity' => 5],
        ]);
        $returnedItems = new Collection;

        $result = $this->service->updateQuantity($saleItems, $returnedItems);

        $this->assertCount(2, $result);
        $this->assertEquals(10, $result[0]->quantity);
        $this->assertEquals(5, $result[1]->quantity);
    }

    public function test_update_quantity_with_sale_items_and_matching_returns()
    {
        $saleItems = new Collection([
            (object) ['product_batch_id' => 1, 'quantity' => 10],
            (object) ['product_batch_id' => 2, 'quantity' => 5],
        ]);
        $returnedItems = new Collection([
            (object) ['product_batch_id' => 1, 'quantity' => 3],
            (object) ['product_batch_id' => 2, 'quantity' => 2],
        ]);

        $result = $this->service->updateQuantity($saleItems, $returnedItems);

        $this->assertCount(2, $result);
        $this->assertEquals(7, $result[0]->quantity);
        $this->assertEquals(3, $result[1]->quantity);
    }

    public function test_update_quantity_with_sale_items_and_returns_for_different_batches()
    {
        $saleItems = new Collection([
            (object) ['product_batch_id' => 1, 'quantity' => 10],
            (object) ['product_batch_id' => 2, 'quantity' => 5],
        ]);
        $returnedItems = new Collection([
            (object) ['product_batch_id' => 1, 'quantity' => 3],
            (object) ['product_batch_id' => 3, 'quantity' => 2],
        ]);

        $result = $this->service->updateQuantity($saleItems, $returnedItems);

        $this->assertCount(2, $result);
        $this->assertEquals(7, $result[0]->quantity);
        $this->assertEquals(5, $result[1]->quantity);
    }

    public function test_update_quantity_with_returns_exceeding_sale_quantities()
    {
        $saleItems = new Collection([
            (object) ['product_batch_id' => 1, 'quantity' => 5],
            (object) ['product_batch_id' => 2, 'quantity' => 10],
        ]);
        $returnedItems = new Collection([
            (object) ['product_batch_id' => 1, 'quantity' => 8],
            (object) ['product_batch_id' => 2, 'quantity' => 5],
        ]);

        // 2. Expect (Must come BEFORE the method call)
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("returned quantity is more than sale quantity");

        // 3. Act
        $this->service->updateQuantity($saleItems, $returnedItems);
    }

    public function test_update_quantity_with_multiple_returns_for_same_batch()
    {
        $saleItems = new Collection([
            (object) ['product_batch_id' => 1, 'quantity' => 10],
            (object) ['product_batch_id' => 2, 'quantity' => 5],
        ]);
        $returnedItems = new Collection([
            (object) ['product_batch_id' => 1, 'quantity' => 3],
            (object) ['product_batch_id' => 1, 'quantity' => 2],
            (object) ['product_batch_id' => 2, 'quantity' => 1],
        ]);

        $result = $this->service->updateQuantity($saleItems, $returnedItems);

        $this->assertCount(2, $result);
        $this->assertEquals(5, $result[0]->quantity);
        $this->assertEquals(4, $result[1]->quantity);
    }

    public function test_update_quantity_with_all_items_returned()
    {
        $saleItems = new Collection([
            (object) ['product_batch_id' => 1, 'quantity' => 5],
            (object) ['product_batch_id' => 2, 'quantity' => 3],
        ]);
        $returnedItems = new Collection([
            (object) ['product_batch_id' => 1, 'quantity' => 5],
            (object) ['product_batch_id' => 2, 'quantity' => 3],
        ]);

        $result = $this->service->updateQuantity($saleItems, $returnedItems);

        $this->assertCount(0, $result);
    }
}
