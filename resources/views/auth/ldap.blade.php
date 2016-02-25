@extends('app')

<style>
    form.login-form{
        border:1px solid lightblue;
        padding:10px;
    }
</style>

@section('content')

  <div class="container">

    <form action="login" method="POST" class="col-lg-4 col-lg-offset-4 login-form">
      <input type="hidden" name="_token" value="{{csrf_token()}}">
      <div class="input-group">
        <input type="text" class="form-control" placeholder="username" name="username" aria-describedby="basic-addon2">
        <span class="input-group-addon" id="basic-addon2">@kemenkeu.go.id</span>
      </div>
      <br>
      <input type="password" class="form-control" name="password" placeholder="password">
      <br/>
      @if(\Request::get('ggl'))
        <input type="hidden" name="ggl" value={{\Request::get('ggl')}}/>
      @endif
      <input type="submit" value="Masuk" class="btn btn-primary btn-block"/>
    </form>

  </div>

@stop
