<?php

namespace Tests\Feature;

use App\Enums\ProfileStatus;
use App\Models\Admin;
use App\Models\Profile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    private Admin $admin;
    private string $token;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = Admin::factory()->create([
            'password' => bcrypt('password'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => $this->admin->email,
            'password' => 'password',
        ]);

        $this->token = $response->json('token');
    }

    public function test_can_get_active_profiles(): void
    {
        Profile::factory()->create(['status' => ProfileStatus::ACTIVE->value]);
        Profile::factory()->create(['status' => ProfileStatus::INACTIVE->value]);

        $response = $this->getJson('/api/profiles');

        $response->assertStatus(200)
            ->assertJsonCount(1)
            ->assertJsonStructure([[
                'id', 'first_name', 'last_name', 'image_path', 'created_at'
            ]]);
    }

    public function test_admin_can_create_profile(): void
    {
        Storage::fake('public');

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$this->token,
        ])->postJson('/api/profiles', [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'image' => UploadedFile::fake()->image('avatar.jpg'),
            'status' => ProfileStatus::ACTIVE->value,
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'first_name' => 'John',
                'last_name' => 'Doe',
                'status' => ProfileStatus::ACTIVE->value,
            ]);

        Storage::disk('public')->assertExists($response->json('image_path'));
    }

    public function test_admin_can_update_profile(): void
    {
        Storage::fake('public');

        $profile = Profile::factory()->create(['admin_id' => $this->admin->id]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$this->token,
        ])->putJson("/api/profiles/{$profile->id}", [
            'first_name' => 'Updated',
            'status' => ProfileStatus::PENDING->value,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'first_name' => 'Updated',
                'status' => ProfileStatus::PENDING->value,
            ]);
    }

    public function test_admin_can_delete_profile(): void
    {
        Storage::fake('public');

        $profile = Profile::factory()->create(['admin_id' => $this->admin->id]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$this->token,
        ])->deleteJson("/api/profiles/{$profile->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('profiles', ['id' => $profile->id]);
    }
}
