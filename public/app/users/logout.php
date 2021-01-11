<?php

declare(strict_types=1);

require __DIR__ . '/../autoload.php';

// 'Destroy' session
unset($_SESSION['user']);

redirect('/');
