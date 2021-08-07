<?php

namespace Tests\Unit;

use App\Helpers\JwtAuth;
use App\Models\Category;
use App\Models\User;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    /** @test */
    public function can_get_all_categories()
    {
        $user = User::factory()->create();

        $jwt = new JwtAuth();
        $token = $jwt->singup($user);

        $response = $this->getJson('/api/categorias', [
            'Authorization' => $token,
        ]);

        $response->assertStatus(201);
    }

    /** @test */
    public function can_register_a_category()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();

        $jwt = new JwtAuth();
        $token = $jwt->singup($user);

        $response = $this->postJson('/api/categorias', [
            'name' => $category->name,
            'description' => $category->description,
        ], [
            'Authorization' => $token,
        ]);

        $response
            ->assertStatus(201)
            ->assertJson([
                'message' => 'La categoría se ha creado correctamente',
            ]);
    }

    /** @test */
    public function can_find_a_category()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();

        $jwt = new JwtAuth();
        $token = $jwt->singup($user);

        $response = $this->getJson('/api/categorias/' . $category->getRouteKey(), [
            'Authorization' => $token,
        ]);

        $response->assertStatus(201);
    }

    /** @test */
    public function can_update_a_category()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();

        $jwt = new JwtAuth();
        $token = $jwt->singup($user);

        $response = $this->putJson('/api/categorias/' . $category->getRouteKey(), [
            'name' => $category->name,
            'description' => $category->description,
        ], [
            'Authorization' => $token,
        ]);

        $response
            ->assertStatus(201)
            ->assertJson([
                'message' => 'La categoría se ha editado correctamente',
            ]);
    }

    /** @test  */
    public function can_delete_a_category()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();

        $jwt = new JwtAuth();
        $token = $jwt->singup($user);

        $response = $this->deleteJson('/api/categorias/' . $category->getRouteKey(), [], [
            'Authorization' => $token,
        ]);

        $response
            ->assertStatus(201)
            ->assertJson([
                'message' => 'Se ha eliminado la categoría correctamente',
            ]);
    }
}
