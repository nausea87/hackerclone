<?php require __DIR__ . '/views/header.php'; ?>

<?php if (!userIsLoggedIn()) {
    redirect('/');
} ?>

<?php showErrorsAndMessages(); ?>
<section class="edit-profile">

    <h1>Edit Profile</h1>
    <?php $user = getUserById($pdo, $_SESSION['user']['id']); ?>
    <article>
        <div class="flex-column">
            <div class="avatar-container">
                <img class="avatar" src="/uploads/avatars/<?php echo $user['avatar'] ?>" alt="avatar">
            </div>
            <button class="show-form-button">Want a new profile picture?</button>
        </div>

        <form action="app/users/editprofile.php" method="post" enctype="multipart/form-data">
            <div class="form-section">
                <input type="file" name="image" id="image" accept=".jpg, .jpeg, .png" required>
                <button type="submit">Save</button>
            </div>
        </form>
    </article>

    <article>
        <div class="flex-row">
            <p>Username:</p>
            <p><?php echo $user['username']; ?></p>
            <button class="show-form-button">Edit</button>
        </div>
        <form action="app/users/editprofile.php" method="post">
            <div class="form-section">
                <label for="username">New Username:</label>
                <input type="text" name="username" id="username" required>
                <button type="submit">Save</button>
            </div>
        </form>
    </article>

    <article>
        <div class="flex-row">
            <p>Biography:</p>
            <p><?php echo isset($user['biography']) ? substr($user['biography'], 0, 18) . '...' : 'no bio added'; ?></p>
            <button class="show-form-button">Edit</button>
        </div>
        <form action="app/users/editprofile.php" method="post">
            <div class="flex-column">
                <label for="biography">Update Biography:</label>
                <textarea maxlength="140" name="biography" id="biography" cols="50" rows="3" required><?php echo isset($user['biography']) ? $user['biography'] : ''; ?></textarea>
                <button type="submit">Save</button>
            </div>
        </form>
    </article>

    <article>
        <div class="flex-row">
            <p>Email:</p>
            <p><?php echo $user['email']; ?></p>
            <button class="show-form-button">Edit</button>
        </div>
        <form action="app/users/editprofile.php" method="post">
            <div class="form-section">
                <label for="email">Update email:</label>
                <input type="email" name="email" id="email" required>
                <button type="submit">Save</button>
            </div>
        </form>
    </article>

    <article>
        <div class="flex-row">
            <p>Password:</p>
            <p>*****</p>
            <button class="show-form-button">Edit</button>
        </div>
        <form action="app/users/editprofile.php" method="post">
            <div class="form-section">
                <label for="oldPassword">Old Password:</label>
                <input type="password" name="oldPassword" id="oldPassword" required>
            </div>
            <div class="form-section">
                <label for="newPassword">New Password:</label>
                <input type="password" name="newPassword" id="newPassword" required>
            </div>
            <div class="form-section">
                <label for="confirmNewPassword">Confirm New Password:</label>
                <input type="password" name="confirmNewPassword" id="confirmNewPassword" required>
            </div>
            <button type="submit">Save</button>
        </form>
    </article>


</section>

<?php require __DIR__ . '/views/footer.php'; ?>