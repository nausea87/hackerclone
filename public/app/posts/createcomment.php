<?php

declare(strict_types=1);

require __DIR__ . '/../autoload.php';

if (!userIsLoggedIn()) {
    redirect('/');
}

header('Content-Type: application/json');

if (isset($_POST['comment'], $_POST['id'])) {
    $comment = filter_var(trim($_POST['comment']), FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
    $postId = trim(filter_var($_POST['id'], FILTER_SANITIZE_STRING));
    $user = getUserById($pdo, $_SESSION['user']['id']);
    $valid = true;

    if (!existsInDatabase($pdo, 'posts', 'id', $postId)) {
        $valid = false;
        $errors = "post doesn't exist";
        $response = [
            'valid' => $valid,
            'errors' => $errors
        ];
        echo json_encode($response);
        exit;
    }

    $statement = $pdo->prepare('INSERT INTO comments (user_id, post_id, comment) VALUES (:user_id, :post_id, :comment)');
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
        ':post_id' => $postId,
        ':comment' => $comment
    ]);

    $statement = $pdo->prepare('SELECT * FROM comments WHERE id = :id');
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

    $comment = $statement->fetch(PDO::FETCH_ASSOC);

    $user = getUserById($pdo, $_SESSION['user']['id']);

    $loggedInUser = getUserById($pdo, $_SESSION['user']['id']);

    $response = [
        'valid' => $valid,
        'comment' => $comment,
        'user' => $user,
        'loggedInUser' => $loggedInUser
    ];

    echo json_encode($response);
    exit;
}

redirect('/');
