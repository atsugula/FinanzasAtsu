@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    <div class="container position-sticky z-index-sticky top-0">
        <div class="row">
            <div class="col-12">
                @include('layouts.navbars.guest.navbar')
            </div>
        </div>
    </div>
    <br>
    <main class="main-content  mt-0">
        <section>
            <div class="page-header min-vh-100">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-4 col-lg-5 col-md-7 d-flex flex-column mx-lg-0 mx-auto">
                            <div class="card card-plain">
                                <br>
                                <div class="card-header pb-0 text-start">
                                    <h4 class="font-weight-bolder">{{ __('Sign In') }}</h4>
                                    <p class="mb-0">{{ __('Enter your email and password to sign in') }}</p>
                                </div>
                                <div class="card-body">
                                    <form role="form" method="POST" action="{{ route('login.perform') }}">
                                        @csrf
                                        @method('post')
                                        <div class="flex flex-col mb-3">
                                            <input type="email" name="email" class="form-control form-control-lg"
                                                value="{{ old('email') ?? '' }}" aria-label="{{ __('Email') }}"
                                                placeholder="{{ __('Enter your email address') }}">
                                            @error('email')
                                                <p class="text-danger text-xs pt-1"> {{ $message }} </p>
                                            @enderror
                                        </div>
                                        <div class="flex flex-col mb-3">
                                            <input type="password" name="password" class="form-control form-control-lg"
                                                aria-label="{{ __('Password') }}" value=""
                                                placeholder="{{ __('********') }}">
                                            @error('password')
                                                <p class="text-danger text-xs pt-1"> {{ $message }} </p>
                                            @enderror
                                        </div>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" name="remember" type="checkbox" id="rememberMe">
                                            <label class="form-check-label"
                                                for="rememberMe">{{ __('Remember me') }}</label>
                                        </div>
                                        <div class="text-center">
                                            <button type="submit"
                                                class="btn btn-lg btn-primary btn-lg w-100 mt-4 mb-0">{{ __('Sign in') }}</button>
                                        </div>
                                    </form>
                                </div>
                                <div class="card-footer text-center pt-0 px-lg-2 px-1">
                                    <p class="mb-1 text-sm mx-auto">
                                        {{ __('Forgot your password? Reset your password') }}
                                        <a href="{{ route('reset-password') }}"
                                            class="text-primary text-gradient font-weight-bold">{{ __('here') }}</a>
                                    </p>
                                </div>
                                <div class="card-footer text-center pt-0 px-lg-2 px-1">
                                    <p class="mb-4 text-sm mx-auto">
                                        {{ __("Don't have an account?") }}
                                        <a href="{{ route('register') }}"
                                            class="text-primary text-gradient font-weight-bold">{{ __('Sign up') }}</a>
                                    </p>
                                </div>
                            </div>
                        </div>
                        @include('components.message-dashboard')
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
