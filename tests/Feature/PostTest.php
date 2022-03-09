<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use App\Models\User;
use App\Models\Post;
use Illuminate\Http\UploadedFile;


class PostTest extends TestCase
{
    use DatabaseTransactions;

    private $user;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    protected function setUp(): void
    {
        //共通ユーザー作成処理
        parent::setUp();
        $this->user = factory(User::class)->create([
            'password' => bcrypt('testtest')
        ]);

        $this->post('/login', [
            'email'    => $this->user->email,
            'password' => 'testtest'
        ]);
    }

    //全件取得
    public function test_index()
    {
        $response = $this->actingAs($this->user)->get('/post');
        $response->assertStatus(200);

        $response->assertViewIs('index');

        factory(Post::class)->create([
            'title' => '全件取得1'
        ]);
        factory(Post::class)->create([
            'title' => '全件取得2'
        ]);

        $posts = Post::all();
        $this->assertSame(2, count($posts));
    }

    //詳細表示
    public function test_detailView()
    {
        $response = $this->actingAs($this->user)->get('/post');
        $response->assertStatus(200);
        $response->assertViewIs('index');

        factory(Post::class)->create([
            'user_id' => Auth::id(),
            'title' => 'アイコン',
            'image' => 'hogehoge',
            'description' => 'アイコンアイコン'
        ]);

        $postdata = Post::where('title', 'アイコン')->first();

        $response = $this->get('post/' . $postdata->id);
        $response->assertStatus(200);
        $response->assertViewIs('show');

        $response->assertSee($postdata->id);
        $response->assertSee('アイコン');
        $response->assertSee('hogehoge');
        $response->assertSee('アイコンアイコン');
    }

    //新規登録
    public function test_create()
    {
        $response = $this->actingAs($this->user)->get(route('post.create'));
        $response->assertStatus(200);
        $response->assertViewIs('create');

        factory(Post::class)->create([
            'user_id' => Auth::id(),
            'title' => 'PHPunit',
            'image' => 'icon.png',
            'description' => 'PHPunit'
        ]);

        $test = $this->assertDatabaseHas('posts', [
            'user_id' => Auth::id(),
            'title' => 'PHPunit',
            'image' => 'icon.png',
            'description' => 'PHPunit',
        ]);
    }

    //レコード削除
    public function test_delete()
    {
        $response = $this->actingAs($this->user)->get(route('post.create'));
        $response->assertStatus(200);
        $response->assertViewIs('create');

        factory(Post::class)->create([
            'user_id' => Auth::id(),
            'title' => 'PHPunit',
            'image' => 'icon.png',
            'description' => 'PHPunit'
        ]);

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
        $response = $this->actingAs($this->user)->get(route('post.create'));
        $response->assertStatus(200);
        $response->assertViewIs('create');

        factory(Post::class)->create([
            'user_id' => Auth::id(),
            'title' => 'PHPunit',
            'image' => 'icon.png',
            'description' => 'PHPunit'
        ]);

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

    //ダウンロード
    public function test_download()
    {
        $response = $this->actingAs($this->user)->get('/post');
        $response->assertStatus(200);
        $response->assertViewIs('index');

        factory(Post::class)->create([
            'user_id' => Auth::id(),
            'title' => 'PHPunit',
            'image' => 'icon.png',
            'description' => 'PHPunit'
        ]);

        $postdata = Post::where('user_id', Auth::id())->first();
        $postdataArray = json_decode(json_encode($postdata), true);

        $response = $this->get('post/' . $postdata->id);
        $response->assertStatus(200);
        $response->assertViewIs('show');
        $response->assertsee('ダウンロード');

        $response = $this->post('download', $postdataArray);
        $response->assertStatus(200);
    }
}
