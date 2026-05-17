<?php
define('DATA_FILE',      __DIR__ . '/data/subscribers.json');
define('SITE_URL',       'https://ivansolovey.github.io');
define('API_URL',        'https://solovey.osd24.com');
define('FROM_EMAIL',     'noreply@osd24.com');
define('FROM_NAME',      'Соловей');
define('REPLY_TO',       'vany.soloviov@gmail.com');
define('ALLOWED_ORIGIN', 'https://ivansolovey.github.io');

// Змінити на випадковий рядок перед деплоєм!
// Генерація: php -r "echo bin2hex(random_bytes(24));"
define('SEND_SECRET', '00158097663c99c31f9b7b3d8c6e48d7aa1dab4af338d526');
