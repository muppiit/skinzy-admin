@include('layouts.header')

@include('layouts.navbar')

@include('layouts.sidebar')

<!-- Main content -->
<section class="content">

    <div class="card card-solid">
        <div class="card-body">
            <form action="{{ route('skinpedia.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="judul">Judul</label>
                    <input type="text" name="judul" id="judul" class="form-control" value="{{ old('judul') }}" required>
                    @error('judul')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="deskripsi">Deskripsi</label>
                    <textarea name="deskripsi" id="deskripsi" class="form-control" rows="4" required>{{ old('deskripsi') }}</textarea>
                    @error('deskripsi')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="gambar">Gambar</label>
                    <input type="file" name="gambar" id="gambar" class="form-control" accept="image/*">
                    @error('gambar')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Create</button>
                <a href="{{ route('skinpedia.index') }}" class="btn btn-secondary">Back to List</a>
            </form>
        </div>
    </div>

</section>

@include('layouts.footer')
