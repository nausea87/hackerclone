<?php
//TODO: Fix this
declare(strict_types=1);

require __DIR__ . '/../autoload.php';

// if (!userIsLoggedIn()) {
//     redirect('/');
// }

// $id = $_SESSION['user']['id'];

// // If not your own account
// if ($_SESSION['user_id'] === $id) {
//     $_SESSION['errors'] = 'You can only delete your own account';
//     redirect('/');
// }

// $statement = $pdo->prepare('DELETE FROM users WHERE id = :id');
// pdoErrorInfo($pdo, $statement);

// $statement->execute([
//     ':id' => $id['id']
// ]);

// $_SESSION['messages'] = 'Account deleted';

// redirect('/');


if (userIsLoggedIn() && isset($_POST['delete-account-button'])) {
    $userId = $_SESSION['user']['id'];

    if ($_SESSION['user_id'] === $userId) {
        $_SESSION['errors'] = 'You can only delete your own account';
        redirect('/');
    } else {
        // Delete user from users table
        $statement = $pdo->prepare('DELETE FROM users WHERE id = :id');
        $statement->execute([
            ':id' => $userId
        ]);

        // Delete user from posts table
        $statement = $pdo->prepare('DELETE FROM posts WHERE user_id = :user_id');
        $statement->execute([
            ':user_id' => $userId
        ]);

        // Delete user from comments table
        $statement = $pdo->prepare('DELETE FROM comments WHERE user_id = :user_id');
        $statement->execute([
            ':user_id' => $userId
        ]);

        // Delete user from likes table
        $statement = $pdo->prepare('DELETE FROM likes WHERE user_id = :user_id');
        $statement->execute([
            ':user_id' => $userId
        ]);

        // Delete user's likes from comments.
        $statement = $pdo->prepare('DELETE FROM comment_likes WHERE user_id = :user_id');
        $statement->execute([
            ':user_id' => $userId
        ]);

        // Delete user's replies.
        $statement = $pdo->prepare('DELETE FROM replies WHERE user_id = :user_id');
        $statement->execute([
            ':user_id' => $userId
        ]);

        // Delete user from followers
        $statement = $pdo->prepare('DELETE FROM follows WHERE user_id_follows = :id');
        $statement->execute([
            ':id' => $userId
        ]);

        session_destroy();
        redirect('/');
    }
}
