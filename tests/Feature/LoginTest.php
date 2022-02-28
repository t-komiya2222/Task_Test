<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use App\Models\User;

class LoginTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    //ログイン遷移確認
    public function test_loginScreenTransition()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }

    //ログイン確認(正常)
    public function test_canLogin()
    {
        //ユーザーを作成
        $user = factory(User::class)->create([
            'password' => bcrypt('testtest')
        ]);

        //ログインしていないことを確認
        $this->assertFalse(Auth::Check());

        //ログイン処理
        $response = $this->post('/login', [
            'email'    => $user->email,
            'password' => 'testtest'
        ]);

        //ログイン出来ているか確認
        $this->assertTrue(Auth::check());
        $response->assertRedirect('home');
    }

    //ログイン確認(異常)
    public function test_canNotLogin()
    {
        //ユーザーを作成
        $user = factory(User::class)->create([
            'password' => bcrypt('testtest')
        ]);

        //ログインしていないことを確認
        $this->assertFalse(Auth::Check());

        //ログイン処理
        $response = $this->post('/login', [
            'email'    => $user->email,
            'password' => 'hogehoge'
        ]);

        //ログイン失敗しているか確認
        $this->assertFalse(Auth::check());
    }
}
