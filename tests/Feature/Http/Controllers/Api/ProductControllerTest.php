<?php

namespace Tests\Feature\Http\Controllers\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;
use Faker\Factory;
use SebastianBergmann\Environment\Console;

class ProductControllerTest extends TestCase
{

    use RefreshDatabase;

        /**
     * @test
     */
    public function non_authenticated_users_cannot_access_following_endpoints_for_the_product_api()
    {
        $index = $this->json('GET','/api/products');
        $index->assertStatus(401);

        $store = $this->json('POST','/api/products');
        $store->assertStatus(401);

        $show = $this->json('GET','/api/products/-1');
        $show->assertStatus(401);

        $update = $this->json('PUT','/api/products/-1');
        $update->assertStatus(401);

        $destroy = $this->json('DELETE','/api/products/-1');
        $destroy->assertStatus(401);
    }

    /**
     * @test
     */
    public function can_return_collection_of_paginated_products()
    {
        $product1 = $this->create('Product');
        $product2 = $this->create('Product');
        $product3 = $this->create('Product');

        $response = $this->actingAs($this->create('User',[],false),'api')->json('GET', '/api/products');

        // \Log::info(1, [$response->getContent()]);

        // note * used to define we have many items, as a collection is returned
        $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                '*' => ['id','name','slug','price','created_at']
            ],        
            'links' => ['first','last', 'prev','next'],
            'meta' => ['current_page', 'last_page', 'path', 'per_page', 'to','from','total']
        ]);
    }

    /**
     * @test
     */
    public function can_create_a_product()
    {
        $product = $this->data();
        $response = $this->actingAs($this->create('User',[],false),'api')->json('POST', '/api/products', $product);

        //\Log::info(1, [$response->getContent()]);

        $response->assertJsonStructure([
            'id','name','slug','price','created_at'
        ])
        ->assertJson([
            'name' => $product["name"],
            'slug' => $product["slug"],
            'price' => $product["price"],        

        ])
        ->assertStatus(201);

        $this->assertDatabaseHas('products',[
            'name' => $product["name"],
            'slug' => $product["slug"],
            'price' => $product["price"],        
        ]);
    }


    /**
     * @test
     */
    public function return_404_failure_for_get_if_product_not_found()
    {
        // Given non existent product (use id that'll never exist e.g. -1)
        $response = $this->actingAs($this->create('User',[],false),'api')->json('GET', "api/products/-1");
        
        // Then
        $response->assertStatus(404);
    }


    /**
     * @test
     */
    public function can_return_a_product()
    {
        // Given
        $product = $this->create('Product');

        // When we make a GET request to this endpoint, expect a Product back
        $response = $this->actingAs($this->create('User',[],false),'api')->json('GET', "api/products/$product->id");
        
        // Then
        $response->assertStatus(200)
        ->assertExactJson([
            'id' => $product->id,
            'name' => $product->name,
            'slug' => $product->slug,
            'price' => $product->price,
            'created_at' => $product->created_at,
        ]);
    }


    /**
     * @test
     */
    public function return_404_failure_for_update_if_product_not_found()
    {
        $response = $this->actingAs($this->create('User',[],false),'api')->json('PUT', "api/products/-1");
        $response->assertStatus(404);        
    }

    /**
     * @test
     */
    public function can_update_existing_product()
    {
        $product = $this->create('Product');

        $response = $this->actingAs($this->create('User',[],false),'api')->json('PUT', "api/products/$product->id", [
            'name' => $product->name.'_updated',
            'slug' => Str::slug($product->name.'_updated'),
            'price' => $product->price + 10,
        ]);
              
        $response->assertStatus(200)
        ->assertExactJson([
            'id' => $product->id,
            'name' => $product->name.'_updated',
            'slug' => Str::slug($product->name.'_updated'),
            'price' => $product->price + 10,
            'created_at' => $product->created_at
        ]); 
        
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => $product->name.'_updated',
            'slug' => Str::slug($product->name.'_updated'),
            'price' => $product->price + 10,
            'created_at' => $product->created_at, 
            'updated_at' => $product->updated_at,            
        ]);
    }    


    /**
     * @test
     */
    public function return_404_failure_for_delete_if_product_not_found()
    {
        $response = $this->actingAs($this->create('User',[],false),'api')->json('DELETE', "api/products/-1");
        $response->assertStatus(404);
    }

    /**
     * @test
     */
    public function can_delete_existing_product()
    {
        $product = $this->create('Product');
        $response = $this->actingAs($this->create('User',[],false),'api')->json('DELETE', "api/products/$product->id");
        $response->assertStatus(204); 
        $this->assertDatabaseMissing('products',['id' => $product->id]);

    }    


    private function data() {
        $faker = Factory::create();
        $product = [
            "name" => $name = $faker->company,
            "slug" => Str::slug($name),
            "price" => random_int(10, 100)
        ];
        return $product;
    }
}


