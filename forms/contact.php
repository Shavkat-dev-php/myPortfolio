
<?php

require 'PHP_Email_Form/PHP_Email_Form.php';

$receiving_email_address = 'shavkatphpdev@gmail.com';

$contact = new PHP_Email_Form;
$contact->ajax = true;

$contact->to = $receiving_email_address;
$contact->from_name = $_POST['name'];
$contact->from_email = $_POST['email'];
$contact->subject = $_POST['subject'];

// Настройки SMTP
$contact->smtp = array(
    'host' => 'smtp.mail.ru', // Пример SMTP сервера
    'username' => 'shavkatphpdev@gmail.com',
    'password' => '', // Обратите внимание, что хранение пароля в коде небезопасно
    'port' => '465'
);

$contact->add_message($_POST['name'], 'From');
$contact->add_message($_POST['email'], 'Email');
$contact->add_message($_POST['message'], 'Message', 10);

echo $contact->send();
?>
