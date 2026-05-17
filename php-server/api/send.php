<?php
require_once __DIR__ . '/../config.php';

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(array('ok' => false, 'error' => 'Method not allowed'));
    exit;
}

$secret   = isset($_POST['secret'])   ? $_POST['secret']           : '';
$subject  = isset($_POST['subject'])  ? trim($_POST['subject'])     : '';
$body     = isset($_POST['body'])     ? trim($_POST['body'])        : '';
$post_url = isset($_POST['post_url']) ? trim($_POST['post_url'])    : '';

if ($secret !== SEND_SECRET) {
    http_response_code(403);
    echo json_encode(array('ok' => false, 'error' => 'Forbidden'));
    exit;
}

if (!$subject || !$body) {
    http_response_code(400);
    echo json_encode(array('ok' => false, 'error' => 'subject і body обовʼязкові'));
    exit;
}

$subscribers = json_decode(file_get_contents(DATA_FILE), true);
if (!is_array($subscribers)) $subscribers = array();

$active = array_filter($subscribers, function ($sub) {
    return $sub['status'] === 'active';
});

$sent   = 0;
$failed = 0;

foreach ($active as $sub) {
    $unsubscribe_url = API_URL . '/api/unsubscribe.php?token=' . $sub['token'];

    $full_body = $body
               . ($post_url ? "\n\nЧитати повністю: " . $post_url : '')
               . "\n\n---\nВідписатися: " . $unsubscribe_url;

    $headers = "From: " . FROM_NAME . " <" . FROM_EMAIL . ">\r\n"
             . "Reply-To: " . REPLY_TO . "\r\n"
             . "Content-Type: text/plain; charset=UTF-8\r\n"
             . "Content-Transfer-Encoding: 8bit\r\n";

    $ok = mail(
        $sub['email'],
        '=?UTF-8?B?' . base64_encode($subject) . '?=',
        $full_body,
        $headers
    );

    if ($ok) { $sent++; } else { $failed++; }

    usleep(100000); // 100ms між відправками
}

echo json_encode(array('ok' => true, 'sent' => $sent, 'failed' => $failed));
