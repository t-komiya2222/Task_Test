<h1>一覧画面</h1>

@if ($message = Session::get('success'))
<p>{{ $message }}</p>
@endif

@if ($message = Session::get('failure'))
<p>{{ $message }}</p>
@endif

<table border="1">
    @foreach ($posts as $post)
    <tr>
        <td><a href="{{ route('post.show',$post->id) }}">{{ $post->title }}</a></td>
        <th>{{ $post->image }}</th>
        <th>{{ $post->description }}</th>
    </tr>
    @endforeach
</table>

<a href="{{ url('/') }}">Home</a>