<x-app-layout class="authentication d-flex justify-content-center align-items-center flex-column">
    <x-slot name="title">Log in</x-slot>
    <a href="{{ route('index') }}" class="btn btn-secondary btn-sm position-absolute top-0 start-0 mt-2 ms-2"><i class="bi bi-arrow-return-left me-2"></i>Return Home</a>

    <div class="row justify-content-center">
        <div class="col-md-5">
            <img src="{{ asset('img/branding/light_long.svg') }}" class="img-fluid mb-5 d-none d-md-block" />
            <img src="{{ asset('img/branding/light_icon.svg') }}" class="mb-2 d-block d-md-none ms-auto me-auto" width="120" />
        </div>
    </div>

    <div class="row justify-content-center mt-5">
        <div class="col-md-8 col-lg-6 col-xl-5 col-xxl-4 position-relative">
            <img src="{{ asset('img/avatar/WaveSticker.png') }}" class="position-absolute sticker d-none d-md-block" />
            <div class="card position-relative">
                <div class="card-body pb-2">
                    <h4 class="text-center mb-3">Log in to your account</h4>
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <label class="text-muted fw-bold text-uppercase text-xs">
                            Username:
                        </label>
                        <div class="input-parent has-icon">
                            <i class="bi bi-person-circle"></i>
                            <input type="text" name="username" class="form-control" placeholder="Username" required="required" value="{{ old('username') }}" />
                        </div>
                        <div class="mb-2"></div>
                        <label class="text-muted fw-bold text-uppercase text-xs">
                            Password:
                        </label>
                        <div class="input-parent has-icon">
                            <i class="bi bi-lock-fill"></i>
                            <input type="password" name="password" class="form-control" placeholder="Password" required="required" />
                        </div>
                        <div class="mb-2"></div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label" for="remember">
                                Remember me?
                            </label>
                        </div>
                        <div class="mb-2"></div>
                        <button class="btn btn-primary d-block w-100 text-center mb-1" type="submit">
                            Log In
                        </button>
                    </form>
                    <div class="text-center">
                        <a href="{{ route('password.request') }}" class="text-small fw-semibold mb-2">Forgot Username or Password?</a>
                    </div>
                    <div class="divider">OR</div>
                    <a href="{{ route('register') }}" class="btn btn-success d-block w-100 text-center mb-2">
                        Create an Account
                    </a>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
