<?php require __DIR__ . '/views/header.php'; ?>

<article>
    <h1>Login</h1>

    <?php showErrorsAndMessages(); ?>
    <form action="app/users/login.php" method="post">
        <div class="form-section">
            <label for="email">Email</label>
            <input type="email" name="email" placeholder="youremail@mail.com" required>
            <small>Enter your email address.</small>
        </div>

        <div class="form-section">
            <label for="password">Password</label>
            <input type="password" name="password" required>
            <small>Enter your password.</small>
        </div>

        <button type="submit">Login</button>
    </form>
</article>

<?php require __DIR__ . '/views/footer.php'; ?>