<?php require __DIR__ . '/views/header.php'; ?>

<article>
    <h1>Login</h1>

    <?php showErrorsAndMessages(); ?>
    <form action="app/users/login.php" method="post">
        <div class="form-section">
            <label for="email">Email</label>
            <input type="email" name="email" placeholder="email@gmail.com" required>

        </div>

        <div class="form-section">
            <label for="password">Password</label>
            <input type="password" name="password" placeholder="password" required>

        </div>

        <button type="submit">Login</button>
    </form>
</article>

<?php require __DIR__ . '/views/footer.php'; ?>