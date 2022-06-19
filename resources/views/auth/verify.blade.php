@extends('layouts.app')

@section('title', 'Verify Your Email')

@section('content')
    <div class="row justify-content-center">
        <div class="col-sm-6">
            <div class="card p-0">
                <div class="card-header border-bottom text-white">{{ __('Verify Your Email Address') }}</div>

                <div class="card-body p-15">
                    @if (session('resent'))
                        <div class="alert alert-success filled mb-15">
                            <div class="row justify-content-center">
                                <div class="col-10 text-center">A fresh verification link has been sent to your email address.</div>
                            </div>
                        </div>
                    @endif

                    {{ __('Before proceeding, please check your email for a verification link.') }}
                    {{ __('If you did not receive the email') }},
                    <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                        @csrf
                        <button type="submit" class="btn btn-link p-0 m-0 align-baseline">{{ __('click here to request another') }}</button>.
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
