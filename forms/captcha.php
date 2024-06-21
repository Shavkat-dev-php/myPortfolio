<?php
require_once('recaptcha/autoload.php');

$secret_key = 'YOUR_SECRET_KEY_HERE';
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

// Если reCAPTCHA пройдена успешно, продолжите с обработкой формы
// Например, отправка письма или сохранение данных в базу данных
?>
