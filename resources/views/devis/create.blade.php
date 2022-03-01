@extends('layouts.app', ['page' => 'Nouveau devis', 'pageSlug' => 'proforma', 'section' => 'transactions'])

@section('content')
    <div class="container-fluid mt--7">
    @include('alerts.error')
        <div class="row">
            <div class="col-xl-12 order-xl-1">
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">Nouveau devis</h3>
                            </div>
                            <div class="col-4 text-right">
                                <a href="{{ route('devis.index') }}" class="btn btn-sm btn-primary">Retour à la liste</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{ route('devis.store') }}" autocomplete="off">
                            @csrf
                            <h6 class="heading-small text-muted mb-4">Informations du client</h6>
                            <div class="pl-lg-4">
                                <div class="form-group{{ $errors->has('client_id') ? ' has-danger' : '' }}">
                                    <input type="hidden" name="user_id" value="{{ Auth::id() }}">
                                    <label class="form-control-label" for="input-name">Client</label>
                                    <select name="client_id" id="input-category" class="form-select form-control-alternative{{ $errors->has('client') ? ' is-invalid' : '' }}" required>
                                        @foreach ($clients as $client)
                                            @if($client['id'] == old('client'))
                                                <option value="{{$client['id']}}" selected>{{$client['name']}} - {{'TEL-'.$client['phone']}}</option>
                                            @else
                                                <option value="{{$client['id']}}">{{$client['name']}} - {{'TEL-'.$client['phone']}}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    @include('alerts.feedback', ['field' => 'client_id'])
                                </div>

                                <button type="submit" class="btn btn-success mt-4">Continuer</button>
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
@endpush
