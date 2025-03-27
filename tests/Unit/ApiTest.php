<?php

namespace Tests\Unit;

use App\Models\Category;
use App\Models\Plant;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class ApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void

{
    parent::setUp();
    $this->artisan('db:seed', ['--class' => 'RoleSeeder']);
    config(['jwt.keys.private' => 'file://' . base_path('config/jwt/private.pem')]);
    config(['jwt.keys.public' => 'file://' . base_path('config/jwt/public.pem')]);
}


    /** @test */
    public function it_authenticates_admin_user()
    {
        $admin = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
        ]);
        $admin->roles()->attach(Role::where('name', 'admin')->first()->id);

        $response = $this->postJson('/api/auth/login', [
            'email' => 'admin@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure(['access_token', 'token_type', 'expires_in']);
        
        $token = $response->json('access_token');
        $decoded = JWTAuth::setToken($token)->getPayload();
        $this->assertContains('admin', $decoded['roles']);
    }

    /** @test */
    public function it_creates_category_as_admin()
    {
        $admin = $this->createAdminWithToken();

        $response = $this->postJson('/api/admin/categories', [
            'category_name' => 'Herbes',
        ], ['Authorization' => "Bearer {$admin['token']}"]);

        $response->assertStatus(201)
                 ->assertJson(['category_name' => 'Herbes']);

        $this->assertDatabaseHas('categories', ['category_name' => 'Herbes']);
    }

    /** @test */
    public function it_updates_category_as_admin()
    {
        $admin = $this->createAdminWithToken();
        $category = Category::create(['category_name' => 'Fleurs']);

        $response = $this->putJson("/api/admin/categories/{$category->id}", [
            'category_name' => 'Fleurs Sauvages',
        ], ['Authorization' => "Bearer {$admin['token']}"]);

        $response->assertStatus(200)
                 ->assertJson(['category_name' => 'Fleurs Sauvages']);

        $this->assertDatabaseHas('categories', ['category_name' => 'Fleurs Sauvages']);
    }

    /** @test */
    public function it_deletes_category_as_admin()
    {
        $admin = $this->createAdminWithToken();
        $category = Category::create(['category_name' => 'Fleurs']);

        $response = $this->deleteJson("/api/admin/categories/{$category->id}", [], [
            'Authorization' => "Bearer {$admin['token']}",
        ]);

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Category deleted']);

        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }

    /** @test */
    public function it_retrieves_plant_by_slug_with_spatie_sluggable()
    {
        $client = $this->createClientWithToken();
        $category = Category::create(['category_name' => 'Herbes']);
        $plant = Plant::create([
            'name' => 'Basilic Aromatique',
            'description' => 'Herbe savoureuse',
            'price' => 2.99,
            'images' => ['url'],
            'category_id' => $category->id,
        ]);

        $response = $this->getJson("/api/plants/{$plant->slug}", [
            'Authorization' => "Bearer {$client['token']}",
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'name' => 'Basilic Aromatique',
                     'slug' => 'basilic-aromatique',
                 ]);
    }

    // Helper methods
    private function createAdminWithToken(): array
    {
        $admin = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
        ]);
        $admin->roles()->attach(Role::where('name', 'admin')->first()->id);
        $token = JWTAuth::fromUser($admin);
        return ['user' => $admin, 'token' => $token];
    }

    private function createClientWithToken(): array
    {
        $client = User::factory()->create([
            'email' => 'client@example.com',
            'password' => Hash::make('password123'),
        ]);
        $client->roles()->attach(Role::where('name', 'client')->first()->id);
        $token = JWTAuth::fromUser($client);
        return ['user' => $client, 'token' => $token];
    }
}