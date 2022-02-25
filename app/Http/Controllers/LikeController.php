<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Like;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
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

    public function getlike()
    {
        $user = User::find(Auth::id());
        $likes = $user->likePostGet;
        return view('like', compact('likes'));
    }
}
