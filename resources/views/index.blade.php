<h1>一覧画面</h1>

<table border="1">
    @foreach ($posts as $post)
        <tr>
            <td>{{ $post->title }}</td>
            <td>{{ $post->image }}</td>
            <td>{{ $post->description }}</td>
        </tr>
    @endforeach
</table>

<a href="{{ url('/') }}">Home</a>