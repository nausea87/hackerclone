<?php require __DIR__ . '/views/header.php';
// Check if logged in, else redirst -1
if (!userIsLoggedIn()) {
    redirect('/');
} ?>

<h1>Create new post</h1>
<?php showErrorsAndMessages(); ?>
<form action="app/posts/store.php" method="post" enctype="multipart/form-data">

    <div class="form-section">
        <label for="image">Title:</label>
        <textarea name="title" id="title" cols="30" rows="1"></textarea>
    </div>
    <div class="form-section">
        <label for="image">Image:</label>
        <input class="input-file" type="file" name="image" id="image" accept=".jpg, .jpeg, .png" required>
    </div>
    <div class="form-section">
        <label for="description">Post:</label>
        <textarea name="description" id="description" cols="30" rows="10"></textarea>
    </div>
    <button type="submit">Submit</button>
</form>

<?php require __DIR__ . '/views/footer.php'; ?>