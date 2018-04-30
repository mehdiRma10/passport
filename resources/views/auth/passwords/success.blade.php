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
                <div class="row text-center">
                    @if ($message)
                        <div class="row">
                            <div class="alert center-block" style="width: 100%;" role="alert"> <i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
                                <strong style="color: #663aad !important">{{ $message }}</strong>
                            </div>
                        </div>
                    @endif
                </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript"></script>>
@endsection