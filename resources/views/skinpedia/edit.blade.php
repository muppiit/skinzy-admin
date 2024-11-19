@include('layouts.header')

@include('layouts.navbar')

@include('layouts.sidebar')
<!-- Main content -->
<section class="content">

    <div class="card card-solid">
        <div class="card-body">
            <form action="{{ route('skinpedia.update', $skinpedia->id_skinpedia) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="form-group">
                    <label for="judul">Judul</label>
                    <input type="text" name="judul" id="judul" class="form-control" value="{{ old('judul', $skinpedia->judul) }}" required>
                    @error('judul')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="deskripsi">Deskripsi</label>
                    <textarea name="deskripsi" id="deskripsi" class="form-control" rows="4" required>{{ old('deskripsi', $skinpedia->deskripsi) }}</textarea>
                    @error('deskripsi')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="gambar">Gambar</label>
                    <input type="file" name="gambar" id="gambar" class="form-control" accept="image/*">
                    <small>Current Image: 
                        @if ($skinpedia->gambar)
                            <img src="{{ asset('storage/' . $skinpedia->gambar) }}" alt="{{ $skinpedia->judul }}" style="max-width: 100px; height: auto;">
                        @else
                            <span>No Image</span>
                        @endif
                    </small>
                    @error('gambar')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('skinpedia.index') }}" class="btn btn-secondary">Back to List</a>
            </form>
        </div>
    </div>

</section>

@include('layouts.footer')
