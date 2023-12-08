@extends('template.master')

@section('content')
<div class="login-area pt-60 pb-100">
    <div class="container">
        <div class="row">
            <div class="col-md-8 mx-auto">
                <div id="app">
                    <div class="row">
                        <router-view/>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- <script src="{{ mix('/js/app.js') }}"></script> --}}
@endsection