@extends('layouts.app', ['page' => 'Modifier produit', 'pageSlug' => 'receipts', 'section' => 'inventory'])

@section('content')
        <div class="row">
            <div class="col-xl-12 order-xl-1">
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">Modifier produit</h3>
                            </div>
                            <div class="col-4 text-right">
                                <a href="{{ route('receipts.show', $receipt) }}" class="btn btn-sm btn-primary">Retour à la liste</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{ route('receipts.product.update', ['receipt' => $receipt, 'receivedproduct' => $receivedproduct]) }}" autocomplete="off">
                            @csrf
                            @method('put')

                            <div class="pl-lg-4">
                                <input type="hidden" name="receipt_id" value="{{ $receipt->id }}">
                                <div class="form-group{{ $errors->has('product_id') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-product">Produit</label>
                                    <select name="product_id" id="input-product" class="form-select form-control-alternative{{ $errors->has('product_id') ? ' is-invalid' : '' }}" required>
                                        @foreach ($products as $product)
                                            @if($product['id'] == old('product_id') or $product['id'] == $receivedproduct->product_id )
                                                <option value="{{$product['id']}}" selected>[{{ $product->category->name }}] {{ $product->name }} - Prix de base: {{ $product->price }}$</option>
                                            @else
                                                <option value="{{$product['id']}}">[{{ $product->category->name }}] {{ $product->name }} - Prix de base: {{ $product->price }}$</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    @include('alerts.feedback', ['field' => 'product_id'])
                                </div>

                                <div class="form-group{{ $errors->has('product_id') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-stock">Stock</label>
                                    <input type="number" name="stock" id="input-stock" class="form-control form-control-alternative{{ $errors->has('product_id') ? ' is-invalid' : '' }}" value="{{ old('stock', $receivedproduct->stock) }}" required>
                                    @include('alerts.feedback', ['field' => 'product_id'])
                                </div>

                                <div class="form-group{{ $errors->has('product_id') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-price">Prix unitaire</label>
                                    <input type="number" name="price" id="input-price" class="form-control form-control-alternative{{ $errors->has('product_id') ? ' is-invalid' : '' }}" value="{{ old('price', $receivedproduct->price) }}" required>
                                    @include('alerts.feedback', ['field' => 'product_id'])
                                </div>

                                <div class="form-group{{ $errors->has('product_id') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-stock_defective">Déféctueux</label>
                                    <input type="number" name="stock_defective" id="input-stock_defective" class="form-control form-control-alternative{{ $errors->has('product_id') ? ' is-invalid' : '' }}" value="{{ old('stock_defective', $receivedproduct->stock_defective) }}" required>
                                    @include('alerts.feedback', ['field' => 'product_id'])
                                </div>



                                <div class="text-center">
                                    <button type="submit" class="btn btn-success mt-4">Continuer</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
@endsection

@push('js')
    <script>
        new SlimSelect({
            select: '.form-select'
        });
    </script>
@endpush
