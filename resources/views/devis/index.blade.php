@extends('layouts.app', ['page' => 'Devis', 'pageSlug' => 'proforma', 'section' => 'transactions'])

@section('content')
    @include('alerts.success')
    <div class="row">
        <div class="col-md-12">
            <div class="card ">
                <div class="card-header">
                    <div class="row">
                        <div class="col-8">
                            <h4 class="card-title">Devis</h4>
                        </div>
                        <div class="col-4 text-right">
                            <a href="{{ route('devis.create') }}" class="btn btn-sm btn-primary">Nouveau devis</a>
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
                                <th>Montant total</th>
                                <th>TVA</th>
                                <th>Status</th>
                                <th>Validé</th>
                                <th></th>
                            </thead>
                            <tbody>
                                @foreach ($devis as $devi)
                                    <tr>
                                        <td>{{ date('d-m-y', strtotime($devi->created_at)) }}</td>
                                        <td><a href="{{ route('clients.show', $devi->client) }}">{{ $devi->client->name }}<br>TEL-{{ $devi->client->phone }}</a></td>
                                        <td>{{ $devi->user->name }}</td>
                                        <td>{{ $devi->products->count() }}</td>
                                        <td>{{ $devi->products->sum('qty') }}</td>
                                        <td>{{ format_money($devi->products->sum('total_amount')) }}</td>
                                        <td>{{ $devi->tva ? 'OUI' : 'NON' }}</td>
                                        <td>
                                            @if (!$devi->finalized_at)
                                                <span class="text-danger">En cours</span>
                                            @else
                                                <span class="text-success">Terminé</span>
                                            @endif
                                        </td>

                                        <td>
                                            @if (!$devi->validate)
                                                <span class="text-danger">NON</span>
                                            @else
                                                <span class="text-success">OUI</span>
                                            @endif
                                        </td>

                                        <td class="td-actions text-right">

                                            @if (!$devi->finalized_at)
                                                <a href="{{ route('devis.show', $devi->id)}}" class="btn btn-link" data-toggle="tooltip" data-placement="bottom" title="Modifier devis">
                                                    <i class="tim-icons icon-pencil"></i>
                                                </a>

                                               

                                            @else
                                                <a href="{{ route('devis.show', $devi->id) }}" class="btn btn-link" data-toggle="tooltip" data-placement="bottom" title="Voir devis">
                                                    <i class="tim-icons icon-zoom-split"></i>
                                                </a>
                                                @if(!$devi->validate)
                                                    <a href="{{ route('devis.validate', $devi) }}" class="btn btn-link" data-toggle="tooltip" data-placement="bottom" title="Valider devis">
                                                        <i class="tim-icons icon-minimal-down"></i>
                                                    </a>
                                                @endif
                                            @endif
                                            {{-- <form action="{{ route('devis.destroy', $devi->id) }}" method="post" class="d-inline">
                                                @csrf
                                                @method('delete')
                                                <button type="button" class="btn btn-link" data-toggle="tooltip" data-placement="bottom" title="Supprimer devis" onclick="confirm('Voulez-vous vraiment supprimer cette promotion? Tous vos enregistrements seront supprimés définitivement.') ? this.parentElement.submit() : ''">
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
                        {{ $devis->links() }}
                    </nav>
                </div>
            </div>
        </div>
    </div>
@endsection
