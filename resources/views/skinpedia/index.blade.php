@include('layouts.header')

@include('layouts.navbar')

@include('layouts.sidebar')
<!-- Main content -->
<section class="content">

    <!-- Default box -->
    <div class="card card-solid">
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <a href="{{ route('skinpedia.create') }}" class="btn btn-primary mb-3">Create New Skinpedia</a>

                    @if (session('status'))
                        <div class="alert alert-success">{{ session('status') }}</div>
                    @endif

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Judul</th>
                                <th>Deskripsi</th>
                                <th>Gambar</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($skinpedias as $skinpedia)
                                <tr>
                                    <td>{{ $skinpedia->id_skinpedia }}</td>
                                    <td>{{ $skinpedia->judul }}</td>
                                    <td>{{ $skinpedia->deskripsi }}</td>
                                    <td>
                                        @if ($skinpedia->gambar)
                                            <img src="{{ $skinpedia->gambar }}" alt="{{ $skinpedia->judul }}"
                                                style="max-width: 100px; height: auto;">
                                        @else
                                            <span>No Image</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('skinpedia.edit', $skinpedia->id_skinpedia) }}"
                                            class="btn btn-warning">Edit</a>
                                        <form action="{{ route('skinpedia.destroy', $skinpedia->id_skinpedia) }}"
                                            method="POST" style="display:inline-block;">
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
            </div>
        </div>
        <!-- /.card-body -->
    </div>
    <!-- /.card -->

</section>

@include('layouts.footer')
