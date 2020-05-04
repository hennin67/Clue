<?php


namespace Clue;


class Email
{
    public function mail($to, $subject, $message, $headers) {
        mail($to, $subject, $message, $headers);
    }
}