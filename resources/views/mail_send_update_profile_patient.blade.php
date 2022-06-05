<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <title>Approval Status</title>

    <style type="text/css" rel="stylesheet" media="all">
        /* Base ------------------------------ */

        *:not(br):not(tr):not(html) {
            font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
            box-sizing: border-box;
        }

        body {
            width: 100% !important;
            height: 100%;
            margin: 0;
            line-height: 1.4;
            background-color: #F2F4F6;
            color: #74787E;
            -webkit-text-size-adjust: none;
        }

        p,
        ul,
        ol,
        blockquote {
            line-height: 1.4;
            text-align: left;
        }

        a {
            color: #3869D4;
        }

        a img {
            border: none;
        }

        td {
            word-break: break-word;
        }

        /* Layout ------------------------------ */

        .email-wrapper {
            width: 100%;
            margin: 0;
            padding: 0;
            -premailer-width: 100%;
            -premailer-cellpadding: 0;
            -premailer-cellspacing: 0;
            background-color: #F2F4F6;
        }

        .email-content {
            width: 100%;
            margin: 0;
            padding: 0;
            -premailer-width: 100%;
            -premailer-cellpadding: 0;
            -premailer-cellspacing: 0;
        }

        /* Masthead ----------------------- */

        .email-masthead {
            padding: 25px 0;
            text-align: center;
        }

        .email-masthead_logo {
            width: 94px;
        }

        .email-masthead_name {
            font-size: 60px;
            font-weight: bold;
            color: #333;
            text-decoration: none;
            text-shadow: 0 1px 0 white;
        }

        /* Body ------------------------------ */

        .email-body {
            width: 100%;
            margin: 0;
            padding: 0;
            -premailer-width: 100%;
            -premailer-cellpadding: 0;
            -premailer-cellspacing: 0;
            border-top: 1px solid #EDEFF2;
            border-bottom: 1px solid #EDEFF2;
            background-color: #FFFFFF;
        }

        .email-body_inner {
            width: 570px;
            margin: 0 auto;
            padding: 0;
            -premailer-width: 570px;
            -premailer-cellpadding: 0;
            -premailer-cellspacing: 0;
            background-color: #FFFFFF;
        }

        .email-footer {
            width: 570px;
            margin: 0 auto;
            padding: 0;
            -premailer-width: 570px;
            -premailer-cellpadding: 0;
            -premailer-cellspacing: 0;
            text-align: center;
        }

        .email-footer p {
            color: #AEAEAE;
        }

        .body-action {
            width: 100%;
            margin: 30px auto;
            padding: 0;
            -premailer-width: 100%;
            -premailer-cellpadding: 0;
            -premailer-cellspacing: 0;
            text-align: center;
        }

        .body-sub {
            margin-top: 25px;
            padding-top: 25px;
            border-top: 1px solid #EDEFF2;
        }

        .content-cell {
            padding: 35px;
        }

        .preheader {
            display: none !important;
            visibility: hidden;
            mso-hide: all;
            font-size: 1px;
            line-height: 1px;
            max-height: 0;
            max-width: 0;
            opacity: 0;
            overflow: hidden;
        }

        /* Attribute list ------------------------------ */

        .attributes {
            margin: 0 0 21px;
        }

        .attributes_content {
            background-color: #EDEFF2;
            padding: 16px;
        }

        .attributes_item {
            padding: 0;
        }

        /* Related Items ------------------------------ */

        .related {
            width: 100%;
            margin: 0;
            padding: 25px 0 0 0;
            -premailer-width: 100%;
            -premailer-cellpadding: 0;
            -premailer-cellspacing: 0;
        }

        .related_item {
            padding: 10px 0;
            color: #74787E;
            font-size: 15px;
            line-height: 18px;
        }

        .related_item-title {
            display: block;
            margin: .5em 0 0;
        }

        .related_item-thumb {
            display: block;
            padding-bottom: 10px;
        }

        .related_heading {
            border-top: 1px solid #EDEFF2;
            text-align: center;
            padding: 25px 0 10px;
        }

        /* Discount Code ------------------------------ */

        .discount {
            width: 100%;
            margin: 0;
            padding: 24px;
            -premailer-width: 100%;
            -premailer-cellpadding: 0;
            -premailer-cellspacing: 0;
            background-color: #EDEFF2;
            border: 2px dashed #9BA2AB;
        }

        .discount_heading {
            text-align: center;
        }

        .discount_body {
            text-align: center;
            font-size: 15px;
        }

        /* Social Icons ------------------------------ */

        .social {
            width: auto;
        }

        .social td {
            padding: 0;
            width: auto;
        }

        .social_icon {
            height: 20px;
            margin: 0 8px 10px 8px;
            padding: 0;
        }

        /* Data table ------------------------------ */

        .purchase {
            width: 100%;
            margin: 0;
            padding: 35px 0;
            -premailer-width: 100%;
            -premailer-cellpadding: 0;
            -premailer-cellspacing: 0;
        }

        .purchase_content {
            width: 100%;
            margin: 0;
            padding: 25px 0 0 0;
            -premailer-width: 100%;
            -premailer-cellpadding: 0;
            -premailer-cellspacing: 0;
        }

        .purchase_item {
            padding: 10px 0;
            color: #74787E;
            font-size: 15px;
            line-height: 18px;
        }

        .purchase_heading {
            padding-bottom: 8px;
            border-bottom: 1px solid #EDEFF2;
        }

        .purchase_heading p {
            margin: 0;
            color: #9BA2AB;
            font-size: 12px;
        }

        .purchase_footer {
            padding-top: 15px;
            border-top: 1px solid #EDEFF2;
        }

        .purchase_total {
            margin: 0;
            text-align: right;
            font-weight: bold;
            color: #2F3133;
        }

        .purchase_total--label {
            padding: 0 15px 0 0;
        }

        /* Utilities ------------------------------ */

        .align-right {
            text-align: right;
        }

        .align-left {
            text-align: left;
        }

        .align-center {
            text-align: center;
        }

        /*Media Queries ------------------------------ */

        @media only screen and (max-width: 600px) {
            .email-body_inner,
            .email-footer {
                width: 100% !important;
            }
        }

        @media only screen and (max-width: 500px) {
            .button {
                width: 100% !important;
            }
        }

        /* Buttons ------------------------------ */

        .button {
            background-color: #3869D4;
            border-top: 10px solid #3869D4;
            border-right: 18px solid #3869D4;
            border-bottom: 10px solid #3869D4;
            border-left: 18px solid #3869D4;
            display: inline-block;
            color: #FFF;
            text-decoration: none;
            border-radius: 3px;
            box-shadow: 0 2px 3px rgba(0, 0, 0, 0.16);
            -webkit-text-size-adjust: none;
        }

        .button--green {
            background-color: #22BC66;
            border-top: 10px solid #22BC66;
            border-right: 18px solid #22BC66;
            border-bottom: 10px solid #22BC66;
            border-left: 18px solid #22BC66;
        }

        .button--red {
            background-color: #FF6136;
            border-top: 10px solid #FF6136;
            border-right: 18px solid #FF6136;
            border-bottom: 10px solid #FF6136;
            border-left: 18px solid #FF6136;
        }

        /* Type ------------------------------ */

        h1 {
            margin-top: 0;
            color: #2F3133;
            font-size: 19px;
            font-weight: bold;
            text-align: left;
        }

        h2 {
            margin-top: 0;
            color: #2F3133;
            font-size: 16px;
            font-weight: bold;
            text-align: left;
        }

        h3 {
            margin-top: 0;
            color: #2F3133;
            font-size: 14px;
            font-weight: bold;
            text-align: left;
        }

        p {
            margin-top: 0;
            color: #74787E;
            font-size: 16px;
            line-height: 1.5em;
            text-align: left;
        }

        p.sub {
            font-size: 12px;
        }

        p.center {
            text-align: center;
        }

    </style>
</head>
<body>
<table class="email-wrapper" width="100%" cellpadding="0" cellspacing="0">
    <tr>
        <td align="center">
            <table class="email-content" width="100%" cellpadding="0" cellspacing="0">
                <tr>
                    <td class="email-masthead">
                        <a href="#" class="email-masthead_name">
{{--                            <img style="width: 30%;" src="http://k-office.kpis.vn/assets/images/logo.png?v=2"--}}
{{--                                 alt="K-OFFICE"/> <strong>HE thong</strong>--}}
                            <img style="width: 75px; height: 70px;" src="data:image/png;base64,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" width="50" height="50" alt="CTUMP" />
                            <strong>BỆNH VIỆN TRƯỜNG ĐẠI HỌC CẦN THƠ</strong>
                        </a>
                    </td>
                </tr>
                <!-- Email Body -->
                <tr>
                    <td class="email-body" width="100%" cellpadding="0" cellspacing="0">
                        <table class="email-body_inner" align="center" width="570" cellpadding="0" cellspacing="0">
                            <!-- Body content -->
                            <tr>
                                <td class="content-cell">
                                    <h1>Xin chào bạn, tài khoản của bạn là {{$username}}</h1>
                                    <p>Bạn có vừa xác nhận đăng ký tài khoản! </p>
                                    <p>Mã xác thực tài khoản của bạn là : <strong>{{$verify_code}}</strong></p>
                                    <!-- Action -->
                                    Xin vui lòng sao chép <strong>mã OTP</strong> này vào thông tin đăng ký : <strong>Mã OTP này chỉ có thời hạn 5
                                        phút </strong></a>
                                    <p>Đừng chia sẻ thông tin này cho bất kỳ ai, với bất kỳ điều gì.</p>
                                    <p>&nbsp;</p>
                                    <p>Trân trọng.
                                        <br>CTU</p>
                                    <!-- Sub copy -->
                                    <table class="body-sub">
                                        <tr>
                                            <td>
                                                <p class="sub">CTU xin chân thành cảm ơn quý khách hàng đã tin tưởng và
                                                    sử dụng hệ thống khám bệnh trực tuyến của chúng tôi trong thời gian qua. </p>
                                                <p class="sub">Mọi vấn đề thắc mắc hoặc tư vấn hỗ trợ kỹ thuật vui lòng
                                                    liên hệ với
                                                    chúng tôi qua hotline: 0783 832 830 hoặc email: <a
                                                            href="mailto:lachithao123vn@gmail.com">support@gmail.com</a>.
                                                </p>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td>
                        <table class="email-footer" align="center" width="570" cellpadding="0" cellspacing="0">
                            <tr>
                                <td class="content-cell" align="center">
                                    <p class="sub align-center">&copy; 2021 CTU-B1706869 C Ltd,. All rights reserved.</p>
                                    <p class="sub align-center">
                                        Đại học Cần Thơ, đường 3/2, Xuân Khánh, Ninh Kiều, Cần Thơ
                                        <br>Phone: 012 345 6789
                                        <br>Email: <a href="mailto:support@student.ctu.edu.vn">support@student.ctu.edu.vn</a>
                                    </p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>