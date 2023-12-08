@extends('template.master')

@section('content')
    <style>
        select.form-control:not([size]):not([multiple]) {
            height: calc(2.9rem + 5px);
        }
    </style>

    <div class="container" style="box-sizing: ''" id="app">
        <div class="row text-center ">
            <div class="col-md-12">
                <br /><br />
                <h2 style="color:#ffffff; font-weight: bolder">Welcome to {{ config('app.name') }}</h2>
                <h5>( Register yourself to get access )</h5>
                <br />
            </div>
        </div>
        <div class="row ">
            <router-view/>   
        </div>
    </div>
@endsection