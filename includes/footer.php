    </main>
    <footer class="fixed-bottom bg-light text-muted text-center py-2">
        Made with &hearts; by Marco Spitzbarth
    </footer>
    <script src="../assets/js/jquery.min.js"></script>
    <script src="../assets/js/popper.min.js"></script>
    <script src="../assets/js/bootstrap.min.js"></script>
    <script src="../assets/js/scripts.js"></script>
    <script src="../assets/js/masonry.pkgd.min.js"></script>
    <script src="../assets/js/imagesloaded.pkgd.min.js"></script>

</body>
</html>

<script>
    $(document).ready(function () {
        // Initialize Masonry
        var $grid = $('.card-grid').masonry({
            // Options for Masonry
            itemSelector: '.card',
            columnWidth: '.card-sizer',
            gutter: 20,
        });

        // Layout Masonry after all images have loaded
        $grid.imagesLoaded().always(function () {
            $grid.masonry('layout');
        });
    });
</script>
