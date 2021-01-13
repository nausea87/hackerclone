<?php

declare(strict_types=1);

require __DIR__ . '/../autoload.php';

if (!userIsLoggedIn()) {
    redirect('/');
}

if (isset($_POST['id'])) {
    $postId = trim(filter_var($_POST['id'], FILTER_SANITIZE_STRING));
    $userId = $_SESSION['user']['id'];

    // + / -

    // If already liked, delete the like
    if (postIsLiked($pdo, $userId, $postId)) {
        $deleteStatement = $pdo->prepare('DELETE FROM likes WHERE user_id = :userId AND post_id = :postId');
        if (!$deleteStatement) {
            die(var_dump($pdo->errorInfo()));
        }
        $deleteStatement->execute([
            ':userId' => $userId,
            ':postId' => $postId
        ]);

        $numberOfLikes = numOfLikes($pdo, $postId);
        $btnText = "like";
        $response = [
            'numberOfLikes' => $numberOfLikes,
            'buttonText' => $btnText
        ];
        echo json_encode($response);

        // Else insert the like

    } else {
        $insertStatement = $pdo->prepare('INSERT INTO likes (user_id, post_id) VALUES (:userId, :postId)');
        if (!$insertStatement) {
            die(var_dump($pdo->errorInfo()));
        }
        $insertStatement->execute([
            ':userId' => $userId,
            ':postId' => $postId
        ]);

        $numberOfLikes = numOfLikes($pdo, $postId);
        $btnText = "unlike";
        $response = [
            'numberOfLikes' => $numberOfLikes,
            'buttonText' => $btnText
        ];
        echo json_encode($response);
    }
}
