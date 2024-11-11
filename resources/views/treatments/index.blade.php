@include('layouts.header')

@include('layouts.navbar')

@include('layouts.sidebar')

<section class="content">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Treatment List</h1>
            </div>
            <div class="col-sm-6">
                <a href="{{ route('treatments.create') }}" class="btn btn-primary float-right">Add New Treatment</a>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($treatments as $treatment)
                    <tr>
                        <td>{{ $treatment->id_treatment }}</td>
                        <td>{{ $treatment->deskripsi_treatment }}</td>
                        <td>
                            <a href="{{ route('treatments.edit', $treatment->id_treatment) }}" class="btn btn-warning">Edit</a>
                            <form action="{{ route('treatments.destroy', $treatment->id_treatment) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</section>

@include('layouts.footer')
