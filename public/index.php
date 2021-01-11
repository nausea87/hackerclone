<?php require __DIR__ . '/views/header.php'; ?>
<!-- TODO: Fix link to individual (click a title) posts for forum feel -->
<?php showErrorsAndMessages(); ?>
<?php if (userIsLoggedIn()) : ?>

    <?php if (isset($_SESSION['greeting'])) : ?>
        <h1><?php echo $_SESSION['greeting']; ?></h1>
        <?php unset($_SESSION['greeting']); ?>
    <?php endif; ?>

    <?php $user = getUserById($pdo, $_SESSION['user']['id']); ?>
    <?php foreach (getAllPosts($pdo) as $post) : ?>
        <article class="post">

            <div class="user-container">
                <a href="/profile.php?id=<?php echo $post['user_id']; ?>">
                    <div class="avatar-username-container">
                        <div class="avatar-container">
                            <img class="avatar" src="/uploads/avatars/<?php echo $post['avatar']; ?>" alt="avatar">
                        </div>
                        <h2 class="username"><?php echo $post['username']; ?></h2>
                    </div>
                </a>
                <div class="date">
                    <p><?php echo $post['date']; ?></p>
                </div>
            </div>
            <!-- Fix title -->
            <div class="title">
                <p><?php echo $post['title']; ?></p>
            </div>

            <!-- Images -->
            <div class="post-image-container">
                <img class="post-image" src="/uploads/posts/<?php echo $post['image']; ?>" alt="post image">
            </div>

            <!--Likes -->
            <div class="like-box">
                <form class="like-form" action="app/posts/likes.php">
                    <input type="hidden" name="id" value="<?php echo $post['id'] ?>">
                    <button class="like-button" type="submit">
                        <?php echo isLikedBy($pdo, $_SESSION['user']['id'], $post['id']) ? "unlike" : "like"; ?>
                    </button>
                </form>
                <p><?php echo formatLikes(getNumberOfLikes($pdo, $post['id'])); ?></p>
            </div>

            <?php if (strlen($post['description']) !== 0) : ?>
                <p><?php echo $post['description']; ?></p>
            <?php endif; ?>

            <?php $numberOfComments = getNumberOfComments($pdo, $post['id']); ?>
            <?php if ($numberOfComments > 2) : ?>
                <form class="show-comments-form" action="">
                    <input type="hidden" name="id" value="<?php echo $post['id']; ?>">
                    <button class="show-comments-button">view
                        <?php echo $numberOfComments ?> comments
                    </button>
                </form>
            <?php endif; ?>

            <ul class="comment-list">
                <?php if (count(getLatestComments($pdo, $post['id'])) !== 0) : ?>
                    <?php foreach (getLatestComments($pdo, $post['id']) as $comment) : ?>
                        <?php $commenter = getUserById($pdo, $comment['user_id']); ?>
                        <article class="comment">
                            <li class="comment-container">
                                <a href="/profile.php?id=<?php echo $comment['user_id']; ?>">
                                    <div class="avatar-container">
                                        <img class="avatar" src="/uploads/avatars/<?php echo $commenter['avatar']; ?>" alt="avatar">
                                    </div>
                                </a>
                                <p><a href="/profile.php?id=<?php echo $comment['user_id']; ?>"><span><?php echo $commenter['username']; ?></span></a><?php echo $comment['comment']; ?></p>
                            </li>

                            <form class="show-replies-form" action="app/posts/showreplies.php" method="post">
                                <input type="hidden" name="id" value="<?php echo $comment['id']; ?>">

                                <!-- // Date for comments & replies scuffed af. TODO -->

                                <button class="reply-button" type="submit">
                                    <?php echo getReplyButtonText($pdo, $comment['id']); ?>
                                </button>

                            </form>
                            <ul class="reply-list"></ul>
                            <form class="reply-form" action="" method="post">
                                <div class="avatar-container">
                                    <img class="avatar" src="/uploads/avatars/<?php echo $user['avatar']; ?>" alt="avatar">
                                </div>
                                <input type="hidden" name="id" value="<?php echo $comment['id']; ?>">
                                <textarea name="reply" cols="45" rows="1" maxlength="140" placeholder="reply..." required></textarea>
                                <button type="submit">>>></button>
                            </form>
                        </article>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>

            <form class="comment-form" action="" method="post">
                <div class="avatar-container">
                    <img class="avatar" src="/uploads/avatars/<?php echo $user['avatar']; ?>" alt="avatar">
                </div>
                <input type="hidden" name="id" value="<?php echo $post['id']; ?>">
                <textarea name="comment" cols="45" rows="1" placeholder="Write comment..." required></textarea>
                <button type="submit">>>></button>
            </form>
        </article>
    <?php endforeach; ?>

<?php else : ?>
    <h1>Welcome to the truth...</h1>
    <h2>Create account:</h2>

    <form action="app/users/signup.php" method="post">
        <div class="form-section">
            <label for="fullName">Name</label>
            <input type="text" name="fullName" id="fullName" placeholder="name" required>
        </div>
        <div class="form-section">
            <label for="email">E-mail</label>
            <input type="email" name="email" id="email" placeholder="example@gmail.com" required>
        </div>
        <div class="form-section">
            <label for="username">Username</label>
            <input type="text" name="username" id="username" placeholder="username" required>
        </div>
        <div class="form-section">
            <label for="password">Password</label>
            <input type="password" name="password" id="password" placeholder="password" required>
        </div>
        <div class="form-section">
            <label for="confirmPassword">Confirm password</label>
            <input type="password" name="confirmPassword" id="confirmPassword" placeholder="password" required>
        </div>
        <button type="submit">>>></button>
    </form>

<?php endif; ?>

<?php require __DIR__ . '/views/footer.php'; ?>