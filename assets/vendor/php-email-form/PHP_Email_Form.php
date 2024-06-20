<?php
class PHP_Email_Form {
    public $to;
    public $from_name;
    public $from_email;
    public $subject;
    public $smtp;
    private $messages = array();
    public $ajax;

    public function add_message($content, $label, $priority=0) {
        $this->messages[] = array(
            'content' => $content,
            'label' => $label,
            'priority' => $priority
        );
    }

    public function send() {
        $email_content = "";
        foreach ($this->messages as $message) {
            $email_content .= $message['label'] . ": " . $message['content'] . "\n";
        }

        $headers = "From: {$this->from_name} <{$this->from_email}>\r\n";
        $headers .= "Reply-To: {$this->from_email}\r\n";
        $headers .= "Content-type: text/plain; charset=UTF-8\r\n";

        if(isset($this->smtp)) {
            return $this->send_with_smtp($email_content, $headers);
        } else {
            return mail($this->to, $this->subject, $email_content, $headers) ? "Email sent successfully!" : "Email sending failed.";
        }
    }

    private function send_with_smtp($email_content, $headers) {
        // Using PHPMailer for SMTP
        require 'PHPMailer/PHPMailerAutoload.php';
        $mail = new PHPMailer;
        $mail->isSMTP();
        $mail->Host = $this->smtp['host'];
        $mail->SMTPAuth = true;
        $mail->Username = $this->smtp['username'];
        $mail->Password = $this->smtp['password'];
        $mail->SMTPSecure = 'tls';
        $mail->Port = $this->smtp['port'];

        $mail->setFrom($this->from_email, $this->from_name);
        $mail->addAddress($this->to);
        $mail->Subject = $this->subject;
        $mail->Body = $email_content;

        if(!$mail->send()) {
            return 'Mailer Error: ' . $mail->ErrorInfo;
        } else {
            return 'Email sent successfully!';
        }
    }
}
?>
