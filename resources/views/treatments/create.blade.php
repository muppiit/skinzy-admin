@include('layouts.header')

@include('layouts.navbar')

@include('layouts.sidebar')

<section class="content">
    <div class="container-fluid">
        <h1>Add New Treatment</h1>

        <form action="{{ route('treatments.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="deskripsi_treatment">Description</label>
                <input type="text" name="deskripsi_treatment" id="deskripsi_treatment" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary">Save</button>
            <a href="{{ route('treatments.index') }}" class="btn btn-secondary">Back</a>
        </form>
    </div>
</section>

@include('layouts.footer')
