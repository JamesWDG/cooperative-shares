<!doctype html>
<html lang="en-US">

<head>
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
    <title>Contact Form Submission Notification</title>
    <meta name="description" content="Contact Form Submission Notification">
    <style type="text/css">
        a:hover {
            text-decoration: underline !important;
        }
    </style>
</head>

<body marginheight="0" topmargin="0" marginwidth="0"
      style="margin: 0px; background-color: #f2f3f8;" leftmargin="0">
    <!--100% body table-->
    <table cellspacing="0" border="0" cellpadding="0" width="100%" bgcolor="#f2f3f8"
           style="@import url(https://fonts.googleapis.com/css?family=Rubik:300,400,500,700|Open+Sans:300,400,600,700); font-family: 'Open Sans', sans-serif;">
        <tr>
            <td>
                <table style="background-color: #f2f3f8; max-width:670px; margin:0 auto;"
                       width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
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
                                        <h6 style="color:#1e1e2d; font-weight:500; margin:0;
                                                   font-size:20px;font-family:'Rubik',sans-serif;">
                                            New Contact Form Submission
                                        </h6>
                                        <p style="color:#455056; font-size:15px;line-height:24px; margin:0;">
                                            You have received a new submission from the cooperative shares website contact form.
                                            Below are the details:
                                        </p>

                                        <table border="1" cellpadding="10" cellspacing="0" width="100%"
                                               style="margin-top:20px; text-align:left; font-size:15px; color:#455056;">
                                            <tr>
                                                <th style="padding:10px; background-color:#f2f3f8;">Full Name</th>
                                                <td style="padding:10px;">{{ $data['form_data']['full_name'] ?? 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <th style="padding:10px; background-color:#f2f3f8;">Email</th>
                                                <td style="padding:10px;">{{ $data['form_data']['email'] ?? 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <th style="padding:10px; background-color:#f2f3f8;">Phone Number</th>
                                                <td style="padding:10px;">{{ $data['form_data']['phone_number'] ?? 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <th style="padding:10px; background-color:#f2f3f8;">Service</th>
                                                <td style="padding:10px;">{{ $data['form_data']['service'] ?? 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <th style="padding:10px; background-color:#f2f3f8;">Message</th>
                                                <td style="padding:10px;">
                                                    {{ $data['form_data']['message'] ?? 'N/A' }}
                                                </td>
                                            </tr>
                                        </table>
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
    <!--/100% body table-->
</body>

</html>
