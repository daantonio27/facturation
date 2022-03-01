@extends('layouts.app', ['page' => 'Retour vente', 'pageSlug' => 'sales', 'section' => 'transactions'])

@section('content')
    @include('alerts.success')
    @include('alerts.error')
    <div class="row">
        <div class="col-md-12">
            <div class="card ">
                <div class="card-header">
                    <div class="row">
                        <div class="col-8">
                            <h4 class="card-title">Modification vente</h4>
                        </div>
                        @if (!$sale->finalized_at)
                            <div class="col-4 text-right">
                                @if ($sale->products->count() == 0)
                                    <form action="{{ route('sales.destroy', $sale) }}" method="post" class="d-inline">
                                        @csrf
                                        @method('delete')
                                        <button type="submit" class="btn btn-sm btn-primary">
                                            Supprimer vente
                                        </button>
                                    </form>
                                @else
                                    <button type="button" class="btn btn-sm btn-primary" onclick="confirm('ATTENTION: Les transactions de cette vente ne semblent pas correspondre au coût des produits, souhaitez-vous la finaliser? Vos enregistrements ne peuvent plus être modifiés à partir de maintenant.') ? window.location.replace('{{ route('sales.finalize', $sale) }}') : ''">
                                        Finaliser ventes
                                    </button>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <th>ID</th>
                            <th>Date</th>
                            <th>Crée par</th>
                            <th>Client</th>
                            <th>produits</th>
                            <th>Total Stock</th>
                            <th>Coût total</th>
                            <th>Montant encaissé</th>
                            <th>Montant restant</th>
                            <th>Status</th>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ $sale->id }}</td>
                                <td>{{ date('d-m-y', strtotime($sale->created_at)) }}</td>
                                <td>{{ $sale->user->name }}</td>
                                <td><a href="{{ route('clients.show', $sale->client) }}">{{ $sale->client->name }}<br>TEL-{{ $sale->client->phone }}</a></td>
                                <td>{{ $sale->products->count() }}</td>
                                <td>{{ $sale->products->sum('qty') }}</td>
                                <td>{{ format_money($sale->products->sum('total_amount')) }}</td>
                                <td>{{ $sale->transactions->sum('amount') }}</td>
                                <td>{{ format_money($sale->products->sum('total_amount') - $sale->transactions->sum('amount'))}}</td>
                                <td>{!! $sale->finalized_at ? 'Finalisée le<br>'.date('d-m-y', strtotime($sale->finalized_at)) : (($sale->products->count() > 0) ? 'A FINALISER' : 'EN ATTENTE') !!}</td>
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
                    <div class="row">
                        <div class="col-8">
                            <h4 class="card-title">produits: {{ $sale->products->sum('qty') }}</h4>
                        </div>
                        @if ($sale->finalized_at)
                            <div class="col-4 text-right">
                                <a href="{{ route('return.product.add', ['sale' => $sale->id]) }}" class="btn btn-sm btn-primary">Ajouter produit</a>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <th>ID</th>
                            <th>Catégorie</th>
                            <th>Produit</th>
                            <th>Quantité</th>
                            <th>Prix Unitaire</th>
                            <th>Total</th>
                            <th></th>
                        </thead>
                        <tbody>
                            @foreach ($items as $sold_product)
                                <tr>
                                    <td>{{ $sold_product->product->id }}</td>
                                    <td><a href="{{ route('categories.show', $sold_product->product->category) }}">{{ $sold_product->product->category->name }}</a></td>
                                    <td><a href="{{ route('products.show', $sold_product->product) }}">{{ $sold_product->product->name }}</a></td>
                                    <td>{{ $sold_product->qty }}</td>
                                    <td>{{ format_money($sold_product->price) }}</td>
                                    <td>{{ format_money($sold_product->total_amount) }}</td>
                                    <td class="td-actions text-right">
                                        <a href="{{ route('return.product.edit', ['sale' => $sale, 'soldproduct' => $sold_product]) }}" class="btn btn-link" data-toggle="tooltip" data-placement="bottom" title="Modifier la commande">
                                            <i class="tim-icons icon-pencil"></i>
                                        </a>
                                        {{-- <form action="{{ route('return.product.destroy', ['sale' => $sale, 'soldproduct' => $sold_product]) }}" method="post" class="d-inline">
                                            @csrf
                                            @method('delete')
                                            <button type="button" class="btn btn-link" data-toggle="tooltip" data-placement="bottom" title="Supprimer commande" onclick="confirm('Voulez-vous vraiment supprimer la commande de ce produit? Il sera supprimée de cette vente.') ? this.parentElement.submit() : ''">
                                                <i class="tim-icons icon-simple-remove"></i>
                                            </button>
                                        </form> --}}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <a  class="btn btn-sm btn-primary" href="{{ route('return.finalize', ['sale' => $sale]) }}">
                        Finaliser vente
                </a>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('js')
    <script src="{{ asset('assets') }}/js/sweetalerts2.js"></script>
@endpush
