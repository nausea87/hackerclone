<?php
require __DIR__ . '/../app/autoload.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?php echo $config['title']; ?></title>



    <!--- unpkg.com -->
    <link rel="stylesheet" href="https://unpkg.com/sanitize.css@12.0.1/sanitize.css">
    <!--Everything went bananas without sanitizing--->
    <link rel="stylesheet" href="/assets/styles/main.css">
    <link rel="stylesheet" href="/assets/styles/navigation.css">
    <link rel="stylesheet" href="/assets/styles/post.css">
    <link rel="stylesheet" href="/assets/styles/comments.css">
    <link rel="stylesheet" href="/assets/styles/replies.css">
    <link rel="stylesheet" href="/assets/styles/forum.css">
    <link rel="stylesheet" href="/assets/styles/profile.css">
    <link rel="stylesheet" href="/assets/styles/edituser.css">
</head>

<body>
    <?php require __DIR__ . '/navigation.php'; ?>

    <div class="wrapper">