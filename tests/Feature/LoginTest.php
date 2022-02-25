<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    //ログイン機能確認
    public function loginScreenTransition()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }
}
