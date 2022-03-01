@extends('layouts.app', ['page' => 'Liste des produits', 'pageSlug' => 'products', 'section' => 'inventory'])

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card ">
                <div class="card-header">
                    <div class="row">
                        <div class="col-8">
                            <h4 class="card-title">Produits</h4>
                        </div>
                        <div class="col-4 text-right">
                            <a href="{{ route('products.create') }}" class="btn btn-sm btn-primary">Nouveau produit</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @include('alerts.success')

                    <div class="">
                        <table class="table tablesorter " id="">
                            <thead class=" text-primary">
                                <th scope="col">Categorie</th>
                                <th scope="col">Référence</th>
                                <th scope="col">Produit</th>
                                <th scope="col">Unité</th>
                                <th scope="col">Prix de base</th>
                                <th scope="col">Stock</th>
                                {{-- <th scope="col">Défectueux</th> --}}
                                <th scope="col">Total vendu</th>
                                <th scope="col"></th>
                            </thead>
                            <tbody>
                                @foreach ($products as $product)
                                    <tr>
                                        <td><a href="{{ route('categories.show', $product->category) }}">{{ $product->category->name }}</a></td>
                                        <td>{{ $product->reference }}</td>
                                        <td>{{ $product->name }}</td>
                                        <td>{{ $product->unite->name }}</td>
                                        <td>{{ format_money($product->price) }}</td>
                                        <td>{{ $product->stock }}</td>
                                        {{-- <td>{{ $product->stock_defective }}</td> --}}
                                        <td>{{ $product->solds->sum('qty') }}</td>
                                        <td class="td-actions text-right">
                                            <a href="{{ route('products.show', $product) }}" class="btn btn-link" data-toggle="tooltip" data-placement="bottom" title="Plus de détails">
                                                <i class="tim-icons icon-zoom-split"></i>
                                            </a>
                                            <a href="{{ route('products.edit', $product) }}" class="btn btn-link" data-toggle="tooltip" data-placement="bottom" title="Modifier Produit">
                                                <i class="tim-icons icon-pencil"></i>
                                            </a>
                                            {{-- <form action="{{ route('products.destroy', $product) }}" method="post" class="d-inline">
                                                @csrf
                                                @method('delete')
                                                <button type="button" class="btn btn-link" data-toggle="tooltip" data-placement="bottom" title="Supprimer Produit" onclick="confirm('Voulez-vous vraiment supprimer ce produit? Les enregistrements qui le contiennent continueront d\'exister.') ? this.parentElement.submit() : ''">
                                                    <i class="tim-icons icon-simple-remove"></i>
                                                </button>
                                            </form> --}}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
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
