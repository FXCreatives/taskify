<?php
session_start();
$activePage = 'help';
$pageTitle = 'Help';
include 'includes/header.php';
?>
<div class="form-container">
    <div class="page-header"><div><h1 class="page-title">Help</h1><p class="page-subtitle">Short answers to the most common Taskify questions.</p></div></div>
    <section class="panel"><div class="panel-header"><div class="panel-title">Frequently asked questions</div></div><div style="padding:0 20px"><div style="padding:16px 0;border-bottom:1px solid var(--border)"><strong>How do I create a task?</strong><p class="page-subtitle">Use New Task in the topbar or Add Task in the sidebar. Title and due date are required.</p></div><div style="padding:16px 0;border-bottom:1px solid var(--border)"><strong>How does search work?</strong><p class="page-subtitle">Search matches task titles and descriptions and can be combined with both filters.</p></div><div style="padding:16px 0"><strong>Can I switch themes?</strong><p class="page-subtitle">Use the theme button in the topbar; the selection is saved on this device.</p></div></div></section>
</div>
<?php include 'includes/footer.php'; ?>
