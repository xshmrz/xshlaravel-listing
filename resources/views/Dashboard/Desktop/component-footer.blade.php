<script src="assets/dashboard/js/jquery.min.js"></script>
<script src="assets/dashboard/js/codebase.app.min.js"></script>
<!-- THEME JS -->
<script src="assets/dashboard/js/plugins/datatables/dataTables.min.js"></script>
<script src="assets/dashboard/js/plugins/datatables-bs5/js/dataTables.bootstrap5.min.js"></script>
<script src="assets/dashboard/js/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="assets/dashboard/js/plugins/datatables-responsive-bs5/js/responsive.bootstrap5.min.js"></script>
<script src="assets/dashboard/js/plugins/datatables-buttons/dataTables.buttons.min.js"></script>
<script src="assets/dashboard/js/plugins/datatables-buttons-bs5/js/buttons.bootstrap5.min.js"></script>
<script src="assets/dashboard/js/plugins/datatables-buttons-jszip/jszip.min.js"></script>
<script src="assets/dashboard/js/plugins/datatables-buttons-pdfmake/pdfmake.min.js"></script>
<script src="assets/dashboard/js/plugins/datatables-buttons-pdfmake/vfs_fonts.js"></script>
<script src="assets/dashboard/js/plugins/datatables-buttons/buttons.print.min.js"></script>
<script src="assets/dashboard/js/plugins/datatables-buttons/buttons.html5.min.js"></script>
<!-- APP -->
<script src="assets/app.core.min.js"></script>
<script src="assets/app.min.js"></script>
<script>
    (function ($, document) {
        $.each(readyQ, function (index, func) {
            $(func);
        });
        $.each(bindReadyQ, function (index, func) {
            $(document).on('ready', func);
        });
    })(jQuery, document);
</script>
<script>
    Authorize.BtnLogin.click(function () {
        var data = xsh.getFormData(Authorize.FrmLogin);
        Authorize.Login({
                            data: data,
                            ok  : function (response) {
                                xsh.showNotification({
                                                         message : response.message,
                                                         callback: () => { xsh.redirectTo(Route.Dashboard); }
                                                     });
                            }
                        });
    });
    Authorize.BtnLogout.click(function () {
        Authorize.Logout({
                             data: null,
                             ok  : function (response) {
                                 xsh.showNotification({
                                                          message : response.message,
                                                          callback: () => { xsh.redirectTo(Authorize.Route.Dashboard.login); }
                                                      });
                             }
                         });
    });
    function initTableWithLoading(dataTableDisplaySelector, loaderText = 'Yükleniyor') {
        const $dataTableDisplay = $(dataTableDisplaySelector);
        const $wrapper          = $dataTableDisplay.parent();
        // Loading div oluştur
        const $loading          = $('<div class="table-loading" style="text-align:center; padding:20px;">' + loaderText + '</div>');
        // Mevcut tabloyu gizle, loading'i ekle
        $wrapper.append($loading);
        // Eğer tabloyu elle dolduruyorsan, tablo hazır olduğunda manuel olarak çağır:
        return {
            done: function () {
                $loading.remove();
                $dataTableDisplay.removeClass('d-none');
            }
        };
    }
    $(document).ready(function () {
        const tableLoader = initTableWithLoading('.dataTableDisplay');
        setTimeout(() => {
            tableLoader.done();
        }, 1000);
    });
</script>

