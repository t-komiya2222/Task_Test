<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class HomeTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    //エントリーポイントにアクセスした時に遷移できるか
    public function test_rootTransition()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }
}
