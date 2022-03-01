@extends('layouts.app', ['page' => 'Modifier produit', 'pageSlug' => 'proforma', 'section' => 'transactions'])

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
                                <a href="{{ route('sales.show', $devis->id) }}" class="btn btn-sm btn-primary">Retour à la liste</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{ route('devis.product.update', ['devis' => $devis, 'soldproduct' => $soldproduct]) }}" autocomplete="off">
                            @csrf
                            @method('put')

                            <div class="pl-lg-4">
                                <input type="hidden" name="devis_id" value="{{ $devis->id }}">
                                <div class="form-group{{ $errors->has('product_id') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-product">Produit</label>
                                    <select name="product_id" id="input-product" class="form-select form-control-alternative{{ $errors->has('product_id') ? ' is-invalid' : '' }}" required>
                                        @foreach ($products as $product)
                                            @if($product['id'] == old('product_id') or $product['id'] == $soldproduct->product_id )
                                                <option value="{{$product['id']}}" selected>[{{ $product->category->name }}] {{ $product->name }} - Prix de base: {{ $product->price }}$</option>
                                            @else
                                                <option value="{{$product['id']}}">[{{ $product->category->name }}] {{ $product->name }} - Prix de base: {{ $product->price }}$</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    @include('alerts.feedback', ['field' => 'product_id'])
                                </div>

                                <div class="form-group{{ $errors->has('product_id') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-price">Prix unitaire</label>
                                    <input type="number" name="price" id="input-price" step=".01" class="form-control form-control-alternative{{ $errors->has('product_id') ? ' is-invalid' : '' }}" value="{{ old('price', $soldproduct->price) }}" required>
                                    @include('alerts.feedback', ['field' => 'product_id'])
                                </div>

                                <div class="form-group{{ $errors->has('product_id') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-qty">Quantité</label>
                                    <input type="number" name="qty" id="input-qty" class="form-control form-control-alternative{{ $errors->has('product_id') ? ' is-invalid' : '' }}" value="{{ old('qty', $soldproduct->qty) }}" required>
                                    @include('alerts.feedback', ['field' => 'product_id'])
                                </div>

                                <div class="form-group{{ $errors->has('product_id') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-total">Montant total</label>
                                    <input type="text" name="total_amount" id="input-total" class="form-control form-control-alternative{{ $errors->has('product_id') ? ' is-invalid' : '' }}" value="{{ old('total_amount', $soldproduct->total_amount) }}" disabled>
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
    <script>
        let input_qty = document.getElementById('input-qty');
        let input_price = document.getElementById('input-price');
        let input_total = document.getElementById('input-total');
        input_qty.addEventListener('input', updateTotal);
        input_price.addEventListener('input', updateTotal);
        function updateTotal () {
            input_total.value = (parseInt(input_qty.value) * parseFloat(input_price.value))+" FCFA";
        }
    </script>
@endpush
