@extends('layouts.app', ['page' => 'Gérer réception', 'pageSlug' => 'receipts', 'section' => 'inventory'])


@section('content')
    @include('alerts.success')
    @include('alerts.error')
    <div class="row">
        <div class="col-md-12">
            <div class="card ">
                <div class="card-header">
                    <div class="row">
                        <div class="col-8">
                            <h4 class="card-title">Récapitulatif des reçus</h4>
                        </div>
                        @if (!$receipt->finalized_at)
                            <div class="col-4 text-right">
                                @if ($receipt->products->count() === 0)
                                    <form action="{{ route('receipts.destroy', $receipt) }}" method="post" class="d-inline">
                                        @csrf
                                        @method('delete')
                                        <button type="submit" class="btn btn-sm btn-primary">
                                            Supprimer le reçu
                                        </button>
                                    </form>
                                @else
                                    <button type="button" class="btn btn-sm btn-primary" onclick="confirm('ATTENTION: À la fin de ce reçu, vous ne pourrez pas y charger plus de produits.') ? window.location.replace('{{ route('receipts.finalize', $receipt) }}') : ''">
                                        Finaliser le réception
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
                            <th>Titre</th>
                            <th>Créer par</th>
                            <th>Fournisseur</th>
                            <th>produits</th>
                            <th>Stock</th>
                            <th>Défectueux</th>
                            <th>Status</th>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ $receipt->id }}</td>
                                <td>{{ date('d-m-y', strtotime($receipt->created_at)) }}</td>
                                <td style="max-width:150px;">{{ $receipt->title }}</td>
                                <td>{{ $receipt->user->name }}</td>
                                <td>
                                    @if($receipt->provider_id)
                                        <a href="{{ route('providers.show', $receipt->provider) }}">{{ $receipt->provider->name }}</a>
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td>{{ $receipt->products->count() }}</td>
                                <td>{{ $receipt->products->sum('stock') }}</td>
                                <td>{{ $receipt->products->sum('stock_defective') }}</td>
                                <td>{!! $receipt->finalized_at ? 'Finalisé' : '<span style="color:red; font-weight:bold;">En cours</span>' !!}</td>
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
                            <h4 class="card-title">produits: {{ $receipt->products->count() }}</h4>
                        </div>
                        @if (!$receipt->finalized_at)
                            <div class="col-4 text-right">
                                <a href="{{ route('receipts.product.add', ['receipt' => $receipt]) }}" class="btn btn-sm btn-primary">Ajouter produit</a>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <th>Catégorie</th>
                            <th>Produit</th>
                            <th>Stock</th>
                            <th>Prix unitaire</th>
                            <th>Défectueux</th>
                            <th>Total Stock</th>
                            <th></th>
                        </thead>
                        <tbody>
                            @foreach ($receipt->products as $received_product)
                                <tr>
                                    <td><a href="{{ route('categories.show', $received_product->product->category) }}">{{ $received_product->product->category->name }}</a></td>
                                    <td><a href="{{ route('products.show', $received_product->product) }}">{{ $received_product->product->name }}</a></td>
                                    <td>{{ $received_product->stock }}</td>
                                    <td>{{ $received_product->price }}</td>
                                    <td>{{ $received_product->stock_defective }}</td>
                                    <td>{{ $received_product->stock + $received_product->stock_defective }}</td>
                                    <td class="td-actions text-right">
                                        @if(!$receipt->finalized_at)
                                            <a href="{{ route('receipts.product.edit', ['receipt' => $receipt, 'receivedproduct' => $received_product]) }}" class="btn btn-link" data-toggle="tooltip" data-placement="bottom" title="Modifier produit">
                                                <i class="tim-icons icon-pencil"></i>
                                            </a>
                                            <form action="{{ route('receipts.product.destroy', ['receipt' => $receipt, 'receivedproduct' => $received_product]) }}" method="post" class="d-inline">
                                                @csrf
                                                @method('delete')
                                                <button type="button" class="btn btn-link" data-toggle="tooltip" data-placement="bottom" title="Supprimer" onclick="confirm('Voulez-vous vraiment supprimer ce produit?') ? this.parentElement.submit() : ''">
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
@endsection

@push('js')
    <script src="{{ asset('assets') }}/js/sweetalerts2.js"></script>
@endpush
