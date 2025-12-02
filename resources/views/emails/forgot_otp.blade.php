<!doctype html>
<html lang="en-US">
<head>
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
    <title>{{ $data['subject'] ?? 'Verify Your E-mail Address' }}</title>
    <meta name="description" content="Verify Your E-mail Address">
    <style type="text/css">
        a:hover {
            text-decoration: underline !important;
        }
    </style>
</head>

<body marginheight="0" topmargin="0" marginwidth="0"
      style="margin:0; background-color:#f2f3f8;" leftmargin="0">
    <table cellspacing="0" border="0" cellpadding="0" width="100%" bgcolor="#f2f3f8"
           style="@import url('https://fonts.googleapis.com/css?family=Rubik:300,400,500,700|Open+Sans:300,400,600,700'); font-family:'Open Sans',sans-serif;">
        <tr>
            <td>
                <table style="background-color:#f2f3f8; max-width:670px; margin:0 auto;" width="100%" border="0"
                       align="center" cellpadding="0" cellspacing="0">
                    <tr>
                        <td style="height:20px;">&nbsp;</td>
                    </tr>
                    <tr>
                        <td>
                            <table width="95%" border="0" align="center" cellpadding="0" cellspacing="0"
                                   style="max-width:670px;background:#fff; border-radius:3px; text-align:center;
                                          -webkit-box-shadow:0 6px 18px 0 rgba(0,0,0,.06);
                                          -moz-box-shadow:0 6px 18px 0 rgba(0,0,0,.06);
                                          box-shadow:0 6px 18px 0 rgba(0,0,0,.06);">
                                <tr>
                                    <td style="height:40px;">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td style="padding:0 35px;">
                                        <h6 style="color:#1e1e2d; font-weight:500; margin:0;font-size:20px;
                                                   font-family:'Rubik',sans-serif;">
                                            {{ $data['heading'] ?? 'Email Verification' }}
                                        </h6>

                                        <p style="color:#455056; font-size:15px;line-height:24px; margin:0;">
                                            {{ $data['line1'] ?? "You're almost ready to get started." }}
                                        </p>

                                        <p style="color:#455056; font-size:15px;line-height:24px; margin:0;">
                                            {{ $data['line2'] ?? 'Use the OTP below to verify your email and reset your password.' }}
                                        </p>

                                        <p style="color:#1e1e2d; font-size:24px; font-weight:700; margin-top:20px;">
                                            Your OTP is:
                                            <span style="letter-spacing:3px;">
                                                {{ $data['otp'] ?? '' }}
                                            </span>
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="height:40px;">&nbsp;</td>
                                </tr>
                            </table>
                        </td>
                    <tr>
                        <td style="height:20px;">&nbsp;</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
