<?php
    //-------------------------------------
    // Copyright : Martin Brugnara
    // Contact : github.com/Martin-Brugnara
    //-------------------------------------


require_once 'swift/lib/swift_required.php';

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
            $headers = join(array(
                sprintf('From: %s', $this->from),
                'Content-Type: text/plain; charset=UTF-8',
                'Content-Transfer-Encoding: 8bit',
                '',
                ''), "\r\n");

            $transport = Swift_SmtpTransport::newInstance('smtp.googlemail.com', 465, "ssl")
              ->setUsername($mail_user)
              ->setPassword($mail_pass);

            $mailer = Swift_Mailer::newInstance($transport);

            $message = Swift_Message::newInstance($this->subject)
              ->setFrom(array($from => $from))
              ->setTo(array($to))
              ->setBody($body);

            return $mailer->send($message);

            #mail($to, $subject, $message, $headers); // Tecnodata
            #return mail($this->to, '=?UTF-8?B?'.base64_encode($this->subject).'?=', $this->message, $headers, "-f".$this->from); // Aruba
        }
    }
?>
