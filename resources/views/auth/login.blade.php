@extends('layouts.app', ['class' => 'login-page', 'page' => 'Gestion commercial', 'contentClass' => 'login-page', 'section' => 'auth'])

@section('content')
    <div class="col-lg-4 col-md-6 ml-auto mr-auto">
        <form class="form" method="post" action="{{ route('login') }}">
            @csrf

            <div class="card">
                <div class="card-header" style="text-align: center">
                    <img src="{{ asset('assets/img/stock.jpg') }}" alt="">
                    <h1 class="card-title">Connexion</h1>
                </div>
                <div class="card-body">
                    <div class="input-group{{ $errors->has('email') ? ' has-danger' : '' }}">
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                                <i class="tim-icons icon-email-85"></i>
                            </div>
                        </div>
                        <input type="email" name="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" placeholder="Email">
                        @include('alerts.feedback', ['field' => 'email'])
                    </div>
                    <div class="input-group{{ $errors->has('password') ? ' has-danger' : '' }}">
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                                <i class="tim-icons icon-lock-circle"></i>
                            </div>
                        </div>
                        <input type="password" placeholder="Mot de passe" name="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}">
                        @include('alerts.feedback', ['field' => 'password'])
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" href="" class="btn btn-primary btn-lg btn-block mb-3">Connexion</button>
                    {{-- <div class="pull-left">
                        <h6>
                            <a href="{{ route('register') }}" class="link footer-link">Create Account</a>
                        </h6>
                    </div> --}}
                    {{-- <div class="pull-right">
                        <h6>
                            <a href="{{ route('password.request') }}" class="link footer-link">I forgot the passwod</a>
                        </h6>
                    </div> --}}
                </div>
            </div>
        </form>
    </div>
@endsection
