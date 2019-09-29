<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Mail;

class SendMail
{
    public static function sendEmail($to, $subject, $template, $data = [])
    {
        $sdata = ['to' => $to, 'subject' => $subject];
        Mail::send($template, $data, function ($mail) use ($sdata) {
                $mail->from(env('MAIL_USERNAME', 'bus-lines'), 'No reply');
            $mail->to($sdata['to'])->subject($sdata['subject']);
        });
    }
}