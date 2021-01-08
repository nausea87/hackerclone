<?php

declare(strict_types=1);

header('Content-type: application/json');

require __DIR__ . '/../autoload.php';

if (isset($_POST['search'])) {
    //Search functions for names similar to search.

    $valid = true;

    $statement = $pdo->prepare('SELECT * FROM users WHERE username LIKE :query');
    $search = trim(filter_var('%' . $_POST['search'] . '%', FILTER_SANITIZE_STRING));
    $statement->execute([
        ':query' => $search
    ]);

    $response = $statement->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($response);
    exit;
}
