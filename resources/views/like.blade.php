<h1>いいね一覧</h1>

<table border="1">
    @foreach ($likes as $like)
    <tr>
        <td><a href="{{ route('post.show',$like->id) }}">{{ $like->title }}</a></td>
        <th>{{ $like->user_id }}</th>
        <th>{{ $like->image }}</th>
        <th>{{ $like->description }}</th>
    </tr>
    @endforeach
</table>

<a href="{{ url('/') }}">Home</a>