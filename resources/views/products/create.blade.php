@include('layouts.header')

@include('layouts.navbar')

@include('layouts.sidebar')

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Create New Product</h1>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">

    <div class="card card-solid">
        <div class="card-body">
            <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="product_name">Product Name:</label>
                    <input type="text" name="product_name" id="product_name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="description">Description:</label>
                    <textarea name="description" id="description" class="form-control" rows="3" required></textarea>
                </div>
                <div class="form-group">
                    <label for="product_image">Product Image:</label>
                    <input type="file" name="product_image" id="product_image" class="form-control">
                </div>
                <div class="form-group">
                    <label for="price">Price:</label>
                    <input type="number" name="price" id="price" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="stok">Stock:</label>
                    <input type="number" name="stok" id="stok" class="form-control" required>
                </div>

                <!-- Skin Condition Dropdown -->
                <div class="form-group">
                    <label for="skin_condition_id">Skin Condition:</label>
                    <select name="condition_id" id="skin_condition_id" class="form-control">
                        <option value="">Select Skin Condition</option>
                        @foreach($skinConditions as $condition)
                            <option value="{{ $condition->condition_id }}">{{ $condition->condition_name }}</option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn btn-success">Create Product</button>
                <a href="{{ route('products.index') }}" class="btn btn-secondary">Back to List</a>
            </form>
        </div>
    </div>

</section>

@include('layouts.footer')
