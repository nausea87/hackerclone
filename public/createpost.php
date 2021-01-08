<?php require __DIR__ . '/views/header.php'; ?>
<!-- Check if logged in, else redirst -1 -->
<?php if (!userIsLoggedIn()) {
    redirect('/');
} ?>
<!--Done-->
<h1>Create new post</h1>
<?php showErrorsAndMessages(); ?>
<form action="app/posts/store.php" method="post" enctype="multipart/form-data">

    <div class="form-section">
        <label for="image">Upload image:</label>
        <input class="input-file" type="file" name="image" id="image" accept=".jpg, .jpeg, .png" required>
    </div>
    <div class="form-section">
        <label for="description">Text:</label>
        <textarea name="description" id="description" cols="30" rows="10"></textarea>
    </div>
    <button type="submit">Create Post</button>
</form>

<?php require __DIR__ . '/views/footer.php'; ?>