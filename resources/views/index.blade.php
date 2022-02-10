<h1>一覧画面</h1>

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