<?php

namespace Tests\Feature;

use App\Infrastructure\Persistence\Models\Supplier;
use App\Utils\Constants;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SupplierTest extends TestCase
{
    use RefreshDatabase;

    private string $supplierApiUrl;

    protected function setUp(): void
    {
        parent::setUp();
        $this->supplierApiUrl = Constants::BASE_API_URL.'/v1/suppliers';
    }

    public function test_supplier_crud_operations(): void
    {
        // 1. Create Supplier
        $createPayload = [
            'name' => 'Al-Ahram Supplies',
            'contact_person' => 'Mahmoud Khalil',
            'phone' => '01011223344',
            'email' => 'contact@ahram.com',
            'address' => 'Cairo, Egypt',
        ];

        $response = $this->postJson($this->supplierApiUrl, $createPayload);
        $response->assertStatus(200);

        $this->assertDatabaseHas('suppliers', [
            'name' => 'Al-Ahram Supplies',
            'phone' => '01011223344',
        ]);

        $supplier = Supplier::where('name', 'Al-Ahram Supplies')->first();
        $this->assertNotNull($supplier);

        // 2. Read (Index and Show)
        $this->getJson($this->supplierApiUrl)->assertStatus(200);
        $this->getJson("{$this->supplierApiUrl}/{$supplier->id}")->assertStatus(200);

        // 3. Update Supplier
        $updatePayload = [
            'name' => 'Al-Ahram Global Supplies',
            'contact_person' => 'Mahmoud Khalil',
            'phone' => '01011223344',
            'email' => 'info@ahram-global.com',
            'address' => 'Giza, Egypt',
        ];

        $this->putJson("{$this->supplierApiUrl}/{$supplier->id}", $updatePayload)->assertStatus(200);
        $this->assertDatabaseHas('suppliers', [
            'name' => 'Al-Ahram Global Supplies',
            'email' => 'info@ahram-global.com',
        ]);

        // 4. Delete Supplier (Soft delete)
        $this->deleteJson("{$this->supplierApiUrl}/{$supplier->id}")->assertStatus(200);
        $this->assertSoftDeleted('suppliers', ['id' => $supplier->id]);
    }
}
