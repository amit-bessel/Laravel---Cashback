@extends('layout/frontend_template')
@section('content')

  </header>

gfhfghgf
  <section class="become-partner-block">
    <div class="container">
        <div class="become-partner-heading">
          <h1>My Profile</h1>
        </div>
        
      <div class="white-bg">  
        <div class="">
          <div class="col-sm-offset-2 col-sm-8 signin-panel-bg">
      @if(Session::has('failure_message'))
        <p class="alert {{ Session::get('alert-class', 'alert-danger') }}">{{ Session::get('failure_message') }}</p>
      @endif
      
      @if(Session::has('success_message'))
        <p class="alert {{ Session::get('alert-class', 'alert-success') }}">{{ Session::get('success_message') }}</p>
      @endif
      
      <a href="{{ url('user/logout') }}">Logout</a> <br/>
      <a href="{{ url('user/change-password') }}">Change Password</a> <br/>
      <a href="{{ url('user/edit-profile') }}">Edit Profile</a>
          </div>

        </div>
      </div>
        
    </div>
  </section>
  

  
                 
@stop