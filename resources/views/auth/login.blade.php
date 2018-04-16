@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-success">
                <div class="panel-heading"><strong>Connexion</strong></div>
                <div class="panel-body">
                @if ($error)
                    <div class="row">
                        <div class="alert alert-danger center-block" style="width: 65%;" role="alert"> <i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
                            <strong>{{ $error }}</strong>
                            <button type="button" class="close" data-dismiss="alert">×</button>
                        </div>
                    </div>
                @endif
                <div class="row">
                    <form class="form-horizontal" role="form" method="POST" action="{{ route('sign_in') }}">
                        
                        <div class="form-group">
                            <label for="email" class="col-md-4 control-label">Adresse courriel</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" value="" required autofocus>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password" class="col-md-4 control-label">Mot de passe</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-8 col-md-offset-4">
                                <button type="submit" class="btn btn-success">
                                    Me connecter 
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
