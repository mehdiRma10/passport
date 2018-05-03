@extends('layouts.app')

@section('content')
@section('title', 'reset pw')
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
                <div class="row">
                    <form  class="form-horizontal" role="form" method="POST" action="{{ route('reset_pass') }}">

                        <div class="form-group required center-block">
                            <label for="password" class="col-md-4 control-label"><font color="white">Nouveau mot de passe</font></label>

                            <div class="col-md-6">
                                <input id="password_1" type="password" class="form-control" name="password_1" required>
                            </div>
                        </div>
                        @if ($error_1)
                            <div class="row">
                                <div class="alert center-block" style="width: 100%;" role="alert"> <i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
                                    <strong style="color: #663aad !important">{{ $error_1 }}</strong>
                                    <button type="button" class="close" data-dismiss="alert">×</button>
                                </div>
                            </div>
                        @endif

                        <div class="form-group required center-block">
                            <label for="password" class="col-md-4 control-label"><font color="white">Confirmer le  mot de passe</font></label>

                            <div class="col-md-6">
                                <input id="password_2" type="password" class="form-control" name="password_2" required>
                            </div>
                        </div>
                        @if ($error_2)
                            <div class="row">
                                <div class="alert center-block" style="width: 100%;" role="alert"> <i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
                                    <strong style="color: #663aad !important">{{ $error_2 }}</strong>
                                    <button type="button" class="close" data-dismiss="alert">×</button>
                                </div>
                            </div>
                        @endif
                        
                        <div class="form-group" >
                            <div class="col-md-6 col-md-offset-3" style="margin-top:50px;">
                                <button type="submit" class="btn btn-round-lg btn-lg center-block" >
                                    <font color="white">Confirmer</font> 
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript"></script>>
@endsection