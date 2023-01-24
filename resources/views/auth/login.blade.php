@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Login') }}</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="row mb-3">
                                <label for="email"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                                <div class="col-md-6">
                                    <input id="email" type="email"
                                        class="form-control @error('email') is-invalid @enderror" name="email"
                                        value="{{ old('email') }}" required autocomplete="email" autofocus>

                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="password"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>

                                <div class="col-md-6">
                                    <input id="password" type="password"
                                        class="form-control @error('password') is-invalid @enderror" name="password"
                                        required autocomplete="current-password">

                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Login') }}
                                    </button>

                                    @if (Route::has('password.request'))
                                        <a class="btn btn-link" href="{{ route('password.request') }}">
                                            {{ __('Forgot Your Password?') }}
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-header">{{ __('Sample login') }}</div>
                    <div class="card-body">
                        <div class="card">
                            <div class="card-header">{{ __('Admin') }}</div>
                            <div class="card-body d-flex justify-content-between">
                                <div>
                                    Email Address :
                                    <span id="adminEmail">
                                        admin@admin.com
                                    </span>
                                    <br>
                                    Password : password
                                </div>
                                <button type="submit" class="btn btn-primary" onclick="copy('admin')">
                                    {{ __('Copy') }}
                                </button>
                            </div>
                        </div>
                        <div class="card mt-3">
                            <div class="card-header">{{ __('Agent') }}</div>
                            <div class="card-body d-flex justify-content-between">
                                <div>
                                    Email Address :
                                    <span id="agentEmail">
                                        agent@agent.com
                                    </span>
                                    <br>
                                    Password : password
                                </div>
                                <button type="submit" class="btn btn-primary" onclick="copy('agent')">
                                    {{ __('Copy') }}
                                </button>
                            </div>
                        </div>
                        <div class="card mt-3">
                            <div class="card-header">{{ __('User') }}</div>
                            <div class="card-body d-flex justify-content-between">
                                <div>
                                    Email Address :
                                    <span id="userEmail">
                                        user@user.com
                                    </span>
                                    <br>
                                    Password : password
                                </div>
                                <button type="submit" class="btn btn-primary" onclick="copy('user')">
                                    {{ __('Copy') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function copy(value) {
            if(value == "admin") {
                document.getElementById("email").value = document.getElementById("adminEmail").innerHTML;
            } else if(value == "agent") {
                document.getElementById("email").value = document.getElementById("agentEmail").innerHTML;
            } else if(value == "user") {
                document.getElementById("email").value = document.getElementById("userEmail").innerHTML;
            }
            document.getElementById("password").value = "password";
        }
    </script>
@endsection
