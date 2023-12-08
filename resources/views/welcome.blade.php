 <!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name') }}</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
                color: #000 !important;
            }

            .title {
                font-size: 28px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 13px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            p > a:focus, p > a:hover, p > a:active, p > a {
                color: #0056b3 !important;
            }

            .m-b-md {
                margin-bottom: 30px;
            }

            p {
                margin-bottom: 0 !important;
                color: #000 !important;
            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">

            <div class="content alert alert-info">
                <div class="title m-b-md">
                    Dear {{ $name }},
                </div>

                <div class="links">
                    <p>Thank you for your interest in using our platform. However, you have to activate your account by acknowledging the mail sent to you.</p>
                    <p>Furthermore, we will encourage you to review our <a href="/terms-and-conditions">terms and condition.</a> document.</p>
                    <p>We wish you a fruitful time as tour round the platform.</p>
                    <p></p>
                    <p><strong>Best Regards,</strong></p>
                    <p><strong> {{ config('app.name') }} Team.</strong></p>
                </div>
            </div>
        </div>
    </body>
</html>
