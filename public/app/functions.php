<?php

declare(strict_types=1);

// 'Exists' functions from guide 
if (!function_exists('redirect')) {

    function redirect(string $path)
    {
        header("Location: ${path}");
        exit;
    }
}

// Errors
function pdoErrorInfo(PDO $pdo, $statement): void
{
    if (!$statement) {
        die(var_dump($pdo->errorInfo()));
    }
}


function userIsLoggedIn(): bool
{
    return isset($_SESSION['user']);
}


function existsInDatabase(PDO $pdo, string $table, string $column, $value): bool
{
    $statement = $pdo->prepare('SELECT ' . $column . ' FROM ' . $table . ' WHERE ' . $column . ' = :value');
    if (!$statement) {
        die(var_dump($pdo->errorInfo()));
    }
    $statement->execute([
        ':value' => $value
    ]);

    if ($statement->fetch()) {
        return true;
    } else {
        return false;
    }
}

// Show posts in index.php ORDER BY DATE
function showPosts(PDO $pdo): array
{
    $statement = $pdo->query('SELECT posts.*, users.username, users.avatar FROM posts 
    INNER JOIN users 
    ON posts.user_id = users.id 
    ORDER BY posts.date DESC');
    if (!$statement) {
        die(var_dump($pdo->errorInfo()));
    }
    $statement->execute();
    $posts = $statement->fetchAll(PDO::FETCH_ASSOC);

    return $posts;
}


function getUserById(PDO $pdo, string $userId)
{
    // Getting user info
    $statement = $pdo->prepare('SELECT fullname, username, email, avatar, biography FROM users WHERE id = :id');
    if (!$statement) {
        die(var_dump($pdo->errorInfo()));
    }
    $statement->execute([
        ':id' => $userId
    ]);
    $user = $statement->fetch(PDO::FETCH_ASSOC);

    return $user;
}


function getPostById(PDO $pdo, string $postId)
{
    $statement = $pdo->prepare('SELECT posts.*, users.username, users.avatar FROM posts INNER JOIN users ON posts.user_id = users.id WHERE posts.id = :id');
    pdoErrorInfo($pdo, $statement);

    $statement->execute([
        ':id' => $postId
    ]);

    $post = $statement->fetch(PDO::FETCH_ASSOC);

    return $post;
}

function getPostsByUser(PDO $pdo, string $userId): array
{
    $statement = $pdo->prepare('SELECT posts.*, users.username, users.avatar FROM posts INNER JOIN users ON posts.user_id = users.id WHERE user_id = :user_id ORDER BY posts.date DESC');
    if (!$statement) {
        die(var_dump($pdo->errorInfo()));
    }
    $statement->execute([
        ':user_id' => $userId
    ]);
    $posts = $statement->fetchAll(PDO::FETCH_ASSOC);

    return $posts;
}


function isYourProfile(): bool
{
    return $_SESSION['user']['id'] === $_GET['id'];
}


// Number of likes for posts
function numOfLikes(PDO $pdo, string $postId): string
{
    $statement = $pdo->prepare('SELECT count(user_id) FROM likes WHERE post_id = :postId');
    if (!$statement) {
        die(var_dump($pdo->errorInfo()));
    }
    $statement->execute([
        ':postId' => $postId
    ]);
    $count = $statement->fetch()[0];
    return $count;
}

// Number of likes for comments
function numOfCommentLikes(PDO $pdo, string $commentId): string
{
    $statement = $pdo->prepare('SELECT count(user_id) FROM comment_likes WHERE comment_id = :commentId');
    if (!$statement) {
        die(var_dump($pdo->errorInfo()));
    }
    $statement->execute([
        ':commentId' => $commentId
    ]);
    $count = $statement->fetch()[0];
    return $count;
}

// Like & Unlike
function postIsLiked(PDO $pdo, string $userId, string $postId): bool
{
    $statement = $pdo->prepare('SELECT * FROM likes WHERE user_id = :userId AND post_id = :postId');
    if (!$statement) {
        die(var_dump($pdo->errorInfo()));
    }
    $statement->execute([
        ':userId' => $userId,
        ':postId' => $postId
    ]);

    if ($statement->fetch()) {
        return true;
    } else {
        return false;
    }
}

// Function to see if the user has already liked a comment
function commentIsLiked(PDO $pdo, string $userId, string $commentId): bool
{
    $statement = $pdo->prepare('SELECT * FROM comment_likes WHERE user_id = :userId AND comment_id = :commentId');
    if (!$statement) {
        die(var_dump($pdo->errorInfo()));
    }
    $statement->execute([
        ':userId' => $userId,
        ':commentId' => $commentId
    ]);

    if ($statement->fetch()) {
        return true;
    } else {
        return false;
    }
}

// Format likes
function formatLikes(string $numberOfLikes): string
{
    $int = intval($numberOfLikes);

    if ($int === 0) {
        return "";
    } else {
        return $numberOfLikes . " Likes";
    }
}

// Displaying errors & messages
function showErrorsAndMessages(): void
{
    if (isset($_SESSION['errors'])) {
        echo "<p class=\"errors\">" . $_SESSION['errors'] . "</p>";
        unset($_SESSION['errors']);
    }
    if (isset($_SESSION['messages'])) {
        echo "<p class=\"messages\">" . $_SESSION['messages'] . "</p>";
        unset($_SESSION['messages']);
    }
}

// Validation of images
function isValidImage(array $image): bool
{
    if ($image['type'] !== 'image/jpeg' && $image['type'] !== 'image/jpg' && $image['type'] !== 'image/png') {
        $_SESSION['errors'] = "The image filetype is not valid";
        return false;
    }
    return true;
}

// Checks if username is valid
function isValidUsername(PDO $pdo, string $username): bool
{
    if (existsInDatabase($pdo, 'users', 'username', $username)) {
        $_SESSION['errors'] = "This username is already taken";
        return false;
    }

    if (strpos($username, ' ') !== false) {
        $_SESSION['errors'] = 'Spaces not allowed';
        return false;
    }

    if (strlen($username) < 3 || strlen($username) > 12) {
        $_SESSION['errors'] = 'username has to be between 3-12 characters long';
        return false;
    }

    return true;
}


function createFileName(string $fileType): string
{
    $fileExt = '.' . explode('/', $fileType)[1];
    $fileName = uniqid("", true) . $fileExt;
    return $fileName;
}

// Get two latest comments
function getLatestComments(PDO $pdo, string $postId): array
{
    $statement = $pdo->prepare('SELECT * FROM comments WHERE post_id = :postId ORDER BY date DESC LIMIT 2');
    pdoErrorInfo($pdo, $statement);

    $statement->execute([
        ':postId' => $postId
    ]);

    $comments = $statement->fetchAll(PDO::FETCH_ASSOC);
    if (count($comments) === 2) {
        $comments = array_reverse($comments);
    }
    return $comments;
}

function getNumberOfComments(PDO $pdo, string $postId): string
{
    $statement = $pdo->prepare('SELECT count(*) FROM comments WHERE post_id = :postId');
    pdoErrorInfo($pdo, $statement);

    $statement->execute([
        ':postId' => $postId
    ]);

    $count = $statement->fetch()[0];

    return $count;
}

function showComments(PDO $pdo, string $commentId): string
{
    $statement = $pdo->prepare('SELECT count(*) FROM replies WHERE comment_id = :commentId');
    pdoErrorInfo($pdo, $statement);

    $statement->execute([
        ':commentId' => $commentId
    ]);

    $numberOfReplies = intval($statement->fetch()[0]);

    if ($numberOfReplies === 0) {
        return 'reply';
    } else {
        return "show replies";
    }
}


//Follow functions
function checkFollow($pdo, $followedId)
{
    $statement = $pdo->prepare('SELECT * FROM follows where user_id_follows = :id AND user_id_followed = :follow');

    $statement->execute([
        ':id' => $_SESSION['user']['id'],
        ':follow' => $followedId
    ]);

    $following = $statement->fetch(PDO::FETCH_ASSOC);

    return $following;
}


function getFollowing($pdo, $userId)
{
    $statement = $pdo->prepare('SELECT COUNT(user_id_follows) FROM follows WHERE user_id_follows = :id');
    $statement->execute([
        ':id' => $userId
    ]);
    $following = $statement->fetch(PDO::FETCH_ASSOC);
    return $following;
}

function getFollowers($pdo, $userId)
{
    $statement = $pdo->prepare('SELECT COUNT(user_id_followed) FROM follows WHERE user_id_followed = :id');
    $statement->execute([
        ':id' => $userId
    ]);
    $followers = $statement->fetch(PDO::FETCH_ASSOC);
    return $followers;
}
