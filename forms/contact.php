<?php
require 'PHP_Email_Form/PHP_Email_Form.php';
require_once('captcha.php');

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

header('Content-Type: application/json'); // Установка заголовка

if ($contact->send()) {
    echo json_encode(array("next" => "/thanks.html", "ok" => true));
} else {
    echo json_encode(array("next" => "/error.html", "ok" => false));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Обработка формы, отправка письма и т.д.
    // ...
}
?>
