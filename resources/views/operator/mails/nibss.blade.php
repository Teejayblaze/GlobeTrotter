<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sensitive Data</title>
</head>
<body>
    <h3>Hi Nibss,</h3>
    <p>Kindly find below the encryption/decryption initialization vector (IV) key and Secret for data cryptography.</p>
    <table>
        <tbody>
            <tr>
                <th>IV Key</th>
                <td>{{$iv}}</td>
            </tr>
            <tr>
                <th>Secret</th>
                <td>{{$key}}</td>
            </tr>
        </tbody>
    </table>
    <p>Kind Regards,</p>
    <p><b>{{env('APP_NAME')}}</b></p>
</body>
</html>