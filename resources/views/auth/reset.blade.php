<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <title>Reset Password </title>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta name="robots" content="noindex, nofollow">
    <meta name="googlebot" content="noindex, nofollow">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <link rel="stylesheet" type="text/css" href="/css/result-light.css">

    <link rel="stylesheet" type="text/css"
          href="//netdna.bootstrapcdn.com/bootswatch/3.1.1/superhero/bootstrap.min.css">
    <script type="text/javascript" src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
    <style type="text/css">
        body {
            padding-top: 70px;
        }
    </style>
</head>
<body>
<div class="container">

    <div>
        @if(!$success)
            <div class="row">
                <div class="alert alert-danger">
                    <strong>ERROR!!!</strong> The reset token is invalid or expired. Please try to submit again.
                </div>
            </div>
        @elseif($reset)
            <div class="alert alert-success">
                <strong>SUCCESS!!!</strong> Your password is successful changed, you can login in the app with new
                password.
            </div>
        @else
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <div class="well well-sm">
                        <form id="reset-form" class="form-horizontal" action="reset" method="post">
                            {{ csrf_field() }}
                            <input name="token" type="hidden" value="{{$token}}">

                            <fieldset>
                                <legend class="text-center">Reset Password</legend>
                                <div id="success-container" class="text-center" style="display:none;">
                                    <p class="lead text-success"><span class="glyphicon glyphicon-ok"></span></p>
                                    <p class="lead">Your message has been sent successfully!</p>
                                </div>

                                <div id="form-container">

                                    <!-- Password input-->
                                    <div class="form-group">
                                        <label class="col-md-3 control-label" for="name">New Password</label>
                                        <div class="col-md-9">
                                            <input name="password" type="password" placeholder="Your new password"
                                                   class="form-control">
                                        </div>
                                    </div>

                                    <!-- Confirmation input-->
                                    <div class="form-group">
                                        <label class="col-md-3 control-label" for="email">Pasword Confirmation</label>
                                        <div class="col-md-9">
                                            <input name="confirm_password" type="password"
                                                   placeholder="Re-enter your new password" class="form-control">
                                        </div>
                                    </div>

                                    <!-- Form actions -->
                                    <div class="form-group">
                                        <div class="col-md-12 text-right" id="spin-area">
                                            <button type="submit" class="btn btn-primary btn-lg">Submit</button>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
    </div>
    @endif

</div>
</body>
</html>
