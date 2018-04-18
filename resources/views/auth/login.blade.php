@extends('layouts.app')

@section('content')
@section('title', 'login')
<style>
@media only screen and (min-width: 768px) {
    h1{
        font-size: 50px;
    }
}

@media only screen and (max-width: 767px) {
    h1{
        font-size: 8vw;
    }
}

@media only screen and (max-width: 767px) and (orientation: portrait) {
    h1{
        font-size: 8vw;
    }
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
	-webkit-appearance: none;
    }
</style>
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel-transparent">
                <div class="panel-heading">
                    <div class="row  text-center">
                            <i class="fas fa-key" style="color: white;font-size: 48px;"></i>
                    </div>
                    <div class="row text-center">
                        <h1 >
                            <font color="white">Connectez-vous à votre <br>
                                <bold style="font-weight: bold; padding-right: 15px;">Passeport.shopping</bold> 
                            </font>
                        </h1>
                    </div>
                </div>
                <div class="panel-body">
                @if ($error)
                    <div class="row">
                        <div class="alert center-block" style="width: 100%;" role="alert"> <i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
                            <strong style="color: #663aad !important">{{ $error }}</strong>
                            <button type="button" class="close" data-dismiss="alert">×</button>
                        </div>
                    </div>
                @endif
                <div class="row">
                    <form class="form-horizontal" role="form" method="POST" action="{{ route('sign_in') }}">
                        
                        <div class="form-group center-block">
                            <label for="email" class="col-md-4 control-label"><font color="white">Adresse courriel</font></label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" value="" required autofocus>
                            </div>
                        </div>

                        <div class="form-group center-block">
                            <label for="password" class="col-md-4 control-label"><font color="white">Mot de passe</font></label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password" required>
                            </div>
                        </div>

                        <div class="form-group" >
                            <div class="col-md-6 col-md-offset-3" style="margin-top:50px;">
                                <button type="submit" class="btn btn-round-lg btn-lg center-block" >
                                    <font color="white">Me connecter</font> 
                                </button>
                                <!--
                                <a class="btn btn-link" href="">
                                    Mot de passe oublié?
                                </a>
                                -->
                            </div>
                        </div>
                    </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
