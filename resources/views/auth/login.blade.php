@extends('layouts.app')
@include('top-img')

@section('content')
<div class="container" style="margin-bottom: 15px;">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
            <div class="card-header">Log in</div>

            <div class="card-body" style="padding-top: 3px">
                <div class="form-group row mb-0" style="margin-top: 10px;">
                    <div style="margin: 0 auto;">
                        <a class="btn btn-primary" href="/sign-in/facebook">Log in with Facebook</a>
                    </div>
                </div>
                <p class="or">Or</p>

                    <form method="POST" action="{{ config('services.auth.base_url') }}/login" id="loginForm">
                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">E-mail</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">Password</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">Keep me logged in</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">Log in</button>
                            </div>
                        </div>
                        <div class="form-group row mb-0" style="margin-top: 10px;">
                            <div class="col-md-8 offset-md-4">
                                <a class="btn btn-secondary" href="/register">Register</a>
                                <span class="invalid-feedback" id="error" role="alert" style="display: hidden;"><b></b></span>
                            </div>
                        </div>
                        <br>
                        <div class="form-group row">
                            <div class="col-md-6 offset-md-4">
                                <p class="cookies-warning">By logging in, you agree to {{ config('app.name') }}'s <a href="/policy" target="_blank">Privacy Policy</a>.</p>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .or {
        margin: 10px 0;
        margin-bottom: 0;
        text-align: center;
        font-size: 20px;
        background-image:linear-gradient(#000,#000),linear-gradient(#000,#000);
        background-size:47% 1px;
        background-position:center left,center right;
        background-repeat:no-repeat;
      }

      @media (max-width: 550px) {
        .or {
            background-size:45% 1px;
        }
      }

      .cookies-warning {
          margin: 0;
          color: gray;
      }
</style>

@vite(['resources/js/auth/login.js'])
@endsection
