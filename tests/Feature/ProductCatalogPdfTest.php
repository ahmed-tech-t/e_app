<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductCatalogPdfTest extends TestCase
{
    use RefreshDatabase;

    public function test_catalog_page_renders_successfully(): void
    {
        $category = $this->createCategory(['name_ar' => 'أدوات منزلية']);
        $saleUnit = $this->createSaleUnit(['name_ar' => 'قطعة']);
        $product = $this->createProduct([
            'category_id' => $category->id,
            'sale_unit_id' => $saleUnit->id,
            'brand' => 'Luminarc',
            'name_ar' => 'طقم كاسات',
        ]);

        $response = $this->get(route('products.catalog'));

        $response->assertStatus(200);
        $response->assertViewIs('products.catalog');
        $response->assertViewHasAll(['categories', 'brands', 'productsData']);
        $response->assertSee('Luminarc');
        $response->assertSee('طقم كاسات');
    }

    public function test_products_index_contains_catalog_menu_option(): void
    {
        $response = $this->get(route('products.index'));

        $response->assertStatus(200);
        $response->assertSee(route('products.catalog'));
    }

    public function test_catalog_download_generates_pdf_for_all_products(): void
    {
        $category = $this->createCategory(['name_ar' => 'أدوات مطبخ']);
        $saleUnit = $this->createSaleUnit();
        $product = $this->createProduct([
            'category_id' => $category->id,
            'sale_unit_id' => $saleUnit->id,
            'name_ar' => 'طبق عشاء',
            'brand' => 'Arcopal',
        ]);

        $response = $this->post(route('products.catalog.download'), [
            'select_all' => '1',
            'price_type' => 'retail',
        ]);

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
        $this->assertStringContainsString('catalog_', $response->headers->get('content-disposition'));
        $this->assertStringContainsString('.pdf', $response->headers->get('content-disposition'));
    }

    public function test_catalog_download_filtered_by_category(): void
    {
        $cat1 = $this->createCategory(['name_ar' => 'فئة 1']);
        $cat2 = $this->createCategory(['name_ar' => 'فئة 2']);
        $saleUnit = $this->createSaleUnit();

        $this->createProduct([
            'category_id' => $cat1->id,
            'sale_unit_id' => $saleUnit->id,
            'name_ar' => 'منتج فئة 1',
        ]);

        $response = $this->post(route('products.catalog.download'), [
            'category_id' => $cat1->id,
            'select_all' => '1',
            'price_type' => 'retail',
        ]);

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
    }

    public function test_catalog_download_filtered_by_brand(): void
    {
        $category = $this->createCategory();
        $saleUnit = $this->createSaleUnit();

        $this->createProduct([
            'category_id' => $category->id,
            'sale_unit_id' => $saleUnit->id,
            'brand' => 'BrandAlpha',
            'name_ar' => 'منتج ألفا',
        ]);

        $response = $this->post(route('products.catalog.download'), [
            'brand' => 'BrandAlpha',
            'select_all' => '1',
            'price_type' => 'wholesale',
        ]);

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
    }

    public function test_catalog_download_with_selected_product_ids(): void
    {
        $category = $this->createCategory();
        $saleUnit = $this->createSaleUnit();

        $product1 = $this->createProduct([
            'category_id' => $category->id,
            'sale_unit_id' => $saleUnit->id,
            'name_ar' => 'منتج 1',
        ]);
        $product2 = $this->createProduct([
            'category_id' => $category->id,
            'sale_unit_id' => $saleUnit->id,
            'name_ar' => 'منتج 2',
        ]);

        $response = $this->post(route('products.catalog.download'), [
            'select_all' => '0',
            'product_ids' => [$product1->id, $product2->id],
            'price_type' => 'both',
        ]);

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
    }

    public function test_catalog_download_redirects_with_error_when_no_products_match(): void
    {
        $response = $this->post(route('products.catalog.download'), [
            'category_id' => 999999,
            'select_all' => '1',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('error');
    }
}
