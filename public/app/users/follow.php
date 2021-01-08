<?php

declare(strict_types=1);

require __DIR__ . '/../autoload.php';

if (isset($_POST['id'])) {

    $statement = $pdo->prepare('SELECT * FROM follows where user_id_follows = :id AND user_id_followed = :follow');
    $statement->execute([
        ':id' => $_SESSION['user']['id'],
        ':follow' => $_POST['id']
    ]);

    $following = $statement->fetch(PDO::FETCH_ASSOC);

    if ($following) {
        $statement = $pdo->prepare('DELETE FROM follows WHERE user_id_follows = :id AND user_id_followed = :follow');

        if (!$statement) {
            die(var_dump($pdo->errorInfo()));
        }

        $statement->execute([
            ':id' => $_SESSION['user']['id'],
            ':follow' => $_POST['id']
        ]);
        redirect('/profile.php?id=' . $_POST['id']);
    } else {

        $statement = $pdo->prepare('INSERT INTO follows(user_id_follows, user_id_followed) VALUES(:id, :follow)');
        $statement->execute([
            ':id' => $_SESSION['user']['id'],
            ':follow' => $_POST['id']
        ]);
        redirect('/profile.php?id=' . $_POST['id']);
    }
}
