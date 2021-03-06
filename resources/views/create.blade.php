<h1>新規登録</h1>
<p><a href="{{ route('post.index')}}">一覧画面</a></p>

@if ($message = Session::get('failure'))
<p>{{ $message }}</p>
@endif

<form action="{{ route('post.index')}}" method="POST" enctype="multipart/form-data">
    @csrf
    <p>タイトル：<input type="text" name="title" value="{{old('title')}}"></p>
    <p>画像：<input type="file" name="image" value="{{old('image')}}"></p>
    <p>詳細：<input type="text" name="description" value="{{old('description')}}"></p>
    <input type="hidden" name="user_id" value="{{ $user_id }}" />
    <input type="submit" value="登録">
</form>

<a href="{{ url('/') }}">Home</a>