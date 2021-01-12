<?php require __DIR__ . '/views/header.php';

if (!userIsLoggedIn()) {
    redirect('/');
} ?>

<?php showErrorsAndMessages(); ?>
<section class="profile">
    <?php if (isset($_GET['id'])) : ?>
        <?php if (getUserById($pdo, $_GET['id']) !== false) : ?>
            <?php $user = getUserById($pdo, $_GET['id']); ?>

            <article class="user-info">
                <div class="avatar-container">
                    <img class="avatar" src="uploads/avatars/<?php echo $user['avatar']; ?>" alt="avatar">
                </div>
                <div class="bio-container">
                    <h2><?php echo $user['username']; ?></h2>
                    <p><?php echo isset($user['biography']) ? $user['biography'] : ''; ?></p>
                </div>
            </article>

            <?php if (isYourProfile()) : ?>
                <a href="/editprofile.php"><button>Edit Profile</button></a>

            <?php else : ?>
                <div class="followers-container">
                    <form action="app/users/follow.php" method="post">
                        <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
                        <?php if (!checkFollow($pdo, $_GET['id'])) : ?>
                            <button class="follow" type="submit"> Follow
                            </button>
                        <?php else : ?>
                            <button class="unfollow" type="submit">Unfollow</button>
                        <?php endif; ?>
                    </form>
                <?php endif; ?>

                <div class="followers">
                    <?php foreach (getFollowers($pdo, $_GET['id']) as $followers) : ?>
                        <p>Followers: <?php echo $followers; ?></p>
                    <?php endforeach; ?>
                    <?php foreach (getFollowing($pdo, $_GET['id']) as $following) : ?>
                        <p>Following: <?php echo $following; ?></p>
                    <?php endforeach; ?>

                </div>
                </div>

                <?php foreach (getPostsByUser($pdo, $_GET['id']) as $post) : ?>

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
                            <p><?php echo $post['date']; ?></p>
                        </div>

                        <div class="post-image-container">
                            <img class="post-image" src="/uploads/posts/<?php echo $post['image']; ?>" alt="post image">
                        </div>

                        <div class="like-box">
                            <form class="like-form" action="app/posts/likes.php">
                                <input type="hidden" name="id" value="<?php echo $post['id'] ?>">
                                <button class="like-button" type="submit">
                                    <?php echo postIsLiked($pdo, $_SESSION['user']['id'], $post['id']) ? "unlike" : "like"; ?>
                                </button>
                            </form>
                            <p><?php echo formatLikes(getNumberOfLikes($pdo, $post['id'])); ?></p>
                        </div>

                        <?php if (strlen($post['description']) !== 0) : ?>
                            <p><?php echo $post['description']; ?></p>
                        <?php endif; ?>


                        <form class="show-comments-form" action="">
                            <input type="hidden" name="id" value="<?php echo $post['id']; ?>">
                            <button class="show-comments-button">show more comments</button>
                        </form>


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

                                        <form class="show-replies-form" action="app/posts/getallreplies.php" method="post">
                                            <input type="hidden" name="id" value="<?php echo $comment['id'] ?>">
                                            <button class="reply-button" type="submit"><?php echo getReplyButtonText($pdo, $comment['id']); ?></button>
                                            <!--Edit comment here, couldn't re-fix in time.--->
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
                            <textarea name="comment" cols="45" rows="1" maxlength="140" placeholder="Leave a comment..." required></textarea>
                            <button type="submit">>>></button>
                        </form>
                        <?php if (isYourProfile()) : ?>
                            <a href="/editpost.php?id=<?php echo $post['id'] ?>">
                                <button>Edit Post</button></a>
                        <?php endif; ?>
                    </article>

                <?php endforeach; ?>
            <?php else : ?>
                <p>User not found</p>
            <?php endif; ?>
        <?php else : ?>
            <p>No user selected</p>
        <?php endif; ?>
</section>

<?php require __DIR__ . '/views/footer.php'; ?>