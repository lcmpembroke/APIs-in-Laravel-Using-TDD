<?php

namespace Tests\Feature\Http\Controllers\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;

class AuthControllerTest extends TestCase
{
    //use RefreshDatabase;

    /**
     * @test
     */
    public function can_authenticate()
    {
        $this->withoutExceptionHandling();
        $user = $this->create('User',[],false);
        var_dump($user->email . ' ' . $user->password);

        // $response = $this->json('POST','/auth/token', [
        //     'email' => $this->create('User', [], false)->email,
        //     'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'
        // ]);

        $response = $this->actingAs($user)->json('POST','/auth/token', [
            'email' => $user->email,
            'password' => $user->password
            //'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'
        ]);


        $response->assertStatus(200)
          ->assertJsonStructure(['token']);
    }
}