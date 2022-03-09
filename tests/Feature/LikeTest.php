<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Post;
use App\Models\Like;
use Illuminate\Support\Facades\Auth;

class LikeTest extends TestCase
{
    use DatabaseTransactions;
    /**
     * A basic feature test example.
     *
     * @return void
     */

    //いいね投稿
    public function test_addlike()
    {
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
        $response->assertsee('「いいね」する');

        $response = $this->post('addlike', $postdataArray);
        $this->assertDatabaseHas('likes', [
            'user_id' => Auth::id(),
            'post_id' => $postdata->id,
        ]);
    }

    //いいね削除
    public function test_dislike()
    {
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
        $response->assertsee('「いいね」する');

        $response = $this->post('addlike', $postdataArray);
        $this->assertDatabaseHas('likes', [
            'user_id' => Auth::id(),
            'post_id' => $postdata->id,
        ]);

        $response = $this->get('post/' . $postdata->id);
        $response->assertStatus(200);
        $response->assertViewIs('show');
        $response->assertsee('「いいね」を外す');

        $likedata = Like::where([
            ['user_id', Auth::id()],
            ['post_id', $postdata->id],
        ])->first();
        $likedataArray = json_decode(json_encode($likedata), true);

        $response = $this->post('dislike', $likedataArray);
        $this->assertDatabaseMissing('likes', [
            'id' => $likedata->id,
            'post_id' => $postdata->id,
            'user_id' => Auth::id(),
        ]);
    }

    //いいね一覧取得
    public function test_likeall()
    {
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
        $response->assertsee('「いいね」する');

        $response = $this->post('addlike', $postdataArray);
        $this->assertDatabaseHas('likes', [
            'user_id' => Auth::id(),
            'post_id' => $postdata->id,
        ]);

        $response = $this->get('getlike');
        $response->assertStatus(200);
        $response->assertViewIs('like');

        $likes = Like::all();
        $this->assertSame(1, count($likes));
    }
}
