@extends('layouts.app', ['pageSlug' => 'dashboard', 'page' => 'Tableau de bord', 'section' => ''])

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-chart">
                <div class="card-header ">
                    <div class="row">
                        <div class="col-sm-6 text-left">
                            <h5 class="card-category">Ventes totales</h5>
                            <h2 class="card-title">Rendement annuel</h2>
                        </div>
                        <div class="col-sm-6">
                            <div class="btn-group btn-group-toggle float-right" data-toggle="buttons">
                            <label class="btn btn-sm btn-primary btn-simple active" id="0">
                                <input type="radio" name="options" checked>
                                <span class="d-none d-sm-block d-md-block d-lg-block d-xl-block">Produits</span>
                                <span class="d-block d-sm-none">
                                    <i class="tim-icons icon-single-02"></i>
                                </span>
                            </label>
                            <label class="btn btn-sm btn-primary btn-simple" id="1">
                                <input type="radio" class="d-none d-sm-none" name="options">
                                <span class="d-none d-sm-block d-md-block d-lg-block d-xl-block">Achats</span>
                                <span class="d-block d-sm-none">
                                    <i class="tim-icons icon-gift-2"></i>
                                </span>
                            </label>
                            <label class="btn btn-sm btn-primary btn-simple" id="2">
                                <input type="radio" class="d-none" name="options">
                                <span class="d-none d-sm-block d-md-block d-lg-block d-xl-block">Clients</span>
                                <span class="d-block d-sm-none">
                                    <i class="tim-icons icon-tap-02"></i>
                                </span>
                            </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="chartBig1"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- <div class="col-lg-6">
            <div class="card card-chart">
                <div class="card-header">
                    <h5 class="card-category">Revenu du mois dernier</h5>
                    <h3 class="card-title"><i class="tim-icons icon-money-coins text-primary"></i>{{ format_money($semesterincomes) }}</h3>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="chartLinePurple"></canvas>
                    </div>
                </div>
            </div>
        </div> --}}
        {{-- <div class="col-lg-4">
            <div class="card card-chart">
                <div class="card-header">
                    <h5 class="card-category">Solde mensuel</h5>
                    <h3 class="card-title"><i class="tim-icons icon-bank text-info"></i> {{ format_money($monthlybalance) }}</h3>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="CountryChart"></canvas>
                    </div>
                </div>
            </div>
        </div> --}}
        {{-- <div class="col-lg-6">
            <div class="card card-chart">
                <div class="card-header">
                    <h5 class="card-category">Dépenses le mois dernier</h5>
                    <h3 class="card-title"><i class="tim-icons icon-paper text-success"></i> {{ format_money($semesterexpenses) }}</h3>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="chartLineGreen"></canvas>
                    </div>
                </div>
            </div>
        </div> --}}
    </div>

    <div class="row">
        <div class="col-lg-6 col-md-12">
            <div class="card card-tasks">
                <div class="card-header">
                    <div class="row">
                        <div class="col-8">
                            <h4 class="card-title">Ventes en attente</h4>
                        </div>
                        <div class="col-4 text-right">
                            <a href="{{ route('sales.create') }}" class="btn btn-sm btn-primary">Nouvelle vente</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-full-width table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>
                                        Date
                                    </th>
                                    <th>
                                        Client
                                    </th>
                                    <th>
                                        Produits
                                    </th>
                                    <th>
                                        Payé
                                    </th>
                                    <th>
                                        Total
                                    </th>
                                    <th>

                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($unfinishedsales as $sale)
                                    <tr>
                                        <td>{{ date('d-m-y', strtotime($sale->created_at)) }}</td>
                                        <td><a href="">{{ $sale->client->name }}<br>TEL-{{ $sale->client->phone }}</a></td>
                                        <td>{{ $sale->products->count() }}</td>
                                        <td>{{ format_money($sale->transactions->sum('amount')) }}</td>
                                        <td>{{ format_money($sale->products->sum('total_amount')) }}</td>
                                        <td class="td-actions text-right">
                                            <a href="{{ route('sales.show', ['sale' => $sale]) }}" class="btn btn-link" data-toggle="tooltip" data-placement="bottom" title="View Sale">
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
        <div class="col-lg-6 col-md-12">
            <div class="card card-tasks">
                <div class="card-header">
                <div class="row">
                        <div class="col-8">
                            <h4 class="card-title">Dernières transactions</h4>
                        </div>
                        <div class="col-4 text-right">
                            {{-- <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#transactionModal">
                                Nouvelle transaction
                            </button> --}}
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-full-width table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>
                                        Catégorie
                                    </th>
                                    <th>
                                        Titre
                                    </th>
                                    <th>
                                        Moyenne
                                    </th>
                                    <th>
                                        Total
                                    </th>
                                    <th>

                                    </th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach($lasttransactions as $transaction)
                                    <tr>
                                        <td>
                                            @if($transaction->type == 'expense')
                                                Frais
                                            @elseif($transaction->type == 'sale')
                                                Vente
                                            @elseif($transaction->type == 'payment')
                                                Paiement
                                            @elseif($transaction->type == 'income')
                                                le revenu
                                            @else
                                                {{ $transaction->type }}
                                            @endif

                                        </td>
                                        <td>{{ $transaction->title }}</td>
                                        <td>{{ $transaction->method->name }}</td>
                                        <td>{{ format_money($transaction->amount) }}</td>
                                        <td class="td-actions text-right">
                                            @if ($transaction->sale_id)
                                                <a href="{{ route('sales.show', $transaction->sale_id) }}" class="btn btn-link" data-toggle="tooltip" data-placement="bottom" title="More details">
                                                    <i class="tim-icons icon-zoom-split"></i>
                                                </a>
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
    </div>

    <div class="modal fade" id="transactionModal" tabindex="-1" role="dialog" aria-labelledby="transactionModal" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Nouvelle transaction</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('transactions.create', ['type' => 'payment']) }}" class="btn btn-sm btn-primary">Paiement Fournisseur</a>
                        <a href="{{ route('transactions.create', ['type' => 'expense']) }}" class="btn btn-sm btn-primary">Nouvelle dépense</a>
                        <a href="{{ route('sales.create') }}" class="btn btn-sm btn-primary">Nouvelle vente</a>
                        {{-- <a href="{{ route('transfer.create') }}" class="btn btn-sm btn-primary">Transfert</a> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="{{ asset('assets/js/plugins/chartjs.min.js') }}"></script>

    <script>
        var lastmonths = [];

        @foreach ($lastmonths as $id => $month)
            lastmonths.push('{{ strtoupper($month) }}')
        @endforeach

        var lastincomes = {{ $lastincomes }};
        var lastexpenses = {{ $lastexpenses }};
        var anualsales = {{ $anualsales }};
        var anualclients = {{ $anualclients }};
        var anualproducts = {{ $anualproducts }};
        var methods = [];
        var methods_stats = [];

        @foreach($monthlybalancebymethod as $method => $balance)
            methods.push('{{ $method }}');
            methods_stats.push('{{ $balance }}');
        @endforeach

        $(document).ready(function() {
            demo.initDashboardPageCharts();
        });
    </script>
@endpush
