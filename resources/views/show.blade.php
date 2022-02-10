<h1>詳細画面</h1>
<p>
    <a href="{{ route('post.index')}}">一覧画面</a>
    <a href="{{ url('/') }}">Home</a>
</p>

ユーザーID:{{ $post->id }}<br>
タイトル：{{ $post->title }}<br>
イメージ:{{ $post->image }}<br>
説明:{{ $post->description }}<br>
