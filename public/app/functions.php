<?php

declare(strict_types=1);

// 'Exists' functions from guide 
if (!function_exists('redirect')) {
    /**
     * Redirect the user to given path.
     *
     * @param string $path
     *
     * @return void
     */
    function redirect(string $path)
    {
        header("Location: ${path}");
        exit;
    }
}

/**
 * If statement is false, die dump $pdo->errorInfo.
 *
 * @param PDO $pdo
 * @param mixed $statement
 * @return void
 */
function pdoErrorInfo(PDO $pdo, $statement): void
{
    if (!$statement) {
        die(var_dump($pdo->errorInfo()));
    }
}



/**
 * check if SESSION user is set
 *
 * @return boolean
 */
function userIsLoggedIn(): bool
{
    return isset($_SESSION['user']);
}

/**
 * Check if a value exists in the database in a specific column
 *
 * @param PDO $pdo
 * @param string $table
 * @param string $column
 * @param mixed $value
 * @return boolean
 */
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

/**
 * Get all posts and the username and avatar of the poster
 *
 * @param PDO $pdo
 * @return array
 */
function getAllPosts(PDO $pdo): array
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

/**
 * Get user from database, returns array if user id exists.
 * If id doesnt exist, false is returned.
 *
 * @param PDO $pdo
 * @param integer $userId
 * @return mixed
 */
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

/**
 * Get a post from the database and username/avatar of poster, returns an array if the post exists, otherwize false is returned.
 *
 * @param PDO $pdo
 * @param string $postId
 * @return mixed
 */
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

/**
 * Get all posts from a user
 *
 * @param PDO $pdo
 * @param integer $userId
 * @return array
 */
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

/**
 * Check if the page belongs to the logged in user.
 * Use only if $_GET['id'] is set and user is logged in.
 *
 * @return boolean
 */
function isYourProfile(): bool
{
    return $_SESSION['user']['id'] === $_GET['id'];
}

function isYourComment($pdo, $userId, $postId): bool
{
    $statement = $pdo->prepare('SELECT * FROM comments WHERE id = :postId');
    pdoErrorInfo($pdo, $statement);

    $statement->execute([
        ':postId' => $postId
    ]);

    $post = $statement->fetch(PDO::FETCH_ASSOC);

    if ($post['user_id'] === $_SESSION['user']['id']) {
        return true;
    } else {
        return false;
    }
}

/**
 * Get the number of likes on a post
 *
 * @param PDO $pdo
 * @param string $postId
 * @return string
 */
function getNumberOfLikes(PDO $pdo, string $postId): string
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

/**
 * check if a post is liked by a specific user
 *
 * @param PDO $pdo
 * @param string $userId
 * @param string $postId
 * @return boolean
 */
function isLikedBy(PDO $pdo, string $userId, string $postId): bool
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

/**
 * Format the number of likes to a string to be displayed on the front-end
 *
 * @param string $numberOfLikes
 * @return string
 */
function formatLikes(string $numberOfLikes): string
{
    $int = intval($numberOfLikes);

    if ($int === 0) {
        return "";
    }
    if ($int === 1) {
        return "1 Like";
    }
    if ($int > 1) {
        return $numberOfLikes . " Likes";
    }
}

/**
 * Echo any errors or messages set in $_SESSION wrapped in a <p> tag.
 * Unset them after they have been printed.
 * The <p> tags have the css classes "errors" and "messages".
 *
 * @return void
 */
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

/**
 * Check if uploaded image is of valid type and size, if false $_SESSION['errors'] is set accordingly.
 *
 * @param array $image
 * @return boolean
 */
function isValidImage(array $image): bool
{
    if ($image['type'] !== 'image/jpeg' && $image['type'] !== 'image/jpg' && $image['type'] !== 'image/png') {
        $_SESSION['errors'] = "The image filetype is not valid";
        return false;
    }

    if ($image['size'] > '3000000') {
        $_SESSION['errors'] = "The image file is too big, 3mb is max";
        return false;
    }

    return true;
}

/**
 * Check if username is taken, contains spaces or is of invalid length, if false $_SESSION['errors'] is set accordingly.
 *
 * @param PDO $pdo
 * @param string $username
 * @return boolean
 */
function isValidUsername(PDO $pdo, string $username): bool
{
    if (existsInDatabase($pdo, 'users', 'username', $username)) {
        $_SESSION['errors'] = "username is already registered";
        return false;
    }

    if (strpos($username, ' ') !== false) {
        $_SESSION['errors'] = 'no spaces allowed in username';
        return false;
    }

    if (strlen($username) < 3 || strlen($username) > 15) {
        $_SESSION['errors'] = 'username has to be between 3-15 characters long';
        return false;
    }

    return true;
}

/**
 * Create unique file name with extension from the uploaded files type.
 *
 * @param string $fileType
 * @return string
 */
function createFileName(string $fileType): string
{
    $fileExt = '.' . explode('/', $fileType)[1];
    $fileName = uniqid("", true) . $fileExt;
    return $fileName;
}

/**
 * Get the 2 latest comments on the post, returns an array with 0-2 items.
 *
 * @param PDO $pdo
 * @param string $postId
 * @return array
 */
function getLatestComments(PDO $pdo, string $postId): array
{
    $statement = $pdo->prepare('SELECT * FROM comments WHERE post_id = :postId ORDER BY date DESC LIMIT 2');
    pdoErrorInfo($pdo, $statement);

    $statement->execute([
        ':postId' => $postId
    ]);

    $comments = $statement->fetchAll(PDO::FETCH_ASSOC);

    //place the latest comment at the bottom
    if (count($comments) === 2) {
        $comments = array_reverse($comments);
    }

    return $comments;
}

/**
 * Get the number of replies on a post
 *
 * @param PDO $pdo
 * @param string $postId
 * @return string
 */
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

/**
 * Count number of replies on a comment and format to a string to be displayed on reply-button.
 * returns 'reply', 'show 1 reply' or 'show X replies'.
 *
 * @param PDO $pdo
 * @param string $commentId
 * @return string
 */
function getReplyButtonText(PDO $pdo, string $commentId): string
{
    $statement = $pdo->prepare('SELECT count(*) FROM replies WHERE comment_id = :commentId');
    pdoErrorInfo($pdo, $statement);

    $statement->execute([
        ':commentId' => $commentId
    ]);

    $numberOfReplies = intval($statement->fetch()[0]);

    if ($numberOfReplies === 0) {
        return 'reply';
    } elseif ($numberOfReplies === 1) {
        return 'show 1 reply';
    } else {
        return "show $numberOfReplies replies";
    }
}

/**
 * Check if user is following user profile you're watching
 *
 * @param pdo $pdo
 * @param int $followedId
 * @return void
 */
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
