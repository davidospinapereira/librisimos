<?php
    function cargar_footer($page)
    {
        if ($page == 'main' || $page == 'book-page')
        {
            $js_read_tool = '<!-- Javascript de la herramienta de lectura -->
        <script src="./view/js/read-tool-scripts.js"></script>';
        }
        else
        {
            $js_read_tool = '';
        }
        if ($page == 'edit-book' || $page == 'new-book' || $page == 'edit-author' || $page == 'new-author')
        {
            $js_tinymce = '<!-- Javascript de TinyMCE -->
        <script src="./view/js/tinymce/tinymce.min.js"></script>';
        }
        else
        {
            $js_tinymce = '';
        }
        $html_footer = 
        '
        <!-- Comienza footer -->
        <footer class="footer">
            <div class="footer-text">
                <p>Copyright &copy; 2024, para el Servicio Nacional de Aprendizaje.</p>
            </div>
            <div class="footer-back-to-top">
                <a href="#inicio"><i class="bx bx-up-arrow-alt"></i></a>
            </div>
        </footer>
        <!-- Termina footer -->

        <!-- Termina cuerpo del archivo-->

        <!-- Comienza Javascript -->
        <!-- JQuery -->
        <script src="./view/js/jquery-3.7.1.min.js"></script>
        <!-- Javascript de SweetAlert2 -->
        <script src="./view/js/sweetalert2.all.min.js"></script>
        <!-- Javascript general -->
        <script src="./view/js/general-scripts.js"></script>
        <!-- Javascript de la página específica -->
        <script src="./view/js/' . $page . '-scripts.js"></script>
        ' . $js_read_tool . '
        ' . $js_tinymce . '
        <!-- Termina Javascript -->

    </body>
</html>
        ';
        return $html_footer;
    }
?>