@extends('layouts.app', ['page' => 'Modifier entrée en stock', 'pageSlug' => 'incomes', 'section' => 'transactions'])

@section('content')
    <div class="container-fluid mt--7">
        <div class="row">
            <div class="col-xl-12 order-xl-1">
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">Modifier entrée en stock</h3>
                            </div>
                            <div class="col-4 text-right">
                                <a href="{{ route('transactions.type', ['type' => 'income']) }}" class="btn btn-sm btn-primary">Retour à la liste</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{ route('transactions.update', $transaction) }}" autocomplete="off">
                            @csrf
                            @method('put')
                            <input type="hidden" name="type" value="{{ $transaction->type }}">
                            <input type="hidden" name="user_id" value="{{ $transaction->user_id }}">
                            <h6 class="heading-small text-muted mb-4">Informations entrée en stock</h6>
                            <div class="pl-lg-4">
                                <div class="form-group{{ $errors->has('title') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-title">Titre</label>
                                    <input type="text" name="title" id="input-title" class="form-control form-control-alternative{{ $errors->has('title') ? ' is-invalid' : '' }}" placeholder="Titre" value="{{ old('title', $transaction->title) }}" required autofocus>
                                    @include('alerts.feedback', ['field' => 'title'])
                                </div>


                                <div class="form-group{{ $errors->has('payment_method_id') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-method">Méthode de paiement</label>
                                    <select name="payment_method_id" id="input-method" class="form-select form-control-alternative{{ $errors->has('payment_method_id') ? ' is-invalid' : '' }}" required>
                                        @foreach ($payment_methods as $payment_method)
                                            @if($payment_method['id'] == old('payment_method_id') or $payment_method['id'] == $transaction->payment_method_id)
                                                <option value="{{$payment_method['id']}}" selected>{{$payment_method['name']}}</option>
                                            @else
                                                <option value="{{$payment_method['id']}}">{{$payment_method['name']}}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    @include('alerts.feedback', ['field' => 'payment_method_id'])
                                </div>

                                <div class="form-group{{ $errors->has('amount') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-amount">Montant</label>
                                    <input type="number" step=".01" name="amount" id="input-amount" class="form-control form-control-alternative" placeholder="Montant" value="{{ old('amount', $transaction->amount) }}" min="0" required>
                                    @include('alerts.feedback', ['field' => 'amount'])
                                </div>

                                <div class="form-group{{ $errors->has('reference') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-reference">Référence</label>
                                    <input type="text" name="reference" id="input-reference" class="form-control form-control-alternative{{ $errors->has('reference') ? ' is-invalid' : '' }}" placeholder="Référence" value="{{ old('reference', $transaction->reference) }}">
                                    @include('alerts.feedback', ['field' => 'reference'])
                                </div>

                                <div class="text-center">
                                    <button type="submit" class="btn btn-success mt-4">Sauvegarder</button>
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
        })
    </script>
@endpush('js')
