<?php require __DIR__ . '/views/header.php';

if (!userIsLoggedIn()) {
    redirect('/');
} ?>

<?php showErrorsAndMessages(); ?>

<section class="edit-post">
    <h1>Edit Post</h1>
    <?php if (isset($_GET['id'])) : ?>
        <?php if (getPostById($pdo, $_GET['id'])) : ?>
            <?php $post = getPostById($pdo, $_GET['id']); ?>
            <?php if ($post['user_id'] === $_SESSION['user']['id']) : ?>
                <article class="post">
                    <div class="post-image-container">
                        <img class="post-image" src="/uploads/posts/<?php echo $post['image']; ?>" alt="post image">
                    </div>
                    <form action="app/posts/update.php" method="post" enctype="multipart/form-data">
                        <div class="form-section">
                            <div class="flex-row">
                                <input type="hidden" name="id" value="<?php echo $post['id']; ?>">
                                <label for="image">Upload new image:</label>
                                <input type="file" name="image" id="image" accept=".jpg, .jpeg, .png" required>
                                <button type="submit">Save</button>
                            </div>
                        </div>
                    </form>
                    <form action="app/posts/update.php" method="post">
                        <div class="flex-column">
                            <input type="hidden" name="id" value="<?php echo $post['id']; ?>">
                            <label for="description">Update Description:</label>
                            <textarea maxlength="140" name="description" id="description" cols="50" rows="3" required><?php echo isset($post['description']) ? $post['description'] : ''; ?></textarea>
                            <button type="submit">Save</button>
                        </div>
                    </form>
                    <form class="delete-post-form" action="app/posts/delete.php" method="post">
                        <input type="hidden" name="id" value="<?php echo $post['id']; ?>">
                        <button class="delete-post-button" type="submit">Delete Post</button>
                    </form>
                </article>
            <?php else : ?>
                <p>Not your post</p>
            <?php endif; ?>
        <?php else : ?>
            <p>Post not found</p>
        <?php endif; ?>
    <?php else : ?>
        <p>No post selected</p>
    <?php endif; ?>
</section>

<?php require __DIR__ . '/views/footer.php'; ?>