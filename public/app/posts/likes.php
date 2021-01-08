<?php

declare(strict_types=1);

require __DIR__ . '/../autoload.php';

if (!userIsLoggedIn()) {
    redirect('/');
}

header('Content-Type: application/json');

if (isset($_POST['id'])) {
    $postId = trim(filter_var($_POST['id'], FILTER_SANITIZE_STRING));
    $userId = $_SESSION['user']['id'];

    // if like exist, delete it. If it doesnt, add it.
    if (isLikedBy($pdo, $userId, $postId)) {
        $deleteStatement = $pdo->prepare('DELETE FROM likes WHERE user_id = :userId AND post_id = :postId');
        if (!$deleteStatement) {
            die(var_dump($pdo->errorInfo()));
        }
        $deleteStatement->execute([
            ':userId' => $userId,
            ':postId' => $postId
        ]);
        // response to the front-end
        $numberOfLikes = getNumberOfLikes($pdo, $postId);
        $buttonText = "like";
        $response = [
            'numberOfLikes' => $numberOfLikes,
            'buttonText' => $buttonText
        ];
        echo json_encode($response);
    } else {
        $insertStatement = $pdo->prepare('INSERT INTO likes (user_id, post_id) VALUES (:userId, :postId)');
        if (!$insertStatement) {
            die(var_dump($pdo->errorInfo()));
        }
        $insertStatement->execute([
            ':userId' => $userId,
            ':postId' => $postId
        ]);
        // response to the front-end
        $numberOfLikes = getNumberOfLikes($pdo, $postId);
        $buttonText = "unlike";
        $response = [
            'numberOfLikes' => $numberOfLikes,
            'buttonText' => $buttonText
        ];
        echo json_encode($response);
    }
}
