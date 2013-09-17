<?php
    //-------------------------------------
    // Copyright : Martin Brugnara
    // Contact : github.com/Martin-Brugnara
    //-------------------------------------

    class Mail
    {
        public function __construct($message, $to, $from='PHP', $subject='(No subject)')
        {
            $this->message = wordwrap($message, 70, "\r\n", true);
            $this->to = $to;
            $this->from = $from;
            $this->subject = $subject;
        }

        public function send()
        {
            $headers  = "\r\n".join(array(
                sprintf('From: %s', $this->from),
                'Content-Type: text/plain; charset=UTF-8',
                'Content-Transfer-Encoding: 8bit',
                '',
                ''
            ));

            #mail($to, $subject, $message, $headers); // Tecnodata
            return mail($this->to, '=?UTF-8?B?'.base64_encode($this->subject).'?=', $this->message, $headers, "-f".$this->from); // Aruba
        }
    }
?>