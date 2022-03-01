@extends('layouts.app', ['page' => 'Encaissement', 'pageSlug' => 'incomes', 'section' => 'transactions'])

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card ">
                <div class="card-header">
                    <div class="row">
                        <div class="col-8">
                            <h4 class="card-title">Encaissements annulés</h4>
                        </div>
                        <div class="col-4 text-right">
                            <a href="{{ route('transactions.type', ['type' => 'income'])  }}" class="btn btn-sm btn-primary">Liste des encaissements validés</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @include('alerts.success')

                    <div class="">
                        <table class="table tablesorter " id="">
                            <thead class=" text-primary">
                                <th scope="col">Date</th>
                                <th scope="col">Annulée par</th>
                                <th scope="col">Titre</th>
                                <th scope="col">Méthode paiement</th>
                                <th scope="col">Montant</th>
                                <th scope="col">Référence</th>
                                <th scope="col"></th>
                            </thead>
                            <tbody>
                                @foreach ($transactions as $transaction)
                                    <tr>
                                        <td> {{ date('d-m-y', strtotime($transaction->created_at)) }}</td>
                                        <td> {{ $transaction->user->name }}</td>
                                        <td> {{ $transaction->title }}</td>
                                        <td><a href="#">{{ $transaction->method->name }}</a></td>
                                        <td>{{ format_money($transaction->amount) }}</td>
                                        <td>{{ $transaction->reference }}</td>
                                        <td></td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer py-4">
                    <nav class="d-flex justify-content-end" aria-label="...">
                        {{ $transactions->links() }}
                    </nav>
                </div>
            </div>
        </div>
    </div>
@endsection
