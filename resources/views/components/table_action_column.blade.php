<td>
    <a href="{{ route('clients.show', $client->id) }}"> <i class="fa fa-info text-eye"></i>view</a>
    <a href="{{ route('clients.edit', $client->id) }}" class="btn  btn-sm"><i class ="fa fa-edit text-warning"></i></a>
    <form action="{{ route('clients.destroy', $client->id) }}" method="POST" style="display: inline-block;">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-sm"><i class="fa fa-trash text-danger"></i></button>
    </form>
</td>
