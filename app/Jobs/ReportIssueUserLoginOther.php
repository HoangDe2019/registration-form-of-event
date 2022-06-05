<?php


namespace App\Jobs;


use App\Supports\OFFICE_Email;

class ReportIssueUserLoginOther extends Job
{
    protected $data;
    protected $to;

    public function __construct($to, $data)
    {
        $this->data = $data;
        $this->to = $to;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        echo "Sending!";
        $data = $this->data;
        $to = $this->to;
        OFFICE_Email::send(OFFICE_Email::$report_issue_user_login_other, $to, $data, null, null, 'BỆNH VIÊN ĐẠI HỌC CẦN THƠ');
        echo "Sent!";
        return;
    }
}