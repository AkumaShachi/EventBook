<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$isFooter    = isset($isFooter)    ? $isFooter    : FALSE;
$pageScripts = isset($pageScripts) ? $pageScripts : array();
?>
    <?php if ($isFooter): ?>
    <!-- ===== FOOTER ===== -->
    <footer class="home-footer">
        <p>&copy; 2026 EventBook. All rights reserved.</p>
    </footer>
    <?php endif; ?>

    <div class="sidebar-backdrop" id="sidebarBackdrop"></div>

    <script>var BASE_URL = "<?php echo base_url(); ?>";</script>
    <script src="<?php echo base_url('assets/js/bootstrap/bootstrap.bundle.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/jquery-4.0.0.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/scrips.js'); ?>"></script>
    <?php foreach ($pageScripts as $_script): ?>
    <?php echo $_script; ?>
    <?php endforeach; ?>
</body>
</html>