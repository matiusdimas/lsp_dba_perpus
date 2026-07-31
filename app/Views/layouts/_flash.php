<?php
/**
 * Reusable View Partial: Flash Messages (Session Alerts)
 * Usage: require_once in any view that needs to display session flash/error messages.
 */
?>
<?php if(isset($_SESSION['flash'])): ?>
    <div class="alert alert-success"><?= $_SESSION['flash']; unset($_SESSION['flash']); ?></div>
<?php endif; ?>
<?php if(isset($_SESSION['error'])): ?>
    <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
<?php endif; ?>
