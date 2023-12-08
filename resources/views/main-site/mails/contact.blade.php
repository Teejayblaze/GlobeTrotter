<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Email</title>
</head>
<body>
    <div class="container">
        @if ($adminCopy)
        <h3>Hi Administrator</h3>
        <p>
            {{$to}} has reached out with the following complaints.
        </p>

        <p>Name: {{$to}}</p>
        <p>Email: {{$email}}</p>
        <p>Subject: {{$subject}}</p>
        <p>Message: {{$message}}</p>

        <p>Best Regards,</p>
        <p>{{config('app.name')}} Team.</p>
        @else
        <h3>Dear {{$to}}</h3>
        <p>
            We are glad you reached out kindly be rest assured that we are treating your request and you will be responded to in the next 24 hours.
        </p>
        <p>Best Regards,</p>
        <p>{{config('app.name')}} Team.</p>
        @endif
    </div>
</body>
</html>