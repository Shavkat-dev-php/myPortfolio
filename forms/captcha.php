<?php
$recaptcha_secret_key = 'YOUR_SECRET_KEY';
$recaptcha_response = $_POST['g-recaptcha-response'];

$url = 'https://www.google.com/recaptcha/api/siteverify';
$data = array(
    'secret' => $recaptcha_secret_key,
    'response' => $recaptcha_response
);

$options = array(
    'http' => array(
        'header' => "Content-type: application/x-www-form-urlencoded\r\n",
        'method' => 'POST',
        'content' => http_build_query($data)
    )
);

$context = stream_context_create($options);
$verify = file_get_contents($url, false, $context);
$captcha_success = json_decode($verify);

if ($captcha_success->success) {
    // Решение reCAPTCHA верное, продолжайте обработку формы
} else {
    // Решение reCAPTCHA неверное, обработайте ошибку
}

if (!isset($_POST['g-recaptcha-response'])) {
  // Ошибка: значение reCAPTCHA не было отправлено формой
  exit('Error: No reCAPTCHA value submitted');
}

if (!$verify) {
  // Ошибка: запрос к Google reCAPTCHA API не удался
  exit('Error: Failed to connect to Google reCAPTCHA API');
}

if (!$captcha_success->success) {
  // Ошибка: решение reCAPTCHA неверное
  exit('Error: Invalid reCAPTCHA solution');
}

?>
