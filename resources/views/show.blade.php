<h1>詳細画面</h1>
<p>
    <a href="{{ route('post.index')}}">一覧画面</a>
    <a href="{{ route('post.edit',$post->id)}}">編集</a>
<form action="{{ route('post.destroy',$post->id)}}" method="POST">
    @csrf
    @method('DELETE')
    <input type="submit" name="" value="削除">
</form>
<form action="{{route('download')}}" method="post">
    @csrf
    <input type="hidden" name="id" value="{{$post['id']}}">
    <button type="submit">ダウンロード</button>
</form>
<a href="{{ url('/') }}">Home</a>
</p>

<p>
    ユーザーID:{{ $post->user_id }}<br>
    投稿ID:{{ $post->id }}<br>
    タイトル：{{ $post->title }}<br>
    イメージ:<img src="{{ asset('/storage/'.$post->image) }}"><br>
    説明:{{ $post->description }}<br>
</p>