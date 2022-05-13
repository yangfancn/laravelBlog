<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="x-csrf-token" content="{{ csrf_token() }}">
    <title>Session Create</title>
    <link rel="stylesheet" href="{{ mix('admin/css/app.css') }}">
</head>
<body>
<div class="login-page">
    <div class="login-box">
        <!-- /.login-logo -->
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <a href="javascript:;" class="h1"><b>Manager</b></a>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.login') }}" method="post" class="axios-form">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" name="name" placeholder="Username" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" class="form-control" name="password" placeholder="Password" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-8">
                            <div class="icheck-primary">
                                <input type="checkbox" id="remember" name="remember">
                                <label for="remember">
                                    Remember Me
                                </label>
                            </div>
                        </div>
                        <!-- /.col -->
                        <div class="col-4">
                            <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
</div>
<script src="{{ mix('admin/js/manifest.js') }}"></script>
<script src="{{ mix('admin/js/vendor.js') }}"></script>
<script src="{{ mix('admin/js/app.js') }}"></script>
<script>
    $('.axios-form').on('submit', function (e) {
        e.preventDefault();
        axios({
            url: $(this).attr('action'),
            data: $(this).serialize(),
            method: 'POST'
        }).then(function (response) {
            msg(response.data.message, 'success', 1800);
            setTimeout(function () {
                window.location.href = response.data.redirectTo;
            }, 1800)
        })
        return false;
    })
</script>
</body>
</html>
