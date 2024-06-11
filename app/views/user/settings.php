<?php include_once './app/views/partials/navbar.php'; ?>

<h1>User Settings</h1>
<form method="POST" action="index.php?action=settings">
    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
    <div class="form-group">
        <label for="dark_mode">Dark Mode</label>
        <input type="checkbox" id="dark_mode" name="dark_mode" <?php echo isset($settings['dark_mode']) && $settings['dark_mode'] ? 'checked' : ''; ?>>
    </div>
    <div class="form-group">
        <label for="delete_confirmation">Delete Confirmation</label>
        <input type="checkbox" id="delete_confirmation" name="delete_confirmation" <?php echo isset($settings['delete_confirmation']) && $settings['delete_confirmation'] ? 'checked' : ''; ?>>
    </div>
    <button type="submit" class="btn btn-primary">Save Settings</button>
</form>

<?php include_once './app/views/partials/footer.php'; ?>
