<footer class="text-center mt-5 py-3 bg-light">
    <small>
        For any queries, kindly mail at <strong>forestry[at]gmail[dot]com</strong><br>
        © 2026 Haryana Government. All Rights Reserved.
    </small>
</footer>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<!-- Bootstrap 5 + Other JS -->
<?php if (!empty($js_files)): ?>
    <?php foreach ($js_files as $js): ?>
        <script src="<?= $js ?>"></script>
    <?php endforeach; ?>
<?php endif; ?>

</body>
</html>
