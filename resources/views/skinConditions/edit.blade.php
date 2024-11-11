@include('layouts.header')

@include('layouts.navbar')

@include('layouts.sidebar')

<section>
    <div class="container-fluid">
        <h1>Edit Skin Condition</h1>

        <form action="{{ route('skinConditions.update', $skinCondition->condition_id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="condition_name">Condition Name</label>
                <input type="text" name="condition_name" class="form-control" value="{{ $skinCondition->condition_name }}" required>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" class="form-control" rows="4" required>{{ $skinCondition->description }}</textarea>
            </div>

            <button type="submit" class="btn btn-success">Update</button>
        </form>
    </div>
</section>

@include('layouts.footer')
