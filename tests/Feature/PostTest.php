<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use App\Models\User;
use App\Models\Post;


class PostTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    protected function setUp(): void
    {
        //共通ユーザー作成処理
        parent::setUp();
    }

    //全件取得
    public function test_index()
    {
        //setupで共通処理化
        $user = factory(User::class)->create([
            'password' => bcrypt('testtest')
        ]);

        $this->post('/login', [
            'email'    => $user->email,
            'password' => 'testtest'
        ]);



        $response = $this->actingAs($user)->get('/post');
        $response->assertStatus(200);

        $response->assertViewIs('index');

        $posts = Post::all();
        $this->assertSame(2, count($posts));

        $response->assertSee('3');
        $response->assertSee('アイコン');
        $response->assertSee('icon.png');
        $response->assertSee('アイコンアイコン');

        $response->assertSee('4');
        $response->assertSee('アイコン2');
        $response->assertSee('icon 2.png');
        $response->assertSee('アイコン2');
    }

    //詳細表示
    public function test_detailView()
    {
        //setupで共通処理化
        $user = factory(User::class)->create([
            'password' => bcrypt('testtest')
        ]);

        $this->post('/login', [
            'email'    => $user->email,
            'password' => 'testtest'
        ]);

        $response = $this->actingAs($user)->get('/post');
        $response->assertStatus(200);
        $response->assertViewIs('index');

        $postdata = Post::where('id', 3)->first();

        $response = $this->get('post/' . $postdata->id);
        $response->assertStatus(200);
        $response->assertViewIs('show');

        $response->assertSee('3');
        $response->assertSee('アイコン');
        $response->assertSee('icon.png');
        $response->assertSee('アイコンアイコン');
    }

    /*
    //新規登録
    public function test_create()
    {
        //setupで共通処理化
        $user = factory(User::class)->create([
            'password' => bcrypt('testtest')
        ]);

        $response = $this->post('/login', [
            'email'    => $user->email,
            'password' => 'testtest'
        ]);



        $response = $this->actingAs(user)->get(route('post.create'));
        $response->assertStatus(200);
        $response->assertViewIs('create');

        $requestdata = [
            'user_id' => Auth::id(),
            'title' => 'PHPunit',
            'image' => 'icon.png',
            'description' => 'PHPunit'
        ];

        $url = route('post.store');
        $response = $this->post($url, $requestdata);

        $response->assertSessionHasNoErrors();
        $response->assertStatus(302);

        $this->assertDatabaseHas('items', ['title' => 'PHPunit']);
        $response = $this->get('/index');
        $response->assertStatus(200);
    }
    */
}
