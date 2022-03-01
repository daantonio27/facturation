@extends('layouts.app', ['page' => 'Ventes', 'pageSlug' => 'sales', 'section' => 'transactions'])

@section('content')
    @include('alerts.success')
    <div class="row">
        <div class="col-md-12">
            <div class="card ">
                <div class="card-header">
                    <div class="row">
                        <div class="col-8">
                            <h4 class="card-title">Ventes</h4>
                        </div>
                        <div class="col-4 text-right">
                            <a href="{{ route('sales.create') }}" class="btn btn-sm btn-primary">Nouvelle vente</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="">
                        <table class="table">
                            <thead>
                                <th>Date</th>
                                <th>Client</th>
                                <th>Crée par</th>
                                <th>Produits</th>
                                <th>Total Stock</th>
                                <th>Montant Payé</th>
                                <th>Status</th>
                                <th>Paiement</th>
                                <th>TVA</th>
                                <th class="td-actions text-right">Action</th>
                            </thead>
                            <tbody>
                                @foreach ($sales as $sale)
                                    <tr>
                                        <td>{{ date('d-m-y', strtotime($sale->created_at)) }}</td>
                                        <td><a href="{{ route('clients.show', $sale->client) }}">{{ $sale->client->name }}<br>TEL-{{ $sale->client->phone }}</a></td>
                                        <td>{{ $sale->user->name }}</td>
                                        <td>{{ $sale->products->count() }}</td>
                                        <td>{{ $sale->products->sum('qty') }}</td>
                                        <td>{{ format_money($sale->transactions->sum('amount')) }}</td>
                                        <td>
                                            @if (!$sale->finalized_at)
                                                <span class="text-danger">En cours</span>
                                            @else
                                                <span class="text-success">Validé</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if (!$sale->paid)
                                                <span class="text-danger">Incomplet</span>
                                            @else
                                                <span class="text-success">Complet</span>
                                            @endif
                                        </td>
                                        <td>{{ $sale->tva ? 'OUI' : 'NON' }}</td>
                                        <td class="td-actions text-right">
                                            @if (!$sale->paid)
                                                <a href="{{ route('sales.show', ['sale' => $sale]) }}" class="btn btn-link" data-toggle="tooltip" data-placement="bottom" title="Encaisser paiement">
                                                    <i class="nc-money-coins"></i>
                                                </a>
                                            @endif

                                            @if (!$sale->finalized_at)
                                                <a href="{{ route('sales.show', ['sale' => $sale]) }}" class="btn btn-link" data-toggle="tooltip" data-placement="bottom" title="Modifier vente">
                                                    <i class="tim-icons icon-pencil"></i>
                                                </a>
                                            @else
                                                <a href="{{ route('sales.show', ['sale' => $sale]) }}" class="btn btn-link" data-toggle="tooltip" data-placement="bottom" title="Détails vente">
                                                    <i class="tim-icons icon-zoom-split"></i>
                                                </a>
                                            @endif
                                            {{-- <form action="{{ route('sales.destroy', $sale) }}" method="post" class="d-inline">
                                                @csrf
                                                @method('delete')
                                                <button type="button" class="btn btn-link" data-toggle="tooltip" data-placement="bottom" title="Supprimer vente" onclick="confirm('Voulez-vous vraiment supprimer cette vente? Tous vos enregistrements seront supprimés définitivement.') ? this.parentElement.submit() : ''">
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
                    <nav class="d-flex justify-content-end" aria-label="...">
                        {{ $sales->links() }}
                    </nav>
                </div>
            </div>
        </div>
    </div>
@endsection
