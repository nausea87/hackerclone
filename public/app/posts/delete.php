<?php

declare(strict_types=1);

require __DIR__ . '/../autoload.php';

if (!userIsLoggedIn()) {
    redirect('/');
}

$id = $_SESSION['user']['id'];

if (isset($_POST['id'])) {
    $post = getPostById($pdo, $_POST['id']);

    if ($post['user_id'] !== $id) {
        $_SESSION['errors'] = 'Not your post';
        redirect('/');
    }

    $statement = $pdo->prepare('SELECT * FROM comments WHERE post_id = :postId');
    pdoErrorInfo($pdo, $statement);

    $statement->execute([
        ':postId' => $post['id']
    ]);

    $comments = $statement->fetchAll(PDO::FETCH_ASSOC);

    //Comments & Replies
    foreach ($comments as $comment) {
        $commentId = $comment['id'];

        $statement = $pdo->prepare('DELETE FROM replies WHERE comment_id = :commentId');
        pdoErrorInfo($pdo, $statement);

        $statement->execute([
            ':commentId' => $commentId
        ]);

        $statement = $pdo->prepare('DELETE FROM comments WHERE id = :commentId');
        pdoErrorInfo($pdo, $statement);

        $statement->execute([
            ':commentId' => $commentId
        ]);
    }

    //Like
    $statement = $pdo->prepare('DELETE FROM likes WHERE post_id = :postId');
    pdoErrorInfo($pdo, $statement);

    $statement->execute([
        ':postId' => $post['id']
    ]);

    //Post
    $statement = $pdo->prepare('DELETE FROM posts WHERE id = :postId');
    pdoErrorInfo($pdo, $statement);

    unlink(__DIR__ . '/../../uploads/posts/' . $post['image']);

    $statement->execute([
        ':postId' => $post['id']
    ]);

    $_SESSION['messages'] = 'post deleted';

    redirect('/profile.php?id=' . $id);
}

redirect('/');
