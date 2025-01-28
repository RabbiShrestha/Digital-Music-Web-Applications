<?php require page('includes/admin-header'); ?>

<section class="admin-content" style="min-height: 200px;">
    <h3>Dashboard</h3>
    <?php if (isset($_SESSION['USER']['username'])): ?>
        <p>Welcome, <?= htmlspecialchars($_SESSION['USER']['username']) ?>!</p>
    <?php else: ?>
        <p>Welcome, Admin!</p> <!-- fallback message if username is not set -->
    <?php endif; ?>
</section>

<?php require page('includes/admin-footer'); ?>
