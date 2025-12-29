<!DOCTYPE html>
<html>

<head>
    <title>CITPL - Password Reset</title>
</head>

<body>


    <table bgcolor="#F2F2F2" width="100%" border="0" cellspacing="0" cellpadding="0">
        <tbody>
            <tr>
                <td>
                    <table align="center" width="100%" border="0" cellspacing="0" cellpadding="0"
                        style="background-color:#F2F2F2;max-width:670px;margin:0 auto;">
                        <tbody>
                            <tr>
                                <td style="height:80px;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td style="text-align:center;"><span title="CK Ticketing Tool"
                                        style="color:#3eb9f3;font-size:32px;font-weight:800;margin:0;"><img
                                            data-imagetype="External" src="https://citpl_alumni.cavinkare.in/assets/img/logo.png"
                                            alt="CK Ticketing Tool" title="CK Ticketing Tool"></span>
                                </td>
                            </tr>
                            <tr>
                                <td style="height:40px;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td>
                                    <table align="center" width="95%" border="0" cellspacing="0" cellpadding="0"
                                        style="text-align:center;background-color:white;border-radius:3px;max-width:670px;">
                                        <tbody>
                                            <tr>
                                                <td style="height:40px;">&nbsp;</td>
                                            </tr>
                                            <tr>
                                                <td style="padding:0 15px;">
                                                    <h1 style="color:#3eb9f3;font-size:30px;font-weight:400;margin:0;">
                                                        {{ $details['body_content1'] }}</h1>
                                                    <span
                                                        style="vertical-align:middle;display:inline-block;width:100px;margin:29px 0 26px 0;border-bottom:1px solid #CECECE;"></span>
                                                    <p style="font-size:15px;margin:0;line-height:24px;">
                                                        {{ $details['body_content2'] }}</p>
                                                    <a href="https://alumni.hepl.com/index.php/forgot_pass?mail={{ $details['mailid'] }}"
                                                        target="_blank" rel="noopener noreferrer"
                                                        data-auth="NotApplicable"
                                                        style="color:white;font-size:14px;font-weight:500;text-transform:uppercase;background-color:#3eb9f3;display:inline-block;border-radius:3px;margin-top:35px;padding:10px 12px;"
                                                        data-linkindex="0">Reset
                                                        Password</a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="height:40px;">&nbsp;</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td style="height:20px;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td style="text-align:center;">
                                    <p style="font-size:14px;margin:0;line-height:18px;">{{ $details['body_content3'] }}
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <td style="height:80px;">&nbsp;</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>


    <!-- <img src="https://citpl_alumni.cavinkare.in/assets/img/logo.png" alt="" style="width:90px;">
    <p>Thank you</p>
    <b>The HEPL Team</b> -->
</body>

</html>
