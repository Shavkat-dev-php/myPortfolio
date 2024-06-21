<?php
require_once('recaptcha/autoload.php');

$secret_key = '6LcuC_4pAAAAAO8jWM6bVwmfgy4LPEDoH3JB0Yta';
$recaptcha = new \ReCaptcha\ReCaptcha($secret_key);

if (!isset($_POST['g-recaptcha-response'])) {
    exit('Error: No reCAPTCHA value submitted');
}

$verify = $recaptcha->verify($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);

if (!$verify) {
    exit('Error: Failed to connect to Google reCAPTCHA API');
}

$captcha_success = $verify->isSuccess();

if (!$captcha_success) {
    exit('Error: Invalid reCAPTCHA solution');
}

?>

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

header('Content-Type: application/json'); // Установка заголовка

if ($contact->send()) {
    echo json_encode(array("next" => "/thanks.html", "ok" => true));
} else {
    echo json_encode(array("next" => "/error.html", "ok" => false));
}
?>
