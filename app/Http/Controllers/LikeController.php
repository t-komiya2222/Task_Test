<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Like;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    //
    public function addlike(Request $request)
    {
        Like::create([
            'post_id' => $request->id,
            'user_id' => Auth::id(),
        ]);
        return redirect()->back();
    }

    public function dislike(Request $request)
    {
        Like::where('id', $request->id)->delete();
        return redirect()->back();
    }

    public function alllike()
    {
        $likes = Like::where('user_id', Auth::id())->get();
        return view('like', compact('likes'));
    }
}
