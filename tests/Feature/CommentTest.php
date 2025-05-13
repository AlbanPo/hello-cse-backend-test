<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Comment;
use App\Models\Profile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    private Admin $admin;
    private string $token;
    private Profile $profile;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = Admin::factory()->create([
            'password' => bcrypt('password'),
        ]);

        $this->profile = Profile::factory()->create();

        $response = $this->postJson('/api/login', [
            'email' => $this->admin->email,
            'password' => 'password',
        ]);

        $this->token = $response->json('token');
    }

    public function test_admin_can_add_comment_to_profile(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$this->token,
        ])->postJson("/api/profiles/{$this->profile->id}/comments", [
            'content' => 'Test comment',
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'content' => 'Test comment',
                'admin_id' => $this->admin->id,
                'profile_id' => $this->profile->id,
            ]);
    }

    public function test_admin_cannot_add_multiple_comments_to_same_profile(): void
    {
        Comment::factory()->create([
            'admin_id' => $this->admin->id,
            'profile_id' => $this->profile->id,
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$this->token,
        ])->postJson("/api/profiles/{$this->profile->id}/comments", [
            'content' => 'Test comment',
        ]);

        $response->assertStatus(403);
    }
}
