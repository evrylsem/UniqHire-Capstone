@extends('layout')
@section('page-title', 'Forgot Password')
@section('auth-content')
<div class="container mt-5">
    <div class="row mt-2 mb-2">
        <div class="col default-text header-texts border-bottom">
            <a href="{{ route('login-page') }}" class="m-1"><svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" style="fill: rgba(4, 176, 0, 1);">
                    <path d="M21 11H6.414l5.293-5.293-1.414-1.414L2.586 12l7.707 7.707 1.414-1.414L6.414 13H21z"></path>
                </svg></a>
            Reset Password
        </div>
    </div>
    <form action="">
        <div class="form-floating mb-3">
            <input type="email" class="form-control" id="floatingInput" name="email" placeholder="Email">
            <label for="floatingInput">Email Address</label>
            @error('email')
            <span class="error-msg">{{ $message }}</span>
            @enderror
        </div>
        <div class="row">
            <div class="col">
                <div class="form-floating mb-3">
                    <input type="password" class="form-control" id="floatingPassword" name="password" required placeholder="Password">
                    <label for="floatingPassword">Password</label>
                    @error('password')
                    <span class="error-msg">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="col">
                <div class="form-floating mb-3">
                    <input type="password" class="form-control" id="floatingInput" name="password_confirmation" required placeholder="Confirm Password">
                    <label for="floatingPassword">Confirm Password</label>
                </div>
            </div>
        </div>
        <div class="d-flex justify-content-center align-items-center mb-3">
            <button type="reset" class="m-2 border-0 bg-transparent clear-btn">
                Clear
            </button>
            <button type="submit" class="m-2 border-0 bg-text" style="font-weight:bold; height:3.5rem; width: 10rem;">
                Register
            </button>
        </div>
    </form>
</div>

@endsection