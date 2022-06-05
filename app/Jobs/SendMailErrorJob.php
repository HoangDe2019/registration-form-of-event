<?php

namespace App\Jobs;

use App\Supports\OFFICE_Email;

class SendMailErrorJob extends Job
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
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
        OFFICE_Email::send(OFFICE_Email::$view_report_error, OFFICE_Email::$mail_supporter, $data, null, null, '[Error]BỆNH VIÊN ĐẠI HỌC CẦN THƠ');
        echo "Sent!";
        return;
    }
}
