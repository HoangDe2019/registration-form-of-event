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

        @media  only screen and (max-width: 600px) {
            .email-body_inner,
            .email-footer {
                width: 100% !important;
            }
        }

        @media  only screen and (max-width: 500px) {
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


                            <img style="width: 75px; height: 70px;" src="http://www.ctump.edu.vn/Portals/_default/Skins/dhyd-trangchu/images/logo-dai-hoc-y-duoc-can-tho.png" width="50" height="50" alt="CTUMP" />
                            <strong>BỆNH VIỆN ĐẠI HỌC Y DƯỢC CẦN THƠ</strong>
                        </a>
                    </td>
                </tr>
                <!-- Email Body -->
                <tr>
                    <td class="email-body" width="100%" cellpadding="0" cellspacing="0">
                        <table class="email-body_inner" align="center" width="570" cellpadding="0" cellspacing="0">

                            <tr>
                                <td bgcolor="#ffffff" align="center">

                                    <table width="90%" border="0" cellspacing="0" cellpadding="0">

                                        <tbody><tr>
                                            <td align="center">
                                                <table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
                                                    <tbody><tr>
                                                        <td align="center" style="text-align:justify;font-family:Arial,Helvetica,sans-serif;font-size:14px;font-weight:700;font-style:italic;color:#0b499c;padding:30px 0px 0px 0px">
                                                            Kính gửi Quý bệnh nhân: <span style="color:#000000;font-weight:400"> <?php echo e($name); ?> </span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td align="center" style="text-align:justify;font-family:Arial,Helvetica,sans-serif;font-size:14px;font-style:italic;color:#0b499c;padding:10px 0px 0px 0px">
                                                            Lịch khám của bệnh nhân bị từ chối vì bác sĩ có công việc bận đột xuất<br>
                                                            Nên chúng tôi thành thật xin lỗi quý bệnh nhân vì sự bất tiện này.<br>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td align="center" style="text-align:justify;font-family:Arial,Helvetica,sans-serif;font-size:14px;font-weight:700;font-style:italic;color:#0b499c;padding:10px 0px 0px 0px">
                                                            Tên bệnh nhân đặt lịch: <span style="color:#000000;font-weight:400"> <?php echo e($name); ?>, </span>
                                                        </td><td>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td align="center" style="text-align:justify;font-family:Arial,Helvetica,sans-serif;font-size:14px;font-weight:700;font-style:italic;color:#0b499c;padding:10px 0px 0px 0px">
                                                            Số điện thoại: <span style="color:#000000;font-weight:400"> <?php echo e($phone); ?> </span>
                                                        </td><td>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td align="center" style="text-align:justify;font-family:Arial,Helvetica,sans-serif;font-size:14px;font-weight:700;font-style:italic;color:#0b499c;padding:10px 0px 0px 0px">
                                                            Địa chỉ: <span style="color:#000000;font-weight:400"> <?php echo e($address); ?> </span>
                                                        </td><td>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td align="center" style="text-align:justify;font-family:Arial,Helvetica,sans-serif;font-size:14px;font-weight:700;font-style:italic;color:#0b499c;padding:10px 0px 0px 0px">
                                                            Lịch hẹn khám bệnh với bác sĩ: <span style="color:#000000;font-weight:400"> <?php echo e($doctor_name); ?> </span>
                                                        </td><td>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td align="center" style="text-align:justify;font-family:Arial,Helvetica,sans-serif;font-size:14px;font-weight:700;font-style:italic;color:#0b499c;padding:10px 0px 0px 0px">
                                                            Ngày làm việc: <span style="color:#000000;font-weight:400"> <?php echo e($date_work); ?> </span>
                                                        </td><td>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td align="center" style="text-align:justify;font-family:Arial,Helvetica,sans-serif;font-size:14px;font-weight:700;font-style:italic;color:#0b499c;padding:10px 0px 0px 0px">
                                                            Buổi làm việc: <span style="color:#000000;font-weight:400"> <?php echo e($session); ?> </span>
                                                        </td><td>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td align="center" style="text-align:justify;font-family:Arial,Helvetica,sans-serif;font-size:14px;font-weight:700;font-style:italic;color:#0b499c;padding:10px 0px 0px 0px">
                                                            Thời gian khám bệnh: <span style="color:#000000;font-weight:400"> <?php echo e($book_time); ?> </span>
                                                        </td><td>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td align="center" style="text-align:justify;font-family:Arial,Helvetica,sans-serif;font-size:14px;font-weight:700;font-style:italic;color:#0b499c;padding:10px 0px 20px 0px">
                                                            Chú ý: <span style="color:#000000;font-weight:400"><i style="color:red">!Mong quí bệnh nhân thông cảm<br> Kính mong quí bệnh nhân cố thể xem chọn lịch khám bệnh của bác sĩ khác hoặc chờ đặt lại lịch của bác sĩ trên vào khung giờ tiếp theo<br> Thành thật xin lỗi quý bệnh nhân sự bất tiện này. Mong quý bệnh nhân thông cảm.</i></span>
                                                        </td><td>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td align="center" style="text-align:justify;font-family:Arial,Helvetica,sans-serif;font-size:14px;font-weight:700;font-style:italic;color:#0b499c;padding:10px 0px 20px 0px">
                                                            Chi nhánh/quản  lý: <span style="color:#000000;font-weight:400">BỆNH VIỆN TRƯỜNG ĐẠI HỌC Y DƯỢC CẦN THƠ</span>
                                                        </td><td>
                                                        </td>
                                                    </tr>


                                                    </tbody></table>
                                            </td>
                                        </tr>

                                        </tbody>
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
                                        Số 179, đường Nguyễn Văn Cừ, P. An Khánh, Q. Ninh Kiều, TP. Cần Thơ.
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
</html><?php /**PATH C:\xampp\htdocs\hospital\resources\views/mail_send_booking_patients_refuse.blade.php ENDPATH**/ ?>