@extends('user.layout')

@section('body')
<main class="background-auth">
    <div class="row text-center">
        <h1 class="margin-top-xl">Welcome to {{ SITE_NAME }}</h1>
    </div>
    
    <div class="col-sm-4 col-sm-offset-4 margin-top-lg">
        @if ($errors->has())
        <div class="alert alert-danger alert-dismissibl fade in">
            <button type="button" class="close" data-dismiss="alert">
                <span aria-hidden="true">&times;</span>
                <span class="sr-only">Close</span>
            </button>
            @foreach ($errors->all() as $error)
        		<p>{{ $error }}</p>		
        	@endforeach
        </div>
        @endif    
        
        <?php if (isset($alert)) { ?>
        <div class="alert alert-<?php echo $alert['type'];?> alert-dismissibl fade in">
            <button type="button" class="close" data-dismiss="alert">
                <span aria-hidden="true">&times;</span>
                <span class="sr-only">Close</span>
            </button>
            <p>
                <?php echo $alert['msg'];?>
            </p>
        </div>
        <?php } ?>
    </div>    
    
    <form method="POST" action="{{ URL::route('user.auth.doLogin') }}" role="form" class="form-login margin-top-normal">
        @foreach ([
            'email' => 'Your Email *',
            'password' => 'Password *',
        ] as $key => $value)
            <div class="row margin-top-normal">
                <div class="col-sm-4 col-sm-offset-4">
                    <div class="form-group">
                        <label>{{ Form::label($key, $value) }}</label>
                        @if ($key == 'password')
                            {{ Form::password($key, ['class' => 'form-control']) }}                        
                        @else
                            {{ Form::text($key, null, ['class' => 'form-control']) }}
                        @endif
                    </div>
                </div>
            </div>        
        @endforeach   
        
        <div class="row margin-top-normal padding-bottom-xl">
            <div class="col-sm-2 col-sm-offset-5">
                <button class="btn btn-lg btn-primary text-uppercase btn-block">Sign In <span class="glyphicon glyphicon-ok-circle"></span></button>
            </div>
        </div>
    </form>            
</main>
@stop

@stop
