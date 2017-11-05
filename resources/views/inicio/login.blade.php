<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="Login de ingreso al sistema de afocat">
    <meta name="author" content="Dante Cortés">
    <link rel="icon" href="{{url('favicon.ico')}}">

    <title>Ingresar al Sistema</title>

    <!-- Bootstrap core CSS -->
    <link href="{{url('bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="../../assets/css/ie10-viewport-bug-workaround.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <style media="screen">
      body {
      padding-top: 40px;
      padding-bottom: 40px;
      background-color: #880202;
      color: #F2FB5F;
      }

      .form-signin {
      max-width: 330px;
      padding: 15px;
      margin: 0 auto;
      }
      .form-signin .form-signin-heading,
      .form-signin .checkbox {
      margin-bottom: 10px;
      }
      .form-signin .checkbox {
      font-weight: normal;
      }
      .form-signin .form-control {
      position: relative;
      height: auto;
      -webkit-box-sizing: border-box;
         -moz-box-sizing: border-box;
              box-sizing: border-box;
      padding: 10px;
      font-size: 16px;
      }
      .form-signin .form-control:focus {
      z-index: 2;
      }
      .form-signin input[type="text"] {
      margin-bottom: -1px;
      border-bottom-right-radius: 0;
      border-bottom-left-radius: 0;
      }
      .form-signin input[type="password"] {
      margin-bottom: 10px;
      border-top-left-radius: 0;
      border-top-right-radius: 0;
      }
      .btn-primary {
        color: #880202;
        background-color: #f2fb5b;
        border-color: #f2fb5f;
      }
      .btn-primary:hover {
        color: #880202;
        background-color: #d1d44b;
        border-color: #d1d44b;
      }
      .btn-primary:active {
        color: #d1d44b;
        background-color: #880202;
        border-color: #880202;
      }
    </style>

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="../../assets/js/ie-emulation-modes-warning.js"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

    <div class="container">
      {{Form::open(['route'=>'login', 'class'=>'form-signin', 'method'=>'post'])}}
        {{ csrf_field() }}
        <h2 class="form-signin-heading">Ingresar</h2>
        <label for="dni" class="sr-only">DNI</label>
        <input type="text" id="persona_dni" class="form-control" name="persona_dni" value="" placeholder="DNI" required autofocus>
        @if ($errors->has('persona_dni'))
            <span class="help-block">
                <strong>{{ $errors->first('persona_dni') }}</strong>
            </span>
        @endif
        <label for="password" class="sr-only">Password</label>
        <input type="password" id="password" class="form-control" name="password" value="" placeholder="Password" required>
        @if ($errors->has('password'))
            <span class="help-block">
                <strong>{{ $errors->first('password') }}</strong>
            </span>
        @endif
        <div class="checkbox">
          <label>
            <input type="checkbox" value="1"> Recordarme
          </label>
        </div>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Entrar</button>
      {{Form::close()}}
    </div> <!-- /container -->


    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="../../assets/js/ie10-viewport-bug-workaround.js"></script>
  </body>
</html>
