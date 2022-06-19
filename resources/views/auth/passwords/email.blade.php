@extends('layouts.app')

@section('title', 'Forgot Password')

@section('content')
    <div class="row justify-content-center">
        <div class="col-sm-5">
            <div class="card p-0">
                <div class="card-header border-bottom text-white">Forgot Password</div>
                <div class="p-20">

                    @if (session('status'))
                        <div class="alert alert-success filled mb-15">
                            <div class="row justify-content-center">
                                <div class="col-10 text-center">{{ session('status') }}</div>
                            </div>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf
                        <div class="form-group @error('email') is-invalid @enderror">
                            @error('email')
                                <div class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                            <label for="email" class="required">Email</label>
                            <input type="email" name="email" class="form-control" placeholder="Email" value="{{ old('email') }}" required autocomplete="email">
                        </div>
                        <input class="btn btn-success btn-block" type="submit" value="Send Password Reset Link">
                    </form>

                        <script type="text/javascript">

                            @if(count($errors))
                                @foreach($errors->all() as $error)
                                    toastDangerAlert("Error", "<?php echo $error; ?>");
                                @endforeach
                            @endif

                            function toastDangerAlert(title, content) {
                                halfmoon.initStickyAlert({
                                    content: content,
                                    title: title,
                                    alertType: "alert-danger",
                                    fillType: "filled"
                                });
                            }
                        </script>

                    <!--
                    <div class="section-div my-10">Don't know your username?</div>
                    <button class="btn btn-primary btn-block">Username Reminder</button>
                    -->

                </div>
            </div>
        </div>
    </div>
@endsection