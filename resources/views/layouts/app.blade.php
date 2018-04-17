<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <title>@yield('title') | passeport  shopping</title>
<script defer src="https://use.fontawesome.com/releases/v5.0.10/js/all.js" integrity="sha384-slN8GvtUJGnv6ca26v8EzVaR9DC58QEwsIk9q1QXdCU8Yu8ck/tL/5szYlBbqmS+" crossorigin="anonymous"></script>
<link href="https://fonts.googleapis.com/css?family=Comfortaa" rel="stylesheet">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <!-- Scripts -->
  <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
  <!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

<style type="text/css">
    body{
        background: url('../images/ps_fond.png') no-repeat center center fixed;
        -webkit-background-size: cover;
        -moz-background-size: cover;
        -o-background-size: cover;
        background-size: cover;
        margin-top: 12%;
        font-family: 'comfortaa' , cursive;
        font-size: 20px;    
    }

    #bg{
        background:url('../images/ps_fond.png') no-repeat center center;
        height: 500px;
    }

    .panel-transparent {
        background: none;
    }

    .panel-transparent .panel-heading{
        background: none !important;
    }

    .panel-transparent .panel-body{
        background: none !important;
    }

    input[type="email"], input[type="password"]
    {
        color : white;
        font-family: 'comfortaa' , cursive;
        font-size: 20px;    
        background: transparent !important;
        border: none !important;
        -webkit-box-shadow: none !important;
        -moz-box-shadow: none !important;
        box-shadow: 0 3px 2px -2px white !important;
    }

    .btn-round-lg{
        border-radius: 22.5px;
        background-color: #663aad !important;

    }
    
    .btn-round{
        border-radius: 17px;
    }
    
    .fa-w-18{
        display: none !important;
    }

</style>

</head>
<body>
    <div id="app">
        <div class="container-fluid">
            @yield('content')
        </div>
    </div>
</body>
</html>
