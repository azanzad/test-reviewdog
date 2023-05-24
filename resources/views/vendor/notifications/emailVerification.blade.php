<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Hello, world!</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
</head>

<body>

    <table class="" style="background-color: #D5D9E2; width: 100%;" align="center">
        <tr>
            <td>
                <table align="center" border="0" cellpadding="0" cellspacing="0" height="auto" style="border-collapse:collapse;background-color: #fff; width: 600px;font-family: 'Poppins', sans-serif;">
                    <tbody>
                        <tr>
                            <td align="center" valign="center" style="text-align:center; padding-bottom: 10px">
                                <div style="text-align:center; margin:0 15px 34px 15px">
                                    <div style="margin-bottom: 10px; padding: 20px; border-bottom: 1px solid #E1E1E1;">
                                        <a href="#" rel="noopener" target="_blank">
                                            <img alt="Logo" src="{{ asset('assets/img/logo_new.png') }}" style="width:50px" />
                                        </a>
                                    </div>
                                    <h2 style="font-size: 28px; font-weight: 700;font-family: 'Poppins', sans-serif;text-align:'center !important'">Email Verification</h2>
                                    <p style="width: 250px; margin: auto; margin-bottom: 30px; font-size: 14px;font-family: 'Poppins', sans-serif;">Please click the button below to verify your email address</p>
                                    <a href='{{$url}}' target="_blank" style="background-color:#A28FC7;
                                    border-radius:6px;display:inline-block; padding:11px 19px; box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
                                    color: #fff; font-size: 12px; font-weight:700; border-radius: 100px; text-decoration: none; margin-bottom:30px">
                                        Verify
                                    </a>
                                    {{-- <p style="width: 250px; margin: auto; margin-bottom: 30px; font-size: 14px;font-family: 'Poppins', sans-serif;">This email verify link will expire in 60 minutes.</p> --}}
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
