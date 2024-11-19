@include('layouts.header')

@include('layouts.navbar')

@include('layouts.sidebar')

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Edit Product</h1>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">

    <div class="card card-solid">
        <div class="card-body">
            <form action="{{ route('products.update', $product->product_id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="product_name">Product Name:</label>
                    <input type="text" name="product_name" id="product_name" class="form-control" value="{{ old('product_name', $product->product_name) }}" required>
                </div>
                <div class="form-group">
                    <label for="description">Description:</label>
                    <textarea name="description" id="description" class="form-control" rows="3" required>{{ old('description', $product->description) }}</textarea>
                </div>
                <div class="form-group">
                    <label for="product_image">Product Image:</label>
                    <input type="file" name="product_image" id="product_image" class="form-control">
                    @if ($product->product_image)
                        <img src="{{ asset('storage/' . $product->product_image) }}" alt="Product Image" class="img-thumbnail mt-2" width="100">
                    @endif
                </div>
                <div class="form-group">
                    <label for="price">Price:</label>
                    <input type="number" name="price" id="price" class="form-control" value="{{ old('price', $product->price) }}" required>
                </div>
                <div class="form-group">
                    <label for="stok">Stock:</label>
                    <input type="number" name="stok" id="stok" class="form-control" value="{{ old('stok', $product->stok) }}" required>
                </div>

                <!-- Skin Condition Dropdown -->
                <div class="form-group">
                    <label for="skin_condition_id">Skin Condition:</label>
                    <select name="condition_id" id="skin_condition_id" class="form-control" required>
                        <option value="">Select Skin Condition</option>
                        @foreach($skinConditions as $condition)
                            <option value="{{ $condition->condition_id }}" 
                                @if ($product->skinCondition && $product->skinCondition->condition_id == $condition->condition_id) selected @endif>
                                {{ $condition->condition_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn btn-success">Update Product</button>
                <a href="{{ route('products.index') }}" class="btn btn-secondary">Back to List</a>
            </form>
        </div>
    </div>

</section>

@include('layouts.footer')
