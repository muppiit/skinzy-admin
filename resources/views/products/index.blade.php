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
                    <a href="{{ route('products.create') }}" class="btn btn-primary mb-3">Create New Product</a>

                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Product Name</th>
                                <th>Description</th>
                                <th>Price</th>
                                <th>Stock</th>
                                <th>Skin Condition</th>
                                <th>Image</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $product)
                                <tr>
                                    <td>{{ $product->product_id }}</td>
                                    <td>{{ $product->product_name }}</td>
                                    <td>{{ $product->description }}</td>
                                    <td>${{ $product->price }}</td>
                                    <td>{{ $product->stok }}</td>
                                    <td>
                                        @if ($product->skinCondition)
                                            {{ $product->skinCondition->condition_name }}
                                        @else
                                            Not Set
                                        @endif
                                    </td>
                                    <td>
                                        <!-- Display product image -->
                                        <img src="{{ $product->product_image }}" alt="{{ $product->product_name }}" style="max-width: 100px; height: auto;">
                                    </td>
                                    <td>
                                        <a href="{{ route('products.edit', $product->product_id) }}" class="btn btn-warning">Edit</a>
                                        <form action="{{ route('products.destroy', $product->product_id) }}" method="POST" style="display:inline-block;">
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
