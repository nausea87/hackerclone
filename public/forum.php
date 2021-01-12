<!-- TODO: Forum -->

<?php require __DIR__ . '/views/header.php';

if (!userIsLoggedIn()) {
    redirect('/');
} ?>

<h1>Comming soon...</h1>
<h2>Currently not working, refer to creating new posts</h2>
<?php showErrorsAndMessages(); ?>
<!--TODO: Finish build

<form action="app/posts/forumstore.php" method="post" enctype="multipart/form-data">

    <div class="formsection">
        <label for="topic">Topic:</label>
        <textarea name="topic" id="topic" cols="30" rows="1"></textarea>
    </div>
    <div class="formsection">
        <label for="description">Description:</label>
        <textarea name="description" id="description" cols="30" rows="10"></textarea>
    </div>
    <button type="submit">Create Post</button>
</form>

-->



<?php require __DIR__ . '/views/footer.php'; ?>