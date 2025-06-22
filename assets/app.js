let activeModal = null;
const xsh       = {
    // Set Form Fields From Data Object
    setFormData(form, data) {
        const $form = $(form);
        this.clearForm($form);
        $form.find('input, select, textarea').each(function () {
            const name = $(this).attr('name');
            if (!name) return;
            if ($(this).is('input') || $(this).is('select')) {
                $(this).val(data[name]).trigger('change');
            }
            else if ($(this).is('textarea')) {
                $(this).text(data[name]);
            }
        });
    },
    // Get Form Data As Object
    getFormData(form) {
        return $(form).serializeArray().reduce((acc, field) => {
            acc[field.name] = field.value;
            return acc;
        }, {});
    },
    // Reset And Clear Form
    clearForm(form) {
        const $form = $(form);
        $form.trigger('reset');
        $form.find('select').each(function () {
            $(this).val($(this).find('option:first').val()).trigger('change');
        });
    },
    // Show Modal And Store Reference
    showModal(modal) {
        $('.modal').modal('hide');
        activeModal = modal;
        activeModal.modal('show');
    },
    // Hide Modal
    hideModal() {
        if (activeModal) activeModal.modal('hide');
    },
    // Redirect To Url
    redirectTo(route, delay = 0) {
        setTimeout(() => {
            window.location.href = route;
        }, delay);
    },
    // Reload Page
    refreshPage(delay = 0) {
        setTimeout(() => {
            location.reload();
        }, delay);
    },
    // Display Toast Notification
    showNotification({message, duration = 2000, position = 'top-center', type = 'dark', callback} = {}) {
        const colorMap      = {
            success: 'var(--bs-success)',
            warning: 'var(--bs-warning)',
            danger : 'var(--bs-dark)',
            primary: 'var(--bs-primary)',
            dark   : 'var(--bs-dark)'
        };
        const $notification = $('<div></div>')
            .html(`<span>${message}</span><div id="progress-bar"></div>`)
            .css({
                     display                                                                             : 'none',
                     minWidth                                                                            : '200px',
                     position                                                                            : 'fixed',
                     padding                                                                             : '20px',
                     backgroundColor                                                                     : colorMap[type] || colorMap.success,
                     color                                                                               : 'white',
                     borderRadius                                                                        : '5px',
                     zIndex                                                                              : 9999,
                     textAlign                                                                           : position.includes('center') ? 'center' : 'left',
                     [position.includes('top') ? 'top' : 'bottom']                                       : '20px',
                     [position.includes('left') ? 'left' : position.includes('right') ? 'right' : 'left']: '50%',
                     transform                                                                           : position.includes('center') ? 'translateX(-50%)' : ''
                 });
        const $overlay      = $('<div></div>').css({position: 'fixed', top: 0, left: 0, width: '100%', height: '100%', zIndex: 1000});
        $('body').append($overlay, $notification);
        $notification.fadeIn(() => $notification.find('#progress-bar').css({width: '0%'}));
        $notification.on('click', closeNotification);
        const timeoutId = setTimeout(closeNotification, duration);
        function closeNotification() {
            clearTimeout(timeoutId);
            $notification.fadeOut(() => {
                $notification.remove();
                $overlay.remove();
                if (typeof callback === 'function') callback();
            });
        }
    },
    // Global Event Bindings And Ajax Spinner
    initBaseEvents() {
        $(document).on('hidden.bs.modal', '.modal', () => {
            this.clearForm($('.modal form'));
        });
        let $activeElement = null;
        let originalHtml   = '';
        $(document).on({
                           ajaxStart(event) {
                               $activeElement = $(event.target.activeElement);
                               if ($activeElement.is(':button, a') && !$activeElement.hasClass('dropdown-item') && !$activeElement.hasClass('nav-main-link') && !$activeElement.hasClass('page-link') && !$activeElement.hasClass('list-group-item')) {
                                   originalHtml = $activeElement.html();
                                   $activeElement.html('<i class="fa fa-spinner fa-spin"></i>');
                               }
                           },
                           ajaxStop() {
                               if ($activeElement && originalHtml) {
                                   $activeElement.html(originalHtml);
                               }
                           }
                       });
    }
};
// Init Events
xsh.initBaseEvents();

