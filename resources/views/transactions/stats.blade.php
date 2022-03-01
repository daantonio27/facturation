@extends('layouts.app', ['pageSlug' => 'tstats', 'page' => 'Statistiques', 'section' => 'transactions'])

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-8">
                            <h4 class="card-title">Statistique transaction</h4>
                        </div>
                        <div class="col-4 text-right">
                            <a href="{{ route('transactions.index') }}" class="btn btn-sm btn-primary">
                                Voir transactions
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                        <table class="table">
                            <thead>
                                <th>Période</th>
                                <th>Transactions</th>
                                <th>Réception</th>
                                <th>Dépenses</th>
                                <th>Paiements</th>
                                <th>Solde de trésorerie</th>
                                <th>Solde total</th>
                                <th></th>
                            </thead>
                            <tbody>
                                @foreach ($transactionsperiods as $period => $data)
                                    <tr>
                                        <td>{{ $period }}</td>
                                        <td>{{ $data->count() }}</td>
                                        <td>{{ format_money($data->where('type', 'income')->sum('amount')) }}</td>
                                        <td>{{ format_money($data->where('type', 'expense')->sum('amount')) }}</td>
                                        <td>{{ format_money($data->where('type', 'payment')->sum('amount')) }}</td>
                                        <td>{{ format_money($data->where('payment_method_id', optional($methods->where('name', 'Cash')->first())->id)->sum('amount')) }}</td>
                                        <td>{{ format_money($data->sum('amount')) }}</td>
                                        <td></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card card-tasks">
                <div class="card-header">
                    <div class="row">
                        <div class="col-8">
                            <h4 class="card-title">Soldes en attente</h4>
                        </div>
                        <div class="col-4 text-right">
                            <a href="{{ route('clients.index') }}" class="btn btn-sm btn-primary">Voir Clients</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-full-width table-responsive">
                        <table class="table">
                            <thead>
                                <th>Client</th>
                                <th>Achats</th>
                                <th>Transactions</th>
                                <th>Balance</th>
                                <th></th>
                            </thead>
                            <tbody>
                                @foreach($clients as $client)
                                    <tr>
                                        <td><a href="{{ route('clients.show', $client) }}">{{ $client->name }}<br>TEL-{{ $client->phone }}</a></td>
                                        <td>{{ $client->sales->count() }}</td>
                                        <td>{{ format_money($client->transactions->sum('amount')) }}</td>
                                        <td>
                                            @if ($client->balance > 0)
                                                <span class="text-success">{{ format_money($client->balance) }}</span>
                                            @elseif ($client->balance < 0.00)
                                                <span class="text-danger">{{ format_money($client->balance) }}</span>
                                            @else
                                                {{ format_money($client->balance) }}
                                            @endif
                                        </td>
                                        <td>
                                            {{-- <a href="{{ route('clients.transactions.add', $client) }}" class="btn btn-link" data-toggle="tooltip" data-placement="bottom" title="Enregistrer la transaction">
                                                <i class="tim-icons icon-simple-add"></i>
                                            </a> --}}
                                            <a href="{{ route('clients.show', $client) }}" class="btn btn-link" data-toggle="tooltip" data-placement="bottom" title="Voir Client">
                                                <i class="tim-icons icon-zoom-split"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card card-tasks">
                <div class="card-header">
                    <div class="row">
                        <div class="col-8">
                            <h4 class="card-title">Statistique par Méthode</h4>
                        </div>
                        <div class="col-4 text-right">
                            <a href="{{ route('methods.index') }}" class="btn btn-sm btn-primary">Voir Méthode</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-full-width table-responsive">
                        <table class="table">
                            <thead>
                                <th>Méthode</th>
                                <th>Nb Transactions {{ $date->year }}</th>
                                <th>Balance {{ $date->year }}</th>
                                <th></th>
                            </thead>
                            <tbody>
                                @foreach($methods as $method)
                                    <tr>
                                        <td><a href="{{ route('methods.show', $method) }}">{{ $method->name }}</a></td>
                                        <td>{{ $transactionsperiods['Année']->where('payment_method_id', $method->id)->count() }}</td>
                                        <td>{{ format_money($transactionsperiods['Année']->where('payment_method_id', $method->id)->sum('amount')) }}</td>
                                        <td>
                                            <a href="{{ route('methods.show', $method) }}" class="btn btn-link" data-toggle="tooltip" data-placement="bottom" title="Voir méthode">
                                                <i class="tim-icons icon-zoom-split"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
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
                        <h4 class="card-title">Statistique  ventes</h4>
                    </div>
                    <div class="col-4 text-right">
                        <a href="{{ route('sales.index') }}" class="btn btn-sm btn-primary">Voir vente</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <th>Période</th>
                        <th>Vente</th>
                        <th>Clients</th>
                        <th>Total Stock</th>
                        <th data-toggle="tooltip" data-placement="bottom" title="Promedio de ingresos por cada venta">Moyenne C / V</th>
                        <th>Montant facturé</th>
                        <th>A Finaliser</th>
                    </thead>
                    <tbody>
                        @foreach ($salesperiods as $period => $data)
                            <tr>
                                <td>{{ $period }}</td>
                                <td>{{ $data->count() }}</td>
                                <td>{{ $data->groupBy('client_id')->count() }}</td>
                                <td>{{ $data->where('finalized_at', '!=', null)->map(function ($sale) {return $sale->products->sum('qty');})->sum() }}</td>
                                <td>{{ format_money($data->avg('total_amount')) }}</td>
                                <td>{{ format_money($data->where('finalized_at', '!=', null)->map(function ($sale) {return $sale->products->sum('total_amount');})->sum()) }}</td>
                                <td>{{ $data->where('finalized_at', null)->count() }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        </div>
    </div>
@endsection
