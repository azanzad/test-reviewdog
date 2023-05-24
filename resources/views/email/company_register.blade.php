<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Hello, world!</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
</head>

<body>
    <table cellpadding="0" cellspacing="0" width="100%" height="100%" style="background-color: #E3E3E3;">
        <tr>
            <td>
                <table width="600" align="center"
                    style="text-align: center; background-color: #fff; color:#1E293B; font-size: 14px; font-family: 'Poppins', sans-serif;">
                    <tr>
                        <td style="padding: 20px 0;border-bottom: 1px solid #EAF0F6;"><img
                                src="{{ asset('assets/img/logo_new.png') }}" style="width: 50px"></td>
                    </tr>
                    <tr>
                        <td style="color: #1E293B; font-size: 20px; font-weight: bold;">Hey,
                            {{ ucfirst($mailData['name']) }}!
                        </td>
                    </tr>

                    <tr>
                        <td style="padding: 20px 100px 20px 100px;">
                            You are registered as a Company in UMA.IO. We welcome you
                            onboard.Your Login details are as below,<br>

                            URL: <a
                                href="{{ Config::get('params.app_url') }}">{{ Config::get('params.app_url') }}</a><br>
                            email: {{ $mailData['email'] }}<br>
                            Password: {{ $mailData['password'] }}<br>
                            Thank You! UMA.IO
                        </td>
                    </tr>
                    <tr>
                        <td style="color: #464E5F; font-size: 12px; padding: 10px 0; border-top: 1px solid #EAF0F6;">
                            Need help ? Visit our <a href="#" style="text-decoration: none; color: #3699FF;">Help
                                Center</a></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
