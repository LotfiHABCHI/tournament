@extends('base') 

@section('title', 'Inscription') 

@section('content')

<form method="POST" action="{{route('register.post')}}">
	@csrf @if ($errors->any())
	<div class="alert alert-warning">
		Vous n'avez pas pu être inscrit &#9785;
	</div>
	@endif
	<div class="form-group">
		<label for="email">E-mail</label>
      <input type="email" id="email" name="email" value="{{old('email')}}"
             aria-describedby="email_feedback" class="form-control @error('email') is-invalid @enderror"> 
      @error('email')
      <div id="email_feedback" class="invalid-feedback">
        {{ $message }}
      </div>
      @enderror
    </div>
    <div class="form-group">
      <label for="password">Mot de passe</label>
      <input type="password" id="password" name="password" value="{{old('password')}}"
             aria-describedby="password_feedback" class="form-control @error('password') is-invalid @enderror">  
      @error('password')
      <div id="password_feedback" class="invalid-feedback">
        {{ $message }}
      </div>
      @enderror
    </div>
    <div class="form-group">
      <label for="passwordConfirmation">Confirmation du mot de passe</label>
      <input type="password" id="passwordConfirmation" name="passwordConfirmation" value="{{old('passwordConfirmation')}}"
             aria-describedby="passwordConfirmation_feedback" class="form-control @error('passwordConfirmation') is-invalid @enderror">  
      @error('passwordConfirmation')
      <div id="passwordConfirmation_feedback" class="invalid-feedback">
        {{ $message }}
      </div>
      @enderror
    </div>
    <button type="submit" class="btn btn-primary">Inscription</button>


</form>
@endsection