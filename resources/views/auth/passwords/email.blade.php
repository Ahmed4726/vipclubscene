

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
body {
    background-color: #f8f9fa;
}

/*.card {*/
/*    max-width: 400px;*/
/*    width: 100%;*/
/*    border: none;*/
/*    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);*/
/*}*/

.img-fluid {
    max-width: 200px;
}
   .form-control {
            border: 2px solid #6e5fec;
            border-radius: 8px;
            padding: 10px;
        }
        .form-control::placeholder {
            color: #6c757d;
        }
.btn-primary {
    background-color: #6e5fec;
    border: none;
}
</style>
</head>
<body>
    <div class="container-fluid d-flex align-items-center justify-content-center min-vh-100">
        <div class="p-5" style="max-width:600px">
            <div class="text-center mb-4">
            </div>
            <h2 class="text-center mb-4">Forgot Your Password?</h2>
               @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
            <p class="text-center mb-4 text-muted" >Enter your email address you used when you joined and we will send you instructions to reset your password.</p>
             <p class="text-center mb-4 text-muted">For security reasons we do NOT store your password. So rest assured that we will never send your password via email</p>
            <form method="POST" action="{{ route('password.email') }}">
                @csrf
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required placeholder="Input your email in here">
                      @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                </div>
                <button type="submit" class="btn btn-primary btn-block">Send Reset Link</button>
            </form>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
