<?php
// includes/footer.php
// Closes body/html and injects Bootstrap + Chart.js + custom JS.
if (!isset($base)) {
    $base = (
        strpos($_SERVER['PHP_SELF'], '/admin/')   !== false ||
        strpos($_SERVER['PHP_SELF'], '/student/') !== false
    ) ? '../' : '';
}
?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script src="<?php echo $base; ?>assets/js/main.js"></script>
<?php if (isset($extra_js)) echo $extra_js; ?>
</body>
</html>