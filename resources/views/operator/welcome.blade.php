@extends('template.master')

@section('content')
    <div class="login-area pt-60 pb-100">
        <div class="container">
            <div class="row">
                <div class="col-md-8 mx-auto">
                    <div class="login-form individual-login-form wow slideInDown" data-wow-duration="2.5s" data-wow-delay=".25s">
                        <div class="row">
                        <div class="col-md-12">
                            <div class="login-header custom-login-header">
                                <p><strong>Congratulation {{ $name }}</strong></p>
                                <hr>
                            </div>
                        </div>
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="links">
                                            <p>Thank you for your interest in using our platform. However, you have to activate your account by acknowledging the mail sent to you.</p>
                                            <p>Furthermore, we will encourage you to review our <a href="/terms-and-conditions" target="_blank">terms and condition.</a> document.</p>
                                            <p>We wish you a fruitful time as you tour round the platform.</p>
                                            <p>Kindly verify and activate your account using this <a href="{{url("/operator/verify/token/".$token)}}">link.</p>
                                            <p><strong>Best Regards,</strong></p>
                                            <p><strong> {{ config('app.name') }} Team.</strong></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection