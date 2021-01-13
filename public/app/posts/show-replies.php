<?php

declare(strict_types=1);

require __DIR__ . '/../autoload.php';

if (!userIsLoggedIn()) {
    redirect('/');
}

header('Content-Type: application/json');

if (isset($_POST['id'])) {
    $commentId = trim(filter_var($_POST['id'], FILTER_SANITIZE_STRING));

    $valid = true;

    if (!existsInDatabase($pdo, 'comments', 'id', $commentId)) {
        $valid = false;
        $errors = "Comment not found";
        $response = [
            'valid' => $valid,
            'errors' => $errors
        ];
        echo json_encode($response);
        exit;
    }

    $statement = $pdo->prepare('SELECT * FROM replies WHERE comment_id = :commentId ORDER BY date DESC');
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
        ':commentId' => $commentId
    ]);

    $replies = $statement->fetchAll(PDO::FETCH_ASSOC);

    //add username and avatar of replier to each reply
    for ($i = 0; $i < count($replies); $i++) {
        $replier = getUserById($pdo, $replies[$i]['user_id']);
        $replies[$i]['avatar'] = $replier['avatar'];
        $replies[$i]['username'] = $replier['username'];
    }

    $response = [
        'valid' => $valid,
        'replies' => $replies
    ];

    echo json_encode($response);
    exit;
}

redirect('/');
