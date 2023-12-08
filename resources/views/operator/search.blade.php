@extends('template.master')

@section('content')
    <div class="login-area oaan-page">
        <div class="container pt-200 pb-200">
              <div class="col-lg-9 col-md-9 mx-auto">
                 <div class="login-form wow slideInDown" data-wow-duration="2.5s" data-wow-delay=".25s">
                     <div class="login-header">
                        <img src="{{asset("img/logo/logo.png")}}" alt="Company Logo">
                        <p>Kindly verify your membership with OAAN on our portal to continue with registration.</p>
                     </div>
                     @if ( $errors->any() )
                    <div class="alert alert-danger">
                        <ul>
                            @foreach( $errors->all() as $error )
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                     <form  action="{{ url('/operator/search') }}" method="post">
                        @csrf
                       <div class="row">
                          <div class="col-lg-9 col-md-9 col-12">
                             <div class="form-group">
                                <input name="oaan_number" id="oaan_number" type="text" class="search form-control" placeholder="Search.." value="{{ old('oaan_number') }}">
                             </div>
                          </div>
                          <div class="col-lg-3 col-md-3 col-12">
                             <div class="d-flex align-items-center">
                                <button type="submit" class="theme-btn"><span class="far fa-search"></span>Search</button>
                               </div>
                          </div>
                       </div> 
                     </form>
                 </div>
             </div>
        </div>
    </div>

@endsection