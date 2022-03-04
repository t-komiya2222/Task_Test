<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Post;
use App\Models\Like;
use Illuminate\Support\Facades\Auth;

class LikeTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_like()
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

        $postdata = Post::where('id', 3)->first();
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

        $postdata = Post::where('id', 3)->first();
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

        dump($likedataArray);

        $response = $this->post('dislike', $likedataArray);
        $this->assertDatabaseMissing('likes', [
            'id' => $likedata->id,
            'post_id' => $postdata->id,
            'user_id' => Auth::id(),
        ]);
    }
}
