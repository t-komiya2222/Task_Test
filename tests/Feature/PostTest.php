<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use App\Models\User;
use App\Models\Post;
use Illuminate\Http\UploadedFile;


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



        $response = $this->actingAs($user)->get(route('post.create'));
        $response->assertStatus(200);
        $response->assertViewIs('create');

        $requestdata = [
            'user_id' => Auth::id(),
            'title' => 'PHPunit',
            'image' => UploadedFile::fake()->image('icon.png'),
            'description' => 'PHPunit'
        ];

        $response = $this->post('post/', $requestdata);

        $response->assertSessionHasNoErrors();
        $response->assertStatus(302);

        $this->assertDatabaseHas('posts', [
            'user_id' => Auth::id(),
            'title' => 'PHPunit',
            'image' => 'icon.png',
            'description' => 'PHPunit',
        ]);
    }

    //レコード削除
    public function test_delete()
    {
        //setupで共通処理化
        $user = factory(User::class)->create([
            'password' => bcrypt('testtest')
        ]);

        $response = $this->post('/login', [
            'email'    => $user->email,
            'password' => 'testtest'
        ]);



        $response = $this->actingAs($user)->get(route('post.create'));
        $response->assertStatus(200);
        $response->assertViewIs('create');

        $requestdata = [
            'user_id' => Auth::id(),
            'title' => 'PHPunit',
            'image' => UploadedFile::fake()->image('icon.png'),
            'description' => 'PHPunit'
        ];

        $response = $this->post('post/', $requestdata);

        $response->assertSessionHasNoErrors();
        $response->assertStatus(302);

        $this->assertDatabaseHas('posts', [
            'user_id' => Auth::id(),
            'title' => 'PHPunit',
            'image' => 'icon.png',
            'description' => 'PHPunit',
        ]);

        $response = $this->get('/post');
        $response->assertStatus(200);
        $response->assertViewIs('index');

        $postdata = Post::where('user_id', Auth::id())->first();
        $response = $this->get('post/' . $postdata->id);

        $response->assertStatus(200);
        $response->assertViewIs('show');
        $response->assertSee('削除', '編集');

        $response = $this->delete('post/' . $postdata->id);
        $this->assertDatabaseMissing('posts', [
            'user_id' => Auth::id(),
            'title' => 'PHPunit',
            'image' => 'icon.png',
            'description' => 'PHPunit',
        ]);
    }


    //レコード更新
    public function test_update()
    {
        //setupで共通処理化
        $user = factory(User::class)->create([
            'password' => bcrypt('testtest')
        ]);

        $response = $this->post('/login', [
            'email'    => $user->email,
            'password' => 'testtest'
        ]);



        $response = $this->actingAs($user)->get(route('post.create'));
        $response->assertStatus(200);
        $response->assertViewIs('create');

        $requestdata = [
            'user_id' => Auth::id(),
            'title' => 'PHPunit',
            'image' => UploadedFile::fake()->image('icon.png'),
            'description' => 'PHPunit'
        ];

        $response = $this->post('post/', $requestdata);

        $response->assertSessionHasNoErrors();
        $response->assertStatus(302);

        $this->assertDatabaseHas('posts', [
            'user_id' => Auth::id(),
            'title' => 'PHPunit',
            'image' => 'icon.png',
            'description' => 'PHPunit',
        ]);

        $response = $this->get('/post');
        $response->assertStatus(200);
        $response->assertViewIs('index');

        $postdata = Post::where('user_id', Auth::id())->first();
        $response = $this->get('post/' . $postdata->id);

        $response->assertStatus(200);
        $response->assertViewIs('show');
        $response->assertSee('削除', '編集');

        $response = $this->get('post/' . $postdata->id . '/edit');
        $response->assertStatus(200);
        $response->assertViewIs('edit');

        $requestdata = [
            'user_id' => Auth::id(),
            'title' => 'PHPunitUpdate',
            'image' => UploadedFile::fake()->image('icon.png'),
            'description' => 'PHPunit'
        ];

        $response = $this->put('post/' . $postdata->id, $requestdata);

        $response->assertSessionHasNoErrors();
        $response->assertStatus(302);

        $this->assertDatabaseHas('posts', [
            'user_id' => Auth::id(),
            'title' => 'PHPunitUpdate',
            'image' => 'icon.png',
            'description' => 'PHPunit',
        ]);
    }
}
