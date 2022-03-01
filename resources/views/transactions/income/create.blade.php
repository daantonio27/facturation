@extends('layouts.app', ['page' => 'Encaissement Paiement Client', 'pageSlug' => 'incomes', 'section' => 'transactions'])

@section('content')
    <div class="container-fluid mt--7">
        <div class="row">
            <div class="col-xl-12 order-xl-1">
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">Encaissement Paiement Client</h3>
                            </div>
                            <div class="col-4 text-right">
                                {{-- <a href="{{ route('transactions.type', ['type' => 'income']) }}" class="btn btn-sm btn-primary">Retour à la liste</a> --}}
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{ route('transactions.store') }}" autocomplete="off">
                            @csrf
                            <input type="hidden" name="type" value="income">
                            <input type="hidden" name="user_id" value="{{ Auth::id() }}">
                            <input type="hidden" name="sale_id" value="{{ $sale->id }}">
                            <input type="hidden" name="client_name" value="{{ $sale->client->name }}">
                            <input type="hidden" name="client_id" value="{{ $sale->client->id }}">
                            <h6 class="heading-small text-muted mb-4">Informations encaissement</h6>
                            <div class="pl-lg-4">
                                {{-- <div class="form-group{{ $errors->has('title') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-title">Titre</label>
                                    <input type="text" name="title" id="input-title" class="form-control form-control-alternative{{ $errors->has('title') ? ' is-invalid' : '' }}" placeholder="Titre" value="{{ old('title') }}" required autofocus>
                                    @include('alerts.feedback', ['field' => 'title'])
                                </div> --}}


                                <div class="form-group{{ $errors->has('payment_method_id') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-method">Méthode de paiement</label>
                                    <select name="payment_method_id" id="input-method" class="form-select form-control-alternative{{ $errors->has('payment_method_id') ? ' is-invalid' : '' }}" required>
                                        @foreach ($payment_methods as $payment_method)
                                            @if($payment_method['id'] == old('payment_method_id'))
                                                <option value="{{$payment_method['id']}}" selected>{{$payment_method['name']}}</option>
                                            @else
                                                <option value="{{$payment_method['id']}}">{{$payment_method['name']}}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    @include('alerts.feedback', ['field' => 'payment_method_id'])
                                </div>

                                <div class="form-group">
                                    <label class="form-control-label" for="input-to-pay">Montant à payer</label>
                                    <input type="number" min="1"  id="input-to-pay" class="form-control"  value="{{ $sale->total_amount - $sale->transactions->sum('amount') }}" readonly>
                                </div>

                                <div class="form-group{{ $errors->has('amount') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-amount">Montant</label>
                                    <input type="number" step=".01" name="amount" id="input-amount" class="form-control form-control-alternative" placeholder="Montant" value="{{ old('amount') }}" min="0" required>
                                    @include('alerts.feedback', ['field' => 'amount'])

                                </div>

                                <div class="text-center">
                                    <span id="saveError" style="color: red"></span>
                                </div>

                                <div class="text-center">
                                    <button type="submit" class="btn btn-success mt-4" id="save-encaisse-btn">Sauvegarder</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        new SlimSelect({
            select: '.form-select'
        });


    </script>
@endpush('js')

@push('js')
    <script>
        $('#input-amount').on('keyup',function(){
            if(parseInt($('#input-to-pay').val() - parseInt($('#input-amount').val())) < 0){
                $('#saveError').text('Le montant encaissé ne peut pas être supérieur au montant à payer');
                $('#save-encaisse-btn').hide();
            }else{
                $('#saveError').text('');
                $('#save-encaisse-btn').show();
            }
        });

    </script>
@endpush('js')
