@extends('layouts.app')

@section('content')
@section('title', 'login')
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
                    <form id="form_sign_in" class="form-horizontal" role="form" method="POST" action="{{ route('sign_in') }}">
                        
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
                        
                        <div class="col-md-6 col-md-offset-4 text-right" >        
                            <font id="email_reset" color="white" class="txt">Mot de passe oublié?</font>
                        </div>

                        <div class="form-group" >
                            <div class="col-md-6 col-md-offset-3" style="margin-top:50px;">
                                <button type="submit" class="btn btn-round-lg btn-lg center-block" >
                                    <font color="white">Me connecter</font> 
                                </button>
                            </div>
                        </div>
			<div class="row text-center">
                        <p style="font-size: 13px; margin: 40px 0px 10px; line-height: 16px;">
                            <font color="white">Le Passeport Shopping vous permet une connexion unique à travers toutes les boutiques de notre réseau de centres d’achat en ligne incluant <a href="http://monpanier.ca" target="_blank"></a>MonPanier.ca</font>
                        </p>
                        <p style="font-size: 13px; margin: 17px 0px 10px; line-height: 16px;" >
                            <font color="white">Il vous permet d’effectuer vos transactions plus rapidement, en toute sécurité, et vous évite les dizaines de comptes et mots de passe!</font>
                        </p>
                        <p style="font-size: 16px; margin: 30px 0px 10px; line-height: 16px;" >
                            <font color="white"><strong>Pas de Passeport Shopping?</strong></font>
                        </p>
                        <p style="font-size: 13px; margin: 20px 0px 10px; line-height: 16px;" >
                            <font color="white">Il suffit de vous créer un compte client chez un marchand de notre réseau. Votre Passeport Shopping sera créé automatiquement et une confirmation vous sera envoyée par courriel.</font>
                        </p>
                    </div>
                </div>

                    </form>
                </div>
		
                <form id="form_email_reset" class="form-horizontal" role="form" method="POST" action="{{ route('email_reset_link') }}" style="display:none">
                        <div class="form-group center-block">
                            <label for="password" class="col-md-4 control-label"><font color="white">Adresse courriel</font></label>

                            <div class="col-md-6">
                                <input id="email_reset" type="email" class="form-control" name="email_reset" required>
                            </div>
                        </div>

                        <div class="col-md-6 col-md-offset-4 text-right" >        
                            <font id="sign_in_back" color="white" class="txt"><i class="fa fa-arrow-left"></i></font>
                        </div>
                        
                        <div class="form-group" >
                            <div class="col-md-6 col-md-offset-3" style="margin-top:50px;">
                                <button type="submit" class="btn btn-round-lg btn-lg center-block" >
                                    <font color="white">Envoyer</font> 
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    
    $( "#email_reset" ).click(function() {
      $("#form_sign_in").hide( "slow");
      $("#form_email_reset").show( "slow");
    });

    $( "#sign_in_back" ).click(function() {
      $("#form_email_reset").hide( "slow");
      $("#form_sign_in").show( "slow");
    });

</script>
@endsection
