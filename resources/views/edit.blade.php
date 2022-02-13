<h1>編集画面</h1>
<a href="{{ route('post.show', $post->id)}}">詳細画面</a>

@if ($message = Session::get('success'))
<p>{{ $message }}</p>
@endif

<form action="{{ route('post.update',$post->id)}}" method="POST">
    @csrf
    @method('PUT')
    <p>タイトル：<input type="text" name="title" value="{{ $post->title }}"></p>
    <p>画像：<input type="text" name="image" value="{{ $post->image }}"></p>
    <p>詳細：<input type="text" name="description" value="{{ $post->description }}"></p>
    <input type="submit" value="編集する">
</form>
