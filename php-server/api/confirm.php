<?php
require_once __DIR__ . '/../config.php';

$token = isset($_GET['token']) ? $_GET['token'] : '';

if (!$token || strlen($token) !== 64 || !ctype_xdigit($token)) {
    page('Помилка', 'Невірне або прострочене посилання.');
    exit;
}

$subscribers = json_decode(file_get_contents(DATA_FILE), true);
if (!is_array($subscribers)) $subscribers = array();

$found = false;
foreach ($subscribers as &$sub) {
    if ($sub['token'] === $token) {
        if ($sub['status'] === 'active') {
            page('Вже підписані', 'Ця адреса вже підтверджена.');
            exit;
        }
        $sub['status']       = 'active';
        $sub['confirmed_at'] = date('Y-m-d H:i:s');
        $found = true;
        break;
    }
}
unset($sub);

if (!$found) {
    page('Помилка', 'Посилання не знайдено або вже використане.');
    exit;
}

write_json($subscribers);
page('Підписку підтверджено', 'Дякую! Новий запис надійде на пошту.');

// --- helpers ---

function page($title, $msg) {
    ?><!DOCTYPE html>
<html lang="uk">
<head>
<meta charset="UTF-8">
<title><?php echo htmlspecialchars($title); ?></title>
<style>
  body { font-family: system-ui, sans-serif; max-width: 480px; margin: 80px auto; padding: 0 24px; color: #1a1a1a; }
  h1   { font-size: 26px; margin-bottom: 12px; }
  p    { color: #6b6b6b; line-height: 1.6; }
  a    { color: #1a3d7c; }
</style>
</head>
<body>
  <h1><?php echo htmlspecialchars($title); ?></h1>
  <p><?php echo htmlspecialchars($msg); ?></p>
  <p><a href="<?php echo SITE_URL; ?>">← Соловей</a></p>
</body>
</html><?php
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
