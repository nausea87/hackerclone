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
        $_SESSION['errors'] = 'This is not your post';
        redirect('/');
    }

    //View main
    $statement = $pdo->prepare('SELECT * FROM comments WHERE post_id = :postId');
    pdoErrorInfo($pdo, $statement);

    $statement->execute([
        ':postId' => $post['id']
    ]);

    $comments = $statement->fetchAll(PDO::FETCH_ASSOC);

    //Post (working)
    $statement = $pdo->prepare('DELETE FROM posts WHERE id = :postId');
    pdoErrorInfo($pdo, $statement);

    unlink(__DIR__ . '/../../uploads/posts/' . $post['image']);

    $statement->execute([
        ':postId' => $post['id']
    ]);

    $_SESSION['messages'] = 'Post deleted';

    redirect('/profile.php?id=' . $id);

    //Comments & Replies
    foreach ($comments as $comment) {
        $commentId = $comment['id'];

        $statement = $pdo->prepare('DELETE FROM replies WHERE comment_id = :commentId');
        pdoErrorInfo($pdo, $statement);

        $statement->execute([
            ':commentId' => $commentId
        ]);

        $_SESSION['messages'] = 'Reply deleted';
        redirect('/profile.php?id=' . $id);
        //////////////////////////////////
        $statement = $pdo->prepare('DELETE FROM comments WHERE id = :commentId');
        pdoErrorInfo($pdo, $statement);

        $statement->execute([
            ':commentId' => $commentId
        ]);

        $_SESSION['messages'] = 'Comment deleted';
        redirect('/profile.php?id=' . $id);
    }

    //Likes (working)
    $statement = $pdo->prepare('DELETE FROM likes WHERE post_id = :postId');
    pdoErrorInfo($pdo, $statement);

    $statement->execute([
        ':postId' => $post['id']
    ]);
}

redirect('/');
