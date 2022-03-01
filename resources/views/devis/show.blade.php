@extends('layouts.app', ['page' => 'Gestion des devis', 'pageSlug' => 'proforma', 'section' => 'transactions'])

@section('content')
    @include('alerts.success')
    @include('alerts.error')
    <div class="row">
        <div class="col-md-12">
            <div class="card ">
                <div class="card-header">
                    <div class="row">
                        <div class="col-8">
                            <h4 class="card-title">Détails du devis</h4>
                        </div>
                        @if (!$devis->finalized_at)
                            <div class="col-4 text-right">
                                @if ($devis->products->count() == 0)
                                    <form action="{{ route('devis.destroy', $devis) }}" method="post" class="d-inline">
                                        @csrf
                                        @method('delete')
                                        <button type="submit" class="btn btn-sm btn-primary">
                                            Supprimer devis
                                        </button>
                                    </form>
                                @else
                                    <button type="button" class="btn btn-sm btn-primary" onclick="confirm('ATTENTION: Les transactions de cette vente ne semblent pas correspondre au coût des produits, souhaitez-vous la finaliser? Vos enregistrements ne peuvent plus être modifiés à partir de maintenant.') ? window.location.replace('{{ route('devis.finalize', $devis) }}') : ''">
                                        Finaliser devis
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

                            <th>Status</th>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ $devis->id }}</td>
                                <td>{{ date('d-m-y', strtotime($devis->created_at)) }}</td>
                                <td>{{ $devis->user->name }}</td>
                                <td><a href="{{ route('clients.show', $devis->client) }}">{{ $devis->client->name }}<br>TEL-{{ $devis->client->phone }}</a></td>
                                <td>{{ $devis->products->count() }}</td>
                                <td>{{ $devis->products->sum('qty') }}</td>
                                <td>{{ format_money($devis->products->sum('total_amount')) }}</td>
                                <td>{!! $devis->finalized_at ? 'Finalisée le<br>'.date('d-m-y', strtotime($devis->finalized_at)) : (($devis->products->count() > 0) ? 'A FINALISER' : 'EN ATTENTE') !!}</td>
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
                            <h4 class="card-title">produits: {{ $devis->products->sum('qty') }}</h4>
                        </div>
                        @if (!$devis->finalized_at)
                            <div class="col-4 text-right">
                                <a href="{{ route('devis.product.add', ['devis' => $devis->id]) }}" class="btn btn-sm btn-primary">Ajouter produit</a>
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
                            @foreach ($devis->products as $sold_product)
                                <tr>
                                    <td>{{ $sold_product->product->id }}</td>
                                    <td><a href="{{ route('categories.show', $sold_product->product->category) }}">{{ $sold_product->product->category->name }}</a></td>
                                    <td><a href="{{ route('products.show', $sold_product->product) }}">{{ $sold_product->product->name }}</a></td>
                                    <td>{{ $sold_product->qty }}</td>
                                    <td>{{ format_money($sold_product->price) }}</td>
                                    <td>{{ format_money($sold_product->total_amount) }}</td>
                                    <td class="td-actions text-right">
                                        @if(!$devis->finalized_at)
                                            <a href="{{ route('devis.product.edit', ['devis' => $devis, 'soldproduct' => $sold_product]) }}" class="btn btn-link" data-toggle="tooltip" data-placement="bottom" title="Modifier la commande">
                                                <i class="tim-icons icon-pencil"></i>
                                            </a>
                                            <form action="{{ route('devis.product.destroy', ['devis' => $devis, 'soldproduct' => $sold_product]) }}" method="post" class="d-inline">
                                                @csrf
                                                @method('delete')
                                                <button type="button" class="btn btn-link" data-toggle="tooltip" data-placement="bottom" title="Supprimer ligne devis" onclick="confirm('Voulez-vous vraiment supprimer la commande de ce produit? Il sera supprimée de ce devis.') ? this.parentElement.submit() : ''">
                                                    <i class="tim-icons icon-simple-remove"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @if ($devis->finalized_at)
    <div class="row">
        <div class="col-md-12">
            <a href="{{ route('devis.print.options', ['devis' => $devis]) }}" class="btn btn-sm btn-primary">
                Imprimer facture proforma
            </a>
            @if(!$devis->validate)
                <a href="{{ route('devis.products.edit', ['devis' => $devis]) }}" class="btn btn-sm btn-primary">
                    Modifier devis
                </a>
            @endif
        </div>
    </div>
    @endif
@endsection

@push('js')
    <script src="{{ asset('assets') }}/js/sweetalerts2.js"></script>
@endpush
