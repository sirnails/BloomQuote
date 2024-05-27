<?php include_once './app/views/partials/navbar.php'; ?>

        <h1>Login</h1>
        <form method="POST" action="index.php?action=login">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
        </form>

        <a href="index.php?action=register" class="btn btn-secondary">Register</a>

<?php include_once './app/views/partials/footer.php'; ?>