@extends('auth.layouts.master-right')
@section('title', 'Login')
@section('content')
    <form class="form-horizontal form-material" id="loginform"  method="POST" action="{{ route('login') }}">
        @csrf
        <a href="javascript:void(0)" class="text-center db"><img src="{{ asset('images/logo-icon.png') }}" alt="Home" /><img src="{{ asset('images/logo-text.png') }}" alt="Home" /></a>
        <div class="form-group m-t-40 login-form-element">
            <div class="col-xs-12">
                <input id="email" placeholder="Username" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autofocus>
                @if ($errors->has('email'))
                    <span class="invalid-feedback">
                          <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        <div class="form-group login-form-element">
            <div class="col-xs-12">
                <input id="password" placeholder="Password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                @if ($errors->has('password'))
                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                @endif
            </div>
        </div>
        <div class="form-group login-form-element">
            <div class="col-md-12">
                <div class="checkbox checkbox-primary pull-left p-t-0">
                    <input id="checkbox-signup" type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label for="checkbox-signup"> Remember me </label>
                </div>
                <a href="javascript:void(0)" id="to-recover" class="text-dark pull-right"><i class="fa fa-lock m-r-5"></i> Forgot pwd?</a> </div>
        </div>
        <div class="form-group text-center m-t-20 login-form-element">
            <div class="col-xs-12">
                <button class="btn btn-info btn-lg btn-block text-uppercase waves-effect waves-light" type="submit">Log In</button>
            </div>
        </div>
        @if(session('nonWorkingHrsMessage'))
            <div class="alert alert-danger text-center">
                <i class="ti-lock" style="font-size: 30px;"></i><br />
                You are not authorized to access during non-working hours, {{ session('nonWorkingHrsMessage') }}
            </div>
            {{ session()->forget('nonWorkingHrsMessage') }}
        @endif

        @if(session('workHrsMessage'))
            <div class="alert alert-danger text-center">
                <i class="ti-lock" style="font-size: 30px;"></i><br />
                You are not authorized to access during not allocated hours, {{ session('workHrsMessage') }}
            </div>
            {{ session()->forget('workHrsMessage') }}
        @endif

        @if(session('nowWrkHrsMessage'))
            <div class="alert alert-danger text-center">
                <i class="ti-lock" style="font-size: 30px;"></i><br />
                You don't have work hours allocated to you, please contact your administrator for work hours allocation.
            </div>
            {{ session()->forget('nowWrkHrsMessage') }}
        @endif
    </form>
    <form class="form-horizontal" id="recoverform" method="POST" action="{{ route('password.email') }}">
        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif
        @csrf
        <div class="form-group login-form-element">
            <div class="col-xs-12">
                <h3>Recover Password</h3>
                <p class="text-muted">Enter your Email and instructions will be sent to you! </p>
            </div>
        </div>
        <div class="form-group login-form-element">
            <div class="col-xs-12">
                <input placeholder="Email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required>

                @if ($errors->has('email'))
                    <span class="invalid-feedback">
                          <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        <div class="form-group text-center m-t-20 login-form-element">
            <div class="col-xs-12">
                <button class="btn btn-primary btn-lg btn-block text-uppercase waves-effect waves-light" type="submit"> Send Password Reset Link</button>
            </div>
        </div>
    </form>
@endsection
