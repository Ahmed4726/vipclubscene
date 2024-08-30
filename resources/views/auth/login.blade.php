<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>VIP Club Scene - Enter</title>
  <link rel="stylesheet" href="TestNewForm/style.css">
</head>
<body>
<br>
<br>
<div class="cont">
    <div class="form sign-in" id="signInForm">
        <h2>ENTER</h2>
        <form method="POST" action="{{ route('login') }}">
            @csrf
        <label>
            <span>Email</span>
            <input type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autofocus />
               @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
        </label>
        <label>
            <span>Password</span>
            <input type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required  />
             @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
        </label>
         <label class="form-check-label remember-me"><input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>@lang('auth.rememberMe')</label>

        <button type="submit" class="submit">Sign In</button>
        </form>
          <button class="forgot-pass" onclick="showForgotPassForm()">Forgot password?</button>
    </div>
    
    <div class="forgot-pass-form form" id="forgotPassForm">
        <h2>Forgot Password</h2>
          @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf
        <label>
            <span>Email</span>
            <input type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required />
        </label>
        <button type="submit" class="submit">Reset Password</button>
         @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
        </form>
        
        <button class="forgot-pass" onclick="showSignInForm()">Back to Sign In</button>
    </div>
    <div class="sub-cont">
        <div class="img">
            <div class="img__text m--up">
                <img src="TestNewForm/img/vip club scene logo.png" alt="Sign up image" />
            </div>
            <div class="img__text m--in">
                <img src="TestNewForm/img/vip club scene logo.png" alt="Sign up image" />
            </div>
            <div class="img__btn">
                <span class="m--up">Sign Up</span>
                <span class="m--in">Sign In</span>
            </div>
        </div>
        <div class="form sign-up">
            <h2>Create Your Account</h2>
            <form method="POST" action="{{ route('register') }}">
                @csrf
            <label>
                <span>Name</span>
                <input type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" required autofocus />
                   @if ($errors->has('name'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
            </label>
            <label>
                <span>Email</span>
                <input type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required />
                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
            </label>
            <label>
                <span>Password</span>
                <input type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required />
                 @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
            </label>
            <label>
                <span>Confirm Password</span>
                <input type="password" class="form-control" name="password_confirmation" required />
            </label>
            <button type="submit" class="submit">Sign Up</button>
            </form>
        </div>
    </div>
</div>

<script>
    function showForgotPassForm() {
        document.getElementById('signInForm').style.display = 'none';
        document.getElementById('forgotPassForm').style.display = 'block';
    }

    function showSignInForm() {
        document.getElementById('forgotPassForm').style.display = 'none';
        document.getElementById('signInForm').style.display = 'block';
    }

    // Initialize the display states
    document.addEventListener("DOMContentLoaded", function() {
        document.getElementById('forgotPassForm').style.display = 'none';
    });
</script>
<script>
    document.querySelector('.img__btn').addEventListener('click', function() {
        document.querySelector('.cont').classList.toggle('s--signup');
    });
</script>

</body>
</html>
