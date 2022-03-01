@extends('layouts.app', ['page' => 'Options impression facture', 'pageSlug' => 'sales', 'section' => 'transactions'])

@section('content')
    @include('alerts.success')
    <div class="row">
        <div class="col-md-12">
            <div class="card ">
                <div class="card-header">
                    <div class="row">
                        <div class="col-8">
                            <h4 class="card-title">Options impression facture</h4>
                        </div>
                        <div class="col-4 text-right">
                            <a href="{{ route('sales.show', $sale->id) }}" class="btn btn-sm btn-primary">Retour</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">

                    @if ($sale->finalized_at)
                    <div class="row">
                        <div class="col-md-12">
                            <form action="{{ route('sale.print.pdf', ['sale' => $sale]) }}" method="post" class="d-inline">
                                @csrf
                                @method('post')
                                <label for="">ENTREPRISE</label>
                                <br>
                                <br>
                                <div class="form-check form-check-radio">
                                    <label class="form-check-label">
                                        <input class="form-check-input" type="radio" name="whois" id="whois1" value="1" checked>
                                        ENTETE 1
                                        <span class="form-check-sign"></span>
                                    </label>
                                </div>
                                <div class="form-check form-check-radio">
                                    <label class="form-check-label">
                                        <input class="form-check-input" type="radio" name="whois" id="whois2" value="2">
                                        ENTETE 2
                                        <span class="form-check-sign"></span>
                                    </label>
                                </div>
                                <br>
                                <br>
                                <label for="">TAILLE PAPIER</label>
                                <div class="form-check form-check-radio">
                                    <label class="form-check-label">
                                        <input class="form-check-input" type="radio" name="size" id="format" value="1" checked>
                                        A4
                                        <span class="form-check-sign"></span>
                                    </label>
                                </div>
                                <div class="form-check form-check-radio">
                                    <label class="form-check-label">
                                        <input class="form-check-input" type="radio" name="size" id="format" value="2">
                                        A5
                                        <span class="form-check-sign"></span>
                                    </label>
                                </div>
                                <br>
                                <br>
                                <button type="button" class="btn btn-success" data-toggle="tooltip" data-placement="bottom" title="Imprimer facture" onclick="this.parentElement.submit()">
                                    Imprimer
                                </button>
                            </form>
                        </div>
                    </div>
                    @endif

                </div>


            </div>
        </div>
    </div>
@endsection
