<?php
require_once __DIR__ . '/../config.php';

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: ' . ALLOWED_ORIGIN);
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(array('ok' => false, 'error' => 'Method not allowed'));
    exit;
}

$email = isset($_POST['email']) ? strtolower(trim($_POST['email'])) : '';

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(array('ok' => false, 'error' => 'Невірний email'));
    exit;
}

// Ініціалізуємо data-файл якщо немає
if (!file_exists(DATA_FILE)) {
    $dir = dirname(DATA_FILE);
    if (!is_dir($dir)) mkdir($dir, 0755, true);
    file_put_contents(DATA_FILE, '[]');
}

$subscribers = json_decode(file_get_contents(DATA_FILE), true);
if (!is_array($subscribers)) $subscribers = array();

// Перевіряємо чи вже є такий email
foreach ($subscribers as $sub) {
    if ($sub['email'] === $email) {
        if ($sub['status'] === 'active') {
            echo json_encode(array('ok' => true, 'msg' => 'already'));
        } else {
            send_confirmation($email, $sub['token']);
            echo json_encode(array('ok' => true, 'msg' => 'resent'));
        }
        exit;
    }
}

$token = bin2hex(openssl_random_pseudo_bytes(32));

$subscribers[] = array(
    'email'         => $email,
    'token'         => $token,
    'status'        => 'pending',
    'subscribed_at' => date('Y-m-d H:i:s'),
);

write_json($subscribers);
send_confirmation($email, $token);

echo json_encode(array('ok' => true, 'msg' => 'confirm_sent'));

// --- helpers ---

function send_confirmation($email, $token) {
    $confirm_url     = API_URL . '/api/confirm.php?token=' . $token;
    $unsubscribe_url = API_URL . '/api/unsubscribe.php?token=' . $token;

    $subject = 'Підтвердіть підписку на Соловей';
    $body    = "Ви підписались на блог «Соловей».\n\n"
             . "Щоб підтвердити підписку, перейдіть за посиланням:\n"
             . $confirm_url . "\n\n"
             . "Якщо не підписувались — просто ігноруйте цей лист.\n\n"
             . "Відписатися: " . $unsubscribe_url;

    send_mail($email, $subject, $body);
}

function send_mail($to, $subject, $body) {
    $headers = "From: " . FROM_NAME . " <" . FROM_EMAIL . ">\r\n"
             . "Reply-To: " . REPLY_TO . "\r\n"
             . "Content-Type: text/plain; charset=UTF-8\r\n"
             . "Content-Transfer-Encoding: 8bit\r\n";

    $encoded_subject = '=?UTF-8?B?' . base64_encode($subject) . '?=';
    mail($to, $encoded_subject, $body, $headers);
}

function write_json($data) {
    $fp = fopen(DATA_FILE, 'c');
    if (flock($fp, LOCK_EX)) {
        ftruncate($fp, 0);
        rewind($fp);
        fwrite($fp, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        flock($fp, LOCK_UN);
    }
    fclose($fp);
}
