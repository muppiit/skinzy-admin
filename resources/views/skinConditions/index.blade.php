@include('layouts.header')

@include('layouts.navbar')

@include('layouts.sidebar')

<section>
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Skin Conditions</h1>
            </div>
            <div class="col-sm-6">
                <a href="{{ route('skinConditions.create') }}" class="btn btn-primary float-sm-right">Create New Condition</a>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Condition Name</th>
                    <th>Description</th>
                    <th>Treatment</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($skinConditions as $condition)
                    <tr>
                        <td>{{ $condition->condition_name }}</td>
                        <td>{{ $condition->description }}</td>
                        <td>{{ $condition->treatment ? $condition->treatment->id_treatment : 'No Treatment Assigned' }}</td>
                        <td>
                            <a href="{{ route('skinConditions.edit', $condition->condition_id) }}" class="btn btn-warning">Edit</a>
                            <form action="{{ route('skinConditions.destroy', $condition->condition_id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this condition?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</section>

@include('layouts.footer')
