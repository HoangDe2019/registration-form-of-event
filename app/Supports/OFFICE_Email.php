<?php
/**
 * User: Administrator
 * Date: 16/10/2018
 * Time: 09:48 PM
 */

namespace App\Supports;


use Illuminate\Support\Facades\Mail;

class OFFICE_Email
{
    static $mail_supporter = "lachithao123vn@gmail.com";
    static $view_report_error = "mail_send_report_error";
    static $view_mail_promp_issue = "mail_send_promp_issue";
    static $view_mail_create_issue = "mail_send_create_issue";
    static $view_mail_reset_password = "mail_send_reset_password";
    static $view_mail_create_account = "mail_send_create_account_patients";
    static $view_mail_booking_schedule = "mail_send_booking_patients";
    static $view_mail_send_update_profile_patient = "mail_send_update_profile_patient";
    static $view_mail_booking_schedule_confirm = "mail_send_booking_patients_confirm";
    static $view_mail_booking_schedule_refuse = "mail_send_booking_patients_refuse";
    static $view_mail_booking_schedule_cancel = "mail_send_booking_patients_cancel";
    static $report_issue_user_login_other = "report_issue_user_login_other";

    /**
     * @param $view
     * @param $to
     * @param array $data
     * @param array $cc
     * @param array $bcc
     * @param string $subject
     */
    static function send($view, $to, $data = [], $cc = [], $bcc = [], $subject = "CTU - Hospital Can Tho University")
    {
        $data['logo'] = env('APP_LOGO');
        Mail::send($view, $data, function ($message) use ($to, $subject, $data, $cc, $bcc) {
            $message->to($to);
            if (!empty($cc)) {
                $message->cc($cc);
            }
            $bcc[] = self::$mail_supporter;
//            $bcc[] = "kpis.vn@gmail.com";
            $message->bcc($bcc);
            $message->subject($subject);
        });
    }
}