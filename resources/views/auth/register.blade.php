@extends('layouts.app')
@include('top-img')

@section('content')
<div class="container" style="margin-top: 25px; margin-bottom: 15px;" >
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Registeration</div>
                
                <div class="card-body">
                    @if (config()->get('app.env') == 'local' || config()->get('app.env') == 'dev' || config()->get('app.env') == 'development')
                        <p>This site is for development only. Registeration can only be done on the production site. <br><a href="https://openwod.com">Click here to get to the production site.</a></p>    
                        <a href="/login" class="btn btn-secondary">
                            Back to startpage
                        </a>
                    @else
                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            <div class="form-group row">
                                <label for="first_name" class="col-md-4 col-form-label text-md-right">First Name</label>

                                <div class="col-md-6">
                                    <input id="first_name" type="text" class="form-control @error('first_name') is-invalid @enderror" name="first_name" value="{{ old('first_name') }}" required autocomplete="first_name" autofocus>

                                    @error('first_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="last_name" class="col-md-4 col-form-label text-md-right">Surname</label>

                                <div class="col-md-6">
                                    <input id="last_name" type="text" class="form-control @error('last_name') is-invalid @enderror" name="last_name" value="{{ old('last_name') }}" required autocomplete="last_name" autofocus>

                                    @error('first_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="email" class="col-md-4 col-form-label text-md-right">E-mail</label>

                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password" class="col-md-4 col-form-label text-md-right">Password</label>

                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password-confirm" class="col-md-4 col-form-label text-md-right">Repeate password</label>

                                <div class="col-md-6">
                                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                                </div>
                            </div>

                            <div class="form-check row">
                                <div class="col-md-6 offset-md-4">
                                    <input id="policies" name="policies" type="checkbox" class="form-check-input" id="policyAndCookies">
                                    <label class="form-check-label" for="policies">I agree to the OpenWOD <a href="/policy" target="_blank">Privacy Policy</a>.</label>

                                    @error('policies')
                                        <span class="text-danger" style="font-size: 80%;" role="alert">
                                            <strong>You need to accept the Privacy Policy.</strong>
                                        </span>
                                    @enderror
                                    </div>
                                </div>
                            <br>

                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        Register
                                    </button>
                                </div>
                            </div>
                            <div class="form-group row mb-0" style="margin-top: 8px;">
                                <div class="col-md-6 offset-md-4">
                                    <a href="/login" class="btn btn-secondary">
                                        Back
                                    </a>
                                </div>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
