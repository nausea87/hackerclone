<?php

declare(strict_types=1);

require __DIR__ . '/../autoload.php';

if (!userIsLoggedIn()) {
    redirect('/');
}

if (isset($_POST['id'], $_POST['reply'])) {
    $reply = filter_var(trim($_POST['reply']), FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
    $commentId = trim(filter_var($_POST['id'], FILTER_SANITIZE_STRING));
    $valid = true;

    // Insert replies and match with user_id, comment_id
    $statement = $pdo->prepare('INSERT INTO replies (user_id, comment_id, reply) 
    VALUES (:user_id, :comment_id, :reply)');
    if (!$statement) {
        $valid = false;
        $errors = $pdo->errorInfo();
        $response = [
            'valid' => $valid,
            'errors' => $errors
        ];
        echo json_encode($response);
        exit;
    }

    $statement->execute([
        ':user_id' => $_SESSION['user']['id'],
        ':comment_id' => $commentId,
        ':reply' => $reply
    ]);

    // Get replies
    $statement = $pdo->prepare('SELECT * FROM replies WHERE id = :id');
    if (!$statement) {
        $valid = false;
        $errors = $pdo->errorInfo();
        $response = [
            'valid' => $valid,
            'errors' => $errors
        ];
        echo json_encode($response);
        exit;
    }

    $statement->execute([
        ':id' => $pdo->lastInsertId()
    ]);

    $reply = $statement->fetch(PDO::FETCH_ASSOC);

    $user = getUserById($pdo, $_SESSION['user']['id']);

    // User info
    $reply['username'] = $user['username'];
    $reply['avatar'] = $user['avatar'];

    $response = [
        'valid' => $valid,
        'reply' => $reply
    ];

    echo json_encode($response);
    exit;
}
