@include('layouts.header')

@include('layouts.navbar')

@include('layouts.sidebar')

<section class="content">
    <div class="container-fluid">
        <h1>Edit Treatment</h1>

        <form action="{{ route('treatments.update', $treatment->id_treatment) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="deskripsi_treatment">Description</label>
                <input type="text" name="deskripsi_treatment" id="deskripsi_treatment" class="form-control" value="{{ $treatment->deskripsi_treatment }}" required>
            </div>

            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('treatments.index') }}" class="btn btn-secondary">Back</a>
        </form>
    </div>
</section>

@include('layouts.footer')
