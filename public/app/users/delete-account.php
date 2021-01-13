<?php
//TODO: Fix this
declare(strict_types=1);

require __DIR__ . '/../autoload.php';

if (!userIsLoggedIn()) {
    redirect('/');
}

$id = $_SESSION['user']['id'];

// If not your own account
if ($_SESSION['user_id'] === $id) {
    $_SESSION['errors'] = 'You can only delete your own account';
    redirect('/');
}

$statement = $pdo->prepare('DELETE FROM users WHERE id = :id');
pdoErrorInfo($pdo, $statement);

$statement->execute([
    ':id' => $id['id']
]);

$_SESSION['messages'] = 'Account deleted';

redirect('/');
