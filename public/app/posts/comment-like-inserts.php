<?php

declare(strict_types=1);

require __DIR__ . '/../autoload.php';

if (!userIsLoggedIn()) {
    redirect('/');
}

if (isset($_POST['id'])) {
    $commentId = trim(filter_var($_POST['id'], FILTER_SANITIZE_STRING));
    $userId = $_SESSION['user']['id'];

    // + / -

    // If already liked, delete the like
    if (commentIsLiked($pdo, $userId, $commentId)) {
        $deleteStatement = $pdo->prepare('DELETE FROM comment_likes WHERE user_id = :userId AND comment_id = :commentId');
        if (!$deleteStatement) {
            die(var_dump($pdo->errorInfo()));
        }
        $deleteStatement->execute([
            ':userId' => $userId,
            ':commentId' => $commentId
        ]);

        $numberOfLikes = numOfLikes($pdo, $commentId);
        $btnText = "like";
        $response = [
            'numberOfLikes' => $numberOfLikes,
            'buttonText' => $btnText
        ];
        echo json_encode($response);

        // Else insert the like

    } else {
        $insertStatement = $pdo->prepare('INSERT INTO comment_likes (user_id, comment_id) VALUES (:userId, :commentId)');
        if (!$insertStatement) {
            die(var_dump($pdo->errorInfo()));
        }
        $insertStatement->execute([
            ':userId' => $userId,
            ':commentId' => $commentId
        ]);

        $numberOfLikes = numOfCommentLikes($pdo, $commentId);
        $btnText = "unlike";
        $response = [
            'numberOfLikes' => $numberOfLikes,
            'buttonText' => $btnText
        ];
        echo json_encode($response);
    }
}
