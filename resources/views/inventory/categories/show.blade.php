@extends('layouts.app', ['page' => 'Category Information', 'pageSlug' => 'categories', 'section' => 'inventory'])

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Informations Catégories</h4>
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>produits</th>
                            <th>Stock</th>
                            <th>Défectueux</th>
                            <th>Prix moyen</th>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ $category->id }}</td>
                                <td>{{ $category->name }}</td>
                                <td>{{ $category->products->count() }}</td>
                                <td>{{ $category->products->sum('stock') }}</td>
                                <td>{{ $category->products->sum('stock_defective') }}</td>
                                <td>${{ round($category->products->avg('price'), 2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">produits: {{ $products->count() }}</h4>
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Stock</th>
                            <th>Défectueux</th>
                            <th>Prix de base</th>
                            <th>Prix moyen</th>
                            <th>Total ventes</th>
                            <th>Revenu produit</th>
                            <th></th>
                        </thead>
                        <tbody>
                            @foreach ($products as $product)
                                <tr>
                                    <td><a href="{{ route('products.show', $product) }}">{{ $product->id }}</a></td>
                                    <td><a href="{{ route('products.show', $product) }}">{{ $product->name }}</a></td>
                                    <td>{{ $product->stock }}</td>
                                    <td>{{ $product->stock_defective }}</td>
                                    <td>{{ format_money($product->price) }}</td>
                                    <td>{{ format_money($product->solds->avg('price')) }}</td>
                                    <td>{{ $product->solds->sum('qty') }}</td>
                                    <td>{{ format_money($product->solds->sum('total_amount')) }}</td>
                                    <td class="td-actions text-right">
                                        <a href="{{ route('products.show', $product) }}" class="btn btn-link" data-toggle="tooltip" data-placement="bottom" title="More Details">
                                            <i class="tim-icons icon-zoom-split"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer py-4">
                    <nav class="d-flex justify-content-end">
                        {{ $products->links() }}
                    </nav>
                </div>
            </div>
        </div>
    </div>
@endsection