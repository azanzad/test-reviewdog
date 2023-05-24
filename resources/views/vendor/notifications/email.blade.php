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
                        <td style="padding: 20px 0;border-bottom: 1px solid #EAF0F6;">
                            <img alt="Logo" src="{{ asset('assets/img/logo_new.png') }}" style="width:50px" />
                        </td>
                    </tr>

                    <tr>
                        <td>
                            Reset your password
                        </td>
                    </tr>

                    <tr>
                        <td style="padding: 20px 100px 20px 100px;">
                            @foreach ($introLines as $line)
                                {{ $line }}
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <a href='{{$actionUrl}}' target="_blank" style="background-color:#A28FC7;
                                border-radius:6px;display:inline-block; padding:11px 19px; box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
                                color: #fff; font-size: 12px; font-weight:700; border-radius: 100px; text-decoration: none; margin-bottom:30px">
                                    Reset Password
                            </a>
                        </td>
                    </tr>
                    @foreach ($outroLines as $line)
                        <tr>
                            <td>

                                {{ $line }}

                            </td>
                        </tr>
                    @endforeach

                </table>
            </td>
        </tr>
    </table>
</body>

</html>
