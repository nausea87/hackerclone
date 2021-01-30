<?php require __DIR__ . '/views/header.php'; ?>
<!-- TODO: Fix link to individual (click a title) posts for forum feel -->
<?php showErrorsAndMessages(); ?>
<?php if (userIsLoggedIn()) : ?>

    <?php if (isset($_SESSION['greeting'])) : ?>
        <h1><?php echo $_SESSION['greeting']; ?></h1>
        <?php unset($_SESSION['greeting']); ?>
    <?php endif; ?>

    <?php $user = getUserById($pdo, $_SESSION['user']['id']); ?>


    <!--- TODO: Work on maximize/minimize view -->

    <?php foreach (showPosts($pdo) as $post) : ?>
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
            <!-- Title -->
            <div class="title">
                <p><?php echo $post['title']; ?></p>
            </div>

            <!-- Images -->
            <div class="post-image-container">
                <img class="post-image" src="/uploads/posts/<?php echo $post['image']; ?>" alt="post image">
            </div>

            <!--Likes -->
            <div class="like">
                <form class="like-form" action="app/posts/like-inserts.php">
                    <input type="hidden" name="id" value="<?php echo $post['id'] ?>">
                    <!-- Fix green / red -->
                    <p><?php echo formatLikes(numOfLikes($pdo, $post['id'])); ?></p>
                    <button class="like-btn" type="submit">
                        <?php echo postIsLiked($pdo, $_SESSION['user']['id'], $post['id']) ? "unlike" : "like"; ?>
                    </button>
                </form>
            </div>

            <!--Content-->
            <p><?php echo $post['description']; ?></p>


            <!--Comments-->
            <form class="show-comments-form" action="">
                <input type="hidden" name="id" value="<?php echo $post['id']; ?>">
                <button class="show-comments-button">show comments
                </button>
            </form>

            <ul class="comment-list">
                <!-- If comments exist -->
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
                                <p>
                                    <a href="/profile.php?id=<?php echo $comment['user_id']; ?>"><span><?php echo $commenter['username']; ?>
                                        </span></a><?php echo $comment['comment']; ?>

                                    <!-- Fix edits -->
                                    <!-- <form class="edit-comment" action="">
                                    <input type="hidden" name="id" value="">
                                    <button class="edit-comment">edit</button>
                                    </form> -->
                                </p>

                            </li>

                            <p><?php echo $comment['date'] ?></p>

                            <!--Comment Likes -->
                            <div class="likes">
                                <form class="comment-like-form" action="app/posts/comment-like-inserts.php">
                                    <input type="hidden" name="id" value="<?php echo $comment['id'] ?>">
                                    <!-- Fix green / red -->
                                    <button class="like-btn" type="submit">
                                        <?php echo commentIsLiked($pdo, $_SESSION['user']['id'], $comment['id']) ? "unlike" : "like"; ?>
                                    </button>
                                </form>
                                <p><?php echo formatLikes(numOfCommentLikes($pdo, $comment['id'])); ?></p>
                            </div>

                            <form class="show-replies-form" action="app/posts/showreplies.php" method="post">
                                <input type="hidden" name="id" value="<?php echo $comment['id']; ?>">

                                <!-- Fix: Date for comments & replies  -->

                                <button class="reply-btn" type="submit">
                                    <?php echo showComments($pdo, $comment['id']); ?>
                                </button>

                                <!---Fix edits for comments & Replies -->

                            </form>
                            <ul class="reply-list"></ul>
                            <form class="reply-form" action="" method="post">
                                <div class="avatar-container">
                                    <img class="avatar" src="/uploads/avatars/<?php echo $user['avatar']; ?>" alt="avatar">
                                </div>
                                <input type="hidden" name="id" value="<?php echo $comment['id']; ?>">
                                <textarea name="reply" cols="20" rows="1" placeholder="reply..." required></textarea>
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
                <textarea name="comment" cols="45" rows="1" placeholder="Comment..." required></textarea>
                <button type="submit">>>></button>
            </form>
        </article>
    <?php endforeach; ?>

<?php else : ?>
    <h1>w3lC0m3 stR4nG3er...</h1>
    <h2>cR34t3 4cc0uNt:</h2>

    <form action="app/users/signup.php" method="post">
        <div class="formsection">
            <label for="fullName">Name</label>
            <input type="text" name="fullName" id="fullName" placeholder="name" required>
        </div>
        <div class="formsection">
            <label for="email">E-mail</label>
            <input type="email" name="email" id="email" placeholder="example@gmail.com" required>
        </div>
        <div class="formsection">
            <label for="username">Username</label>
            <input type="text" name="username" id="username" placeholder="username" required>
        </div>
        <div class="formsection">
            <label for="password">Password</label>
            <input type="password" name="password" id="password" placeholder="password" required>
        </div>
        <div class="formsection">
            <label for="confirmPassword">Confirm password</label>
            <input type="password" name="confirmPassword" id="confirmPassword" placeholder="password" required>
        </div>
        <button type="submit">>>></button>
    </form>

<?php endif; ?>

<?php require __DIR__ . '/views/footer.php'; ?>