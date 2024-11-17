@include('layouts.header')

@include('layouts.navbar')

@include('layouts.sidebar')

<section>
    <div class="container-fluid">
        <h1>Create New Skin Condition</h1>

        <form action="{{ route('skinConditions.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="condition_name">Condition Name</label>
                <input type="text" name="condition_name" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" class="form-control" rows="4" required></textarea>
            </div>

            <div class="form-group">
                <label for="id_treatment">Select Treatment</label>
                <select name="id_treatment" id="id_treatment" class="form-control" required>
                    <option value="" disabled selected>-- Select a Treatment --</option>
                    @foreach($treatments as $treatment)
                    <option value="{{ $treatment->id_treatment }}">{{ $treatment->id_treatment }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-success">Create</button>
        </form>
    </div>
</section>

@include('layouts.footer')
