<?php
session_start();
$activePage = 'settings';
$pageTitle = 'Settings';
include 'includes/header.php';
?>
<div class="form-container">
    <div class="page-header"><div><h1 class="page-title">Settings</h1><p class="page-subtitle">Workspace preferences for this device.</p></div></div>
    <section class="panel"><div class="panel-header"><div><div class="panel-title">Appearance</div><div class="panel-subtitle">Your preference is remembered on this device.</div></div></div><div style="padding:20px"><button type="button" class="btn btn-ghost" id="settingsThemeToggle">Toggle dark mode</button></div></section>
</div>
<script>document.getElementById('settingsThemeToggle').addEventListener('click',function(){document.getElementById('themeToggle').click()})</script>
<?php include 'includes/footer.php'; ?>
