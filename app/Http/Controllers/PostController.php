<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class PostController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::all();
        return view('index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user_id = Auth::id();
        return view('create', compact('user_id'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            //タイトルと画像が空白じゃなかったら
            if (isset($request->title) && isset($request->image)) {
                $file_name = $request->file('image')->getClientOriginalName();
                Post::create([
                    'user_id' => $request->user_id,
                    'title' => $request->title,
                    'description' => $request->description,
                    //第一引数は追加したいパス 第二引数は保存したい場所?　第三引数は指定したいファイル名
                    'image' => Storage::putFileAs('', $request->file('image'), $file_name)
                ]);
                return redirect()->route('post.index')->with('success', '新規登録完了しました');
            } else {
                throw new Exception('新規登録:タイトルまたは画像が未入力です');
            }
        } catch (Exception $ex) {
            //例外発生時の処理（Exceptionのエラー分を取得して表示してくれる）
            $user_id = Auth::id();
            Log::error($ex->getMessage());
            return back()->with('failure', '必須項目が空白です');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $post = Post::find($id);
        return view('show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $post = Post::find($id);
        return view('edit', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            if (isset($request->title) && isset($request->image)) {
                $update = [
                    'title' => $request->title,
                    'image' => $request->image,
                    'description' => $request->description,
                ];
                Post::where('id', $id)->update($update);
                return back()->with('success', '編集完了');
            } else {
                throw new Exception('更新:タイトルまたは画像が未入力です');
            }
        } catch (Exception $ex) {
            $post = Post::find($id);
            Log::error($ex->getMessage());
            return back()->with('failure', '必須項目が空白です');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $post_id = DB::table('posts')->where('id', $id)->first();
            if (isset($post_id)) {
                Post::where('id', $id)->delete();
                return redirect()->route('post.index')->with('success', '削除完了');
            } else {
                throw new Exception('対象のレコードが存在しません');
            }
        } catch (Exception $ex) {
            Log::error($ex->getMessage());
            return redirect()->route('post.index')->with('failure', '対象のレコードが存在しません');
        }
    }

    /**
     * Download
     */
    public function download(Request $request)
    {
        $download_image = Post::find($request['id']);
        return response()->download(storage_path('/app/public/' . $download_image['image']));
    }
}
