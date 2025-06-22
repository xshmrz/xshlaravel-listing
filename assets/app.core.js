const Appointment = {
    // BUTTON
    BtnGet   : $('.AppointmentBtnGet'),
    BtnGetAll: $('.AppointmentBtnGetAll'),
    BtnCreate: $('.AppointmentBtnCreate'),
    BtnUpdate: $('.AppointmentBtnUpdate'),
    BtnDelete: $('.AppointmentBtnDelete'),

    // FORM
    FrmGet   : $('.AppointmentFrmGet'),
    FrmGetAll: $('.AppointmentFrmGetAll'),
    FrmCreate: $('.AppointmentFrmCreate'),
    FrmUpdate: $('.AppointmentFrmUpdate'),
    FrmDelete: $('.AppointmentFrmDelete'),

    // MODAL
    MdlGet   : $('.AppointmentMdlGet'),
    MdlGetAll: $('.AppointmentMdlGetAll'),
    MdlCreate: $('.AppointmentMdlCreate'),
    MdlUpdate: $('.AppointmentMdlUpdate'),
    MdlDelete: $('.AppointmentMdlDelete'),

    // MODAL BUTTON
    BtnMdlGet   : $('.AppointmentBtnMdlGet'),
    BtnMdlGetAll: $('.AppointmentBtnMdlGetAll'),
    BtnMdlCreate: $('.AppointmentBtnMdlCreate'),
    BtnMdlUpdate: $('.AppointmentBtnMdlUpdate'),
    BtnMdlDelete: $('.AppointmentBtnMdlDelete'),
    // ROUTE
    Route : {
        Api: {
            index:      '/api/appointment',
            store:      '/api/appointment',
            show:       (id) => '/api/appointment/'+id,
            update:     (id) => '/api/appointment/'+id,
            destroy:    (id) => '/api/appointment/'+id,
        },
        Dashboard: {
            index:      '/dashboard/appointment',
            store:      '/dashboard/appointment',
            create:     '/dashboard/appointment/create',
            show:       (id) => '/dashboard/appointment/'+id,
            update:     (id) => '/dashboard/appointment/'+id,
            edit:       (id) => '/dashboard/appointment/'+id+'/edit',
        },
        Panel: {
            index:      '/panel/appointment',
            store:      '/panel/appointment',
            create:     '/panel/appointment/create',
            show:       (id) => '/panel/appointment/'+id,
            update:     (id) => '/panel/appointment/'+id,
            edit:       (id) => '/panel/appointment/'+id+'/edit',
        },
        Site: {
            index:      '/appointment',
            store:      '/appointment',
            create:     '/appointment/create',
            show:       (id) => '/appointment/'+id,
            update:     (id) => '/appointment/'+id,
            edit:       (id) => '/appointment/'+id+'/edit',
        },
    },
    // AJAX
    req({type, url, data = {}, ok, err}) {
        $.ajax({
            type,
            url,
            data,
            success: res => ok ? ok(res) : console.log(res.message),
            error  : res => err ? err(res) : console.log(res.responseJSON?.message || res.statusText)
        });
    },
    // API METHODS
    Get:    ({id, ok, err})        => Appointment.req({type: 'GET',    url: 'api/appointment/'+id, ok, err}),
    GetAll: ({q = {}, ok, err} = {}) => {
        const param = obj => Object.keys(obj)
            .map(key => key + '=' + encodeURIComponent(obj[key]))
            .join('&');

        const url = Object.keys(q).length
            ? 'api/appointment?' + param(q)
            : 'api/appointment';

        Appointment.req({type: 'GET', url, ok, err});
    },
    Create: ({data, ok, err})      => Appointment.req({type: 'POST',   url: 'api/appointment', data, ok, err}),
    Update: ({id, data, ok, err})  => Appointment.req({type: 'PUT',    url: 'api/appointment/'+id, data, ok, err}),
    Delete: ({id, ok, err})        => Appointment.req({type: 'DELETE', url: 'api/appointment/'+id, ok, err}),

    // UPLOAD FILE
    uploadFile({ input, ok, err }) {
        const $input = (input instanceof jQuery) ? input : $(input);
        const fileInput = $input[0];
        if (!$input.length || !fileInput.files.length) return;

        const type = $input.data('type');
        const url = $input.data('upload-url');

        if (!type || !url) {
            console.warn('❌ data-type veya data-upload-url eksik');
            return;
        }

        const isMultiple = EnumUploadMultiple[type] === true;
        const fieldName = fileInput.name || type;

        const formData = new FormData();

        // Çoklu veya tekli dosya ekle
        if (isMultiple) {
            for (let i = 0; i < fileInput.files.length; i++) {
                formData.append(fieldName + '[]', fileInput.files[i]);
            }
        } else {
            formData.append(fieldName, fileInput.files[0]);
        }

        // data-extra-* attributeları topla
        $.each($input.data(), (key, value) => {
            if (key.startsWith('extra')) {
                const cleanKey = key.replace(/^extra/, '').replace(/^[A-Z]/, c => c.toLowerCase());
                formData.append(cleanKey, value);
            }
        });

        $.ajax({
            type: 'POST',
            url,
            data: formData,
            contentType: false,
            processData: false,
            success: res => ok ? ok(res) : console.log(res.message),
            error: res => err ? err(res) : console.log(res.responseJSON?.message || res.statusText)
        });
    },

    // FORM BINDINGS
    bindFormCreate({ button, form = null, apiMethod = null, redirectUrl = null, onSuccess = null, onBefore = null }) {
        button.off('click').click(() => {
            if (typeof onBefore === 'function') onBefore();
            const $btn = $(button);

            let formSelector = form || $btn.data('form');
            if (typeof formSelector === 'string' && !formSelector.startsWith('.') && !formSelector.startsWith('#')) {
                formSelector = '.' + formSelector;
            }
            const finalForm = (formSelector instanceof jQuery) ? formSelector : $(formSelector);

            const finalApiMethod = apiMethod || Appointment[$btn.data('api')];
            const finalRedirect = redirectUrl || $btn.data('redirect');

            const data = xsh.getFormData(finalForm);

            finalApiMethod({
                data,
                ok: (res) => {
                    xsh.showNotification({
                        message: res.message,
                        callback: () => {
                            if (onSuccess) onSuccess(res);
                            else if (finalRedirect) xsh.redirectTo(finalRedirect);
                        }
                    });
                }
            });
        });
    },

    bindFormUpdate({ button, form = null, apiMethod = null, redirectUrl = null, onSuccess = null, onBefore = null }) {
        button.off('click').click(() => {
            if (typeof onBefore === 'function') onBefore();
            const $btn = $(button);

            let formSelector = form || $btn.data('form');
            if (typeof formSelector === 'string' && !formSelector.startsWith('.') && !formSelector.startsWith('#')) {
                formSelector = '.' + formSelector;
            }
            const finalForm = (formSelector instanceof jQuery) ? formSelector : $(formSelector);

            const finalApiMethod = apiMethod || Appointment[$btn.data('api')];
            const finalRedirect = redirectUrl || $btn.data('redirect');
            const finalId = $btn.data('id');

            const data = xsh.getFormData(finalForm);

            finalApiMethod({
                id: finalId,
                data,
                ok: (res) => {
                    xsh.showNotification({
                        message: res.message,
                        callback: () => {
                            if (onSuccess) onSuccess(res);
                            else if (finalRedirect) xsh.redirectTo(finalRedirect);
                        }
                    });
                }
            });
        });
    },

    bindFormDelete({ button, apiMethod = null, redirectUrl = null, onSuccess = null, onBefore = null }) {
        button.off('click').click(() => {
            if (typeof onBefore === 'function') onBefore();
            const $btn = $(button);
            const finalApiMethod = apiMethod || Appointment[$btn.data('api')];
            const finalRedirect = $btn.data('redirect');
            const finalId = $btn.data('id');

            finalApiMethod({
                id: finalId,
                ok: (res) => {
                    xsh.showNotification({
                        message: res.message,
                        callback: () => {
                            if (onSuccess) onSuccess(res);
                            else if (finalRedirect) xsh.redirectTo(finalRedirect);
                        }
                    });
                }
            });
        });
    },
    // AUTO BIND
    autoBindFormActions() {
        const map = {
            '.AppointmentBtnCreate': Appointment.bindFormCreate,
            '.AppointmentBtnUpdate': Appointment.bindFormUpdate,
            '.AppointmentBtnDelete': Appointment.bindFormDelete,
        };

        Object.entries(map).forEach(([selector, handler]) => {
            $(selector).each((i, btn) => {
                const $btn = $(btn);
                const formSelector = $btn.data('form');
                const redirectUrl = $btn.data('redirect');
                const apiMethodName = $btn.data('api');

                if (!apiMethodName || typeof this[apiMethodName] !== 'function') return;

                const options = {
                    button: $btn,
                    form: formSelector,
                    apiMethod: this[apiMethodName],
                    redirectUrl
                };

                handler.call(this, options);
            });
        });
    }
};
// AUTO BIND
Appointment.autoBindFormActions();
const Comment = {
    // BUTTON
    BtnGet   : $('.CommentBtnGet'),
    BtnGetAll: $('.CommentBtnGetAll'),
    BtnCreate: $('.CommentBtnCreate'),
    BtnUpdate: $('.CommentBtnUpdate'),
    BtnDelete: $('.CommentBtnDelete'),

    // FORM
    FrmGet   : $('.CommentFrmGet'),
    FrmGetAll: $('.CommentFrmGetAll'),
    FrmCreate: $('.CommentFrmCreate'),
    FrmUpdate: $('.CommentFrmUpdate'),
    FrmDelete: $('.CommentFrmDelete'),

    // MODAL
    MdlGet   : $('.CommentMdlGet'),
    MdlGetAll: $('.CommentMdlGetAll'),
    MdlCreate: $('.CommentMdlCreate'),
    MdlUpdate: $('.CommentMdlUpdate'),
    MdlDelete: $('.CommentMdlDelete'),

    // MODAL BUTTON
    BtnMdlGet   : $('.CommentBtnMdlGet'),
    BtnMdlGetAll: $('.CommentBtnMdlGetAll'),
    BtnMdlCreate: $('.CommentBtnMdlCreate'),
    BtnMdlUpdate: $('.CommentBtnMdlUpdate'),
    BtnMdlDelete: $('.CommentBtnMdlDelete'),
    // ROUTE
    Route : {
        Api: {
            index:      '/api/comment',
            store:      '/api/comment',
            show:       (id) => '/api/comment/'+id,
            update:     (id) => '/api/comment/'+id,
            destroy:    (id) => '/api/comment/'+id,
        },
        Dashboard: {
            index:      '/dashboard/comment',
            store:      '/dashboard/comment',
            create:     '/dashboard/comment/create',
            show:       (id) => '/dashboard/comment/'+id,
            update:     (id) => '/dashboard/comment/'+id,
            edit:       (id) => '/dashboard/comment/'+id+'/edit',
        },
        Panel: {
            index:      '/panel/comment',
            store:      '/panel/comment',
            create:     '/panel/comment/create',
            show:       (id) => '/panel/comment/'+id,
            update:     (id) => '/panel/comment/'+id,
            edit:       (id) => '/panel/comment/'+id+'/edit',
        },
        Site: {
            index:      '/comment',
            store:      '/comment',
            create:     '/comment/create',
            show:       (id) => '/comment/'+id,
            update:     (id) => '/comment/'+id,
            edit:       (id) => '/comment/'+id+'/edit',
        },
    },
    // AJAX
    req({type, url, data = {}, ok, err}) {
        $.ajax({
            type,
            url,
            data,
            success: res => ok ? ok(res) : console.log(res.message),
            error  : res => err ? err(res) : console.log(res.responseJSON?.message || res.statusText)
        });
    },
    // API METHODS
    Get:    ({id, ok, err})        => Comment.req({type: 'GET',    url: 'api/comment/'+id, ok, err}),
    GetAll: ({q = {}, ok, err} = {}) => {
        const param = obj => Object.keys(obj)
            .map(key => key + '=' + encodeURIComponent(obj[key]))
            .join('&');

        const url = Object.keys(q).length
            ? 'api/comment?' + param(q)
            : 'api/comment';

        Comment.req({type: 'GET', url, ok, err});
    },
    Create: ({data, ok, err})      => Comment.req({type: 'POST',   url: 'api/comment', data, ok, err}),
    Update: ({id, data, ok, err})  => Comment.req({type: 'PUT',    url: 'api/comment/'+id, data, ok, err}),
    Delete: ({id, ok, err})        => Comment.req({type: 'DELETE', url: 'api/comment/'+id, ok, err}),

    // UPLOAD FILE
    uploadFile({ input, ok, err }) {
        const $input = (input instanceof jQuery) ? input : $(input);
        const fileInput = $input[0];
        if (!$input.length || !fileInput.files.length) return;

        const type = $input.data('type');
        const url = $input.data('upload-url');

        if (!type || !url) {
            console.warn('❌ data-type veya data-upload-url eksik');
            return;
        }

        const isMultiple = EnumUploadMultiple[type] === true;
        const fieldName = fileInput.name || type;

        const formData = new FormData();

        // Çoklu veya tekli dosya ekle
        if (isMultiple) {
            for (let i = 0; i < fileInput.files.length; i++) {
                formData.append(fieldName + '[]', fileInput.files[i]);
            }
        } else {
            formData.append(fieldName, fileInput.files[0]);
        }

        // data-extra-* attributeları topla
        $.each($input.data(), (key, value) => {
            if (key.startsWith('extra')) {
                const cleanKey = key.replace(/^extra/, '').replace(/^[A-Z]/, c => c.toLowerCase());
                formData.append(cleanKey, value);
            }
        });

        $.ajax({
            type: 'POST',
            url,
            data: formData,
            contentType: false,
            processData: false,
            success: res => ok ? ok(res) : console.log(res.message),
            error: res => err ? err(res) : console.log(res.responseJSON?.message || res.statusText)
        });
    },

    // FORM BINDINGS
    bindFormCreate({ button, form = null, apiMethod = null, redirectUrl = null, onSuccess = null, onBefore = null }) {
        button.off('click').click(() => {
            if (typeof onBefore === 'function') onBefore();
            const $btn = $(button);

            let formSelector = form || $btn.data('form');
            if (typeof formSelector === 'string' && !formSelector.startsWith('.') && !formSelector.startsWith('#')) {
                formSelector = '.' + formSelector;
            }
            const finalForm = (formSelector instanceof jQuery) ? formSelector : $(formSelector);

            const finalApiMethod = apiMethod || Comment[$btn.data('api')];
            const finalRedirect = redirectUrl || $btn.data('redirect');

            const data = xsh.getFormData(finalForm);

            finalApiMethod({
                data,
                ok: (res) => {
                    xsh.showNotification({
                        message: res.message,
                        callback: () => {
                            if (onSuccess) onSuccess(res);
                            else if (finalRedirect) xsh.redirectTo(finalRedirect);
                        }
                    });
                }
            });
        });
    },

    bindFormUpdate({ button, form = null, apiMethod = null, redirectUrl = null, onSuccess = null, onBefore = null }) {
        button.off('click').click(() => {
            if (typeof onBefore === 'function') onBefore();
            const $btn = $(button);

            let formSelector = form || $btn.data('form');
            if (typeof formSelector === 'string' && !formSelector.startsWith('.') && !formSelector.startsWith('#')) {
                formSelector = '.' + formSelector;
            }
            const finalForm = (formSelector instanceof jQuery) ? formSelector : $(formSelector);

            const finalApiMethod = apiMethod || Comment[$btn.data('api')];
            const finalRedirect = redirectUrl || $btn.data('redirect');
            const finalId = $btn.data('id');

            const data = xsh.getFormData(finalForm);

            finalApiMethod({
                id: finalId,
                data,
                ok: (res) => {
                    xsh.showNotification({
                        message: res.message,
                        callback: () => {
                            if (onSuccess) onSuccess(res);
                            else if (finalRedirect) xsh.redirectTo(finalRedirect);
                        }
                    });
                }
            });
        });
    },

    bindFormDelete({ button, apiMethod = null, redirectUrl = null, onSuccess = null, onBefore = null }) {
        button.off('click').click(() => {
            if (typeof onBefore === 'function') onBefore();
            const $btn = $(button);
            const finalApiMethod = apiMethod || Comment[$btn.data('api')];
            const finalRedirect = $btn.data('redirect');
            const finalId = $btn.data('id');

            finalApiMethod({
                id: finalId,
                ok: (res) => {
                    xsh.showNotification({
                        message: res.message,
                        callback: () => {
                            if (onSuccess) onSuccess(res);
                            else if (finalRedirect) xsh.redirectTo(finalRedirect);
                        }
                    });
                }
            });
        });
    },
    // AUTO BIND
    autoBindFormActions() {
        const map = {
            '.CommentBtnCreate': Comment.bindFormCreate,
            '.CommentBtnUpdate': Comment.bindFormUpdate,
            '.CommentBtnDelete': Comment.bindFormDelete,
        };

        Object.entries(map).forEach(([selector, handler]) => {
            $(selector).each((i, btn) => {
                const $btn = $(btn);
                const formSelector = $btn.data('form');
                const redirectUrl = $btn.data('redirect');
                const apiMethodName = $btn.data('api');

                if (!apiMethodName || typeof this[apiMethodName] !== 'function') return;

                const options = {
                    button: $btn,
                    form: formSelector,
                    apiMethod: this[apiMethodName],
                    redirectUrl
                };

                handler.call(this, options);
            });
        });
    }
};
// AUTO BIND
Comment.autoBindFormActions();
const Customer = {
    // BUTTON
    BtnGet   : $('.CustomerBtnGet'),
    BtnGetAll: $('.CustomerBtnGetAll'),
    BtnCreate: $('.CustomerBtnCreate'),
    BtnUpdate: $('.CustomerBtnUpdate'),
    BtnDelete: $('.CustomerBtnDelete'),

    // FORM
    FrmGet   : $('.CustomerFrmGet'),
    FrmGetAll: $('.CustomerFrmGetAll'),
    FrmCreate: $('.CustomerFrmCreate'),
    FrmUpdate: $('.CustomerFrmUpdate'),
    FrmDelete: $('.CustomerFrmDelete'),

    // MODAL
    MdlGet   : $('.CustomerMdlGet'),
    MdlGetAll: $('.CustomerMdlGetAll'),
    MdlCreate: $('.CustomerMdlCreate'),
    MdlUpdate: $('.CustomerMdlUpdate'),
    MdlDelete: $('.CustomerMdlDelete'),

    // MODAL BUTTON
    BtnMdlGet   : $('.CustomerBtnMdlGet'),
    BtnMdlGetAll: $('.CustomerBtnMdlGetAll'),
    BtnMdlCreate: $('.CustomerBtnMdlCreate'),
    BtnMdlUpdate: $('.CustomerBtnMdlUpdate'),
    BtnMdlDelete: $('.CustomerBtnMdlDelete'),
    // ROUTE
    Route : {
        Api: {
            index:      '/api/customer',
            store:      '/api/customer',
            show:       (id) => '/api/customer/'+id,
            update:     (id) => '/api/customer/'+id,
            destroy:    (id) => '/api/customer/'+id,
        },
        Dashboard: {
            index:      '/dashboard/customer',
            store:      '/dashboard/customer',
            create:     '/dashboard/customer/create',
            show:       (id) => '/dashboard/customer/'+id,
            update:     (id) => '/dashboard/customer/'+id,
            edit:       (id) => '/dashboard/customer/'+id+'/edit',
        },
        Panel: {
            index:      '/panel/customer',
            store:      '/panel/customer',
            create:     '/panel/customer/create',
            show:       (id) => '/panel/customer/'+id,
            update:     (id) => '/panel/customer/'+id,
            edit:       (id) => '/panel/customer/'+id+'/edit',
        },
        Site: {
            index:      '/customer',
            store:      '/customer',
            create:     '/customer/create',
            show:       (id) => '/customer/'+id,
            update:     (id) => '/customer/'+id,
            edit:       (id) => '/customer/'+id+'/edit',
        },
    },
    // AJAX
    req({type, url, data = {}, ok, err}) {
        $.ajax({
            type,
            url,
            data,
            success: res => ok ? ok(res) : console.log(res.message),
            error  : res => err ? err(res) : console.log(res.responseJSON?.message || res.statusText)
        });
    },
    // API METHODS
    Get:    ({id, ok, err})        => Customer.req({type: 'GET',    url: 'api/customer/'+id, ok, err}),
    GetAll: ({q = {}, ok, err} = {}) => {
        const param = obj => Object.keys(obj)
            .map(key => key + '=' + encodeURIComponent(obj[key]))
            .join('&');

        const url = Object.keys(q).length
            ? 'api/customer?' + param(q)
            : 'api/customer';

        Customer.req({type: 'GET', url, ok, err});
    },
    Create: ({data, ok, err})      => Customer.req({type: 'POST',   url: 'api/customer', data, ok, err}),
    Update: ({id, data, ok, err})  => Customer.req({type: 'PUT',    url: 'api/customer/'+id, data, ok, err}),
    Delete: ({id, ok, err})        => Customer.req({type: 'DELETE', url: 'api/customer/'+id, ok, err}),

    // UPLOAD FILE
    uploadFile({ input, ok, err }) {
        const $input = (input instanceof jQuery) ? input : $(input);
        const fileInput = $input[0];
        if (!$input.length || !fileInput.files.length) return;

        const type = $input.data('type');
        const url = $input.data('upload-url');

        if (!type || !url) {
            console.warn('❌ data-type veya data-upload-url eksik');
            return;
        }

        const isMultiple = EnumUploadMultiple[type] === true;
        const fieldName = fileInput.name || type;

        const formData = new FormData();

        // Çoklu veya tekli dosya ekle
        if (isMultiple) {
            for (let i = 0; i < fileInput.files.length; i++) {
                formData.append(fieldName + '[]', fileInput.files[i]);
            }
        } else {
            formData.append(fieldName, fileInput.files[0]);
        }

        // data-extra-* attributeları topla
        $.each($input.data(), (key, value) => {
            if (key.startsWith('extra')) {
                const cleanKey = key.replace(/^extra/, '').replace(/^[A-Z]/, c => c.toLowerCase());
                formData.append(cleanKey, value);
            }
        });

        $.ajax({
            type: 'POST',
            url,
            data: formData,
            contentType: false,
            processData: false,
            success: res => ok ? ok(res) : console.log(res.message),
            error: res => err ? err(res) : console.log(res.responseJSON?.message || res.statusText)
        });
    },

    // FORM BINDINGS
    bindFormCreate({ button, form = null, apiMethod = null, redirectUrl = null, onSuccess = null, onBefore = null }) {
        button.off('click').click(() => {
            if (typeof onBefore === 'function') onBefore();
            const $btn = $(button);

            let formSelector = form || $btn.data('form');
            if (typeof formSelector === 'string' && !formSelector.startsWith('.') && !formSelector.startsWith('#')) {
                formSelector = '.' + formSelector;
            }
            const finalForm = (formSelector instanceof jQuery) ? formSelector : $(formSelector);

            const finalApiMethod = apiMethod || Customer[$btn.data('api')];
            const finalRedirect = redirectUrl || $btn.data('redirect');

            const data = xsh.getFormData(finalForm);

            finalApiMethod({
                data,
                ok: (res) => {
                    xsh.showNotification({
                        message: res.message,
                        callback: () => {
                            if (onSuccess) onSuccess(res);
                            else if (finalRedirect) xsh.redirectTo(finalRedirect);
                        }
                    });
                }
            });
        });
    },

    bindFormUpdate({ button, form = null, apiMethod = null, redirectUrl = null, onSuccess = null, onBefore = null }) {
        button.off('click').click(() => {
            if (typeof onBefore === 'function') onBefore();
            const $btn = $(button);

            let formSelector = form || $btn.data('form');
            if (typeof formSelector === 'string' && !formSelector.startsWith('.') && !formSelector.startsWith('#')) {
                formSelector = '.' + formSelector;
            }
            const finalForm = (formSelector instanceof jQuery) ? formSelector : $(formSelector);

            const finalApiMethod = apiMethod || Customer[$btn.data('api')];
            const finalRedirect = redirectUrl || $btn.data('redirect');
            const finalId = $btn.data('id');

            const data = xsh.getFormData(finalForm);

            finalApiMethod({
                id: finalId,
                data,
                ok: (res) => {
                    xsh.showNotification({
                        message: res.message,
                        callback: () => {
                            if (onSuccess) onSuccess(res);
                            else if (finalRedirect) xsh.redirectTo(finalRedirect);
                        }
                    });
                }
            });
        });
    },

    bindFormDelete({ button, apiMethod = null, redirectUrl = null, onSuccess = null, onBefore = null }) {
        button.off('click').click(() => {
            if (typeof onBefore === 'function') onBefore();
            const $btn = $(button);
            const finalApiMethod = apiMethod || Customer[$btn.data('api')];
            const finalRedirect = $btn.data('redirect');
            const finalId = $btn.data('id');

            finalApiMethod({
                id: finalId,
                ok: (res) => {
                    xsh.showNotification({
                        message: res.message,
                        callback: () => {
                            if (onSuccess) onSuccess(res);
                            else if (finalRedirect) xsh.redirectTo(finalRedirect);
                        }
                    });
                }
            });
        });
    },
    // AUTO BIND
    autoBindFormActions() {
        const map = {
            '.CustomerBtnCreate': Customer.bindFormCreate,
            '.CustomerBtnUpdate': Customer.bindFormUpdate,
            '.CustomerBtnDelete': Customer.bindFormDelete,
        };

        Object.entries(map).forEach(([selector, handler]) => {
            $(selector).each((i, btn) => {
                const $btn = $(btn);
                const formSelector = $btn.data('form');
                const redirectUrl = $btn.data('redirect');
                const apiMethodName = $btn.data('api');

                if (!apiMethodName || typeof this[apiMethodName] !== 'function') return;

                const options = {
                    button: $btn,
                    form: formSelector,
                    apiMethod: this[apiMethodName],
                    redirectUrl
                };

                handler.call(this, options);
            });
        });
    }
};
// AUTO BIND
Customer.autoBindFormActions();
const IncomeExpense = {
    // BUTTON
    BtnGet   : $('.IncomeExpenseBtnGet'),
    BtnGetAll: $('.IncomeExpenseBtnGetAll'),
    BtnCreate: $('.IncomeExpenseBtnCreate'),
    BtnUpdate: $('.IncomeExpenseBtnUpdate'),
    BtnDelete: $('.IncomeExpenseBtnDelete'),

    // FORM
    FrmGet   : $('.IncomeExpenseFrmGet'),
    FrmGetAll: $('.IncomeExpenseFrmGetAll'),
    FrmCreate: $('.IncomeExpenseFrmCreate'),
    FrmUpdate: $('.IncomeExpenseFrmUpdate'),
    FrmDelete: $('.IncomeExpenseFrmDelete'),

    // MODAL
    MdlGet   : $('.IncomeExpenseMdlGet'),
    MdlGetAll: $('.IncomeExpenseMdlGetAll'),
    MdlCreate: $('.IncomeExpenseMdlCreate'),
    MdlUpdate: $('.IncomeExpenseMdlUpdate'),
    MdlDelete: $('.IncomeExpenseMdlDelete'),

    // MODAL BUTTON
    BtnMdlGet   : $('.IncomeExpenseBtnMdlGet'),
    BtnMdlGetAll: $('.IncomeExpenseBtnMdlGetAll'),
    BtnMdlCreate: $('.IncomeExpenseBtnMdlCreate'),
    BtnMdlUpdate: $('.IncomeExpenseBtnMdlUpdate'),
    BtnMdlDelete: $('.IncomeExpenseBtnMdlDelete'),
    // ROUTE
    Route : {
        Api: {
            index:      '/api/income-expense',
            store:      '/api/income-expense',
            show:       (id) => '/api/income-expense/'+id,
            update:     (id) => '/api/income-expense/'+id,
            destroy:    (id) => '/api/income-expense/'+id,
        },
        Dashboard: {
            index:      '/dashboard/income-expense',
            store:      '/dashboard/income-expense',
            create:     '/dashboard/income-expense/create',
            show:       (id) => '/dashboard/income-expense/'+id,
            update:     (id) => '/dashboard/income-expense/'+id,
            edit:       (id) => '/dashboard/income-expense/'+id+'/edit',
        },
        Panel: {
            index:      '/panel/income-expense',
            store:      '/panel/income-expense',
            create:     '/panel/income-expense/create',
            show:       (id) => '/panel/income-expense/'+id,
            update:     (id) => '/panel/income-expense/'+id,
            edit:       (id) => '/panel/income-expense/'+id+'/edit',
        },
        Site: {
            index:      '/income-expense',
            store:      '/income-expense',
            create:     '/income-expense/create',
            show:       (id) => '/income-expense/'+id,
            update:     (id) => '/income-expense/'+id,
            edit:       (id) => '/income-expense/'+id+'/edit',
        },
    },
    // AJAX
    req({type, url, data = {}, ok, err}) {
        $.ajax({
            type,
            url,
            data,
            success: res => ok ? ok(res) : console.log(res.message),
            error  : res => err ? err(res) : console.log(res.responseJSON?.message || res.statusText)
        });
    },
    // API METHODS
    Get:    ({id, ok, err})        => IncomeExpense.req({type: 'GET',    url: 'api/income-expense/'+id, ok, err}),
    GetAll: ({q = {}, ok, err} = {}) => {
        const param = obj => Object.keys(obj)
            .map(key => key + '=' + encodeURIComponent(obj[key]))
            .join('&');

        const url = Object.keys(q).length
            ? 'api/income-expense?' + param(q)
            : 'api/income-expense';

        IncomeExpense.req({type: 'GET', url, ok, err});
    },
    Create: ({data, ok, err})      => IncomeExpense.req({type: 'POST',   url: 'api/income-expense', data, ok, err}),
    Update: ({id, data, ok, err})  => IncomeExpense.req({type: 'PUT',    url: 'api/income-expense/'+id, data, ok, err}),
    Delete: ({id, ok, err})        => IncomeExpense.req({type: 'DELETE', url: 'api/income-expense/'+id, ok, err}),

    // UPLOAD FILE
    uploadFile({ input, ok, err }) {
        const $input = (input instanceof jQuery) ? input : $(input);
        const fileInput = $input[0];
        if (!$input.length || !fileInput.files.length) return;

        const type = $input.data('type');
        const url = $input.data('upload-url');

        if (!type || !url) {
            console.warn('❌ data-type veya data-upload-url eksik');
            return;
        }

        const isMultiple = EnumUploadMultiple[type] === true;
        const fieldName = fileInput.name || type;

        const formData = new FormData();

        // Çoklu veya tekli dosya ekle
        if (isMultiple) {
            for (let i = 0; i < fileInput.files.length; i++) {
                formData.append(fieldName + '[]', fileInput.files[i]);
            }
        } else {
            formData.append(fieldName, fileInput.files[0]);
        }

        // data-extra-* attributeları topla
        $.each($input.data(), (key, value) => {
            if (key.startsWith('extra')) {
                const cleanKey = key.replace(/^extra/, '').replace(/^[A-Z]/, c => c.toLowerCase());
                formData.append(cleanKey, value);
            }
        });

        $.ajax({
            type: 'POST',
            url,
            data: formData,
            contentType: false,
            processData: false,
            success: res => ok ? ok(res) : console.log(res.message),
            error: res => err ? err(res) : console.log(res.responseJSON?.message || res.statusText)
        });
    },

    // FORM BINDINGS
    bindFormCreate({ button, form = null, apiMethod = null, redirectUrl = null, onSuccess = null, onBefore = null }) {
        button.off('click').click(() => {
            if (typeof onBefore === 'function') onBefore();
            const $btn = $(button);

            let formSelector = form || $btn.data('form');
            if (typeof formSelector === 'string' && !formSelector.startsWith('.') && !formSelector.startsWith('#')) {
                formSelector = '.' + formSelector;
            }
            const finalForm = (formSelector instanceof jQuery) ? formSelector : $(formSelector);

            const finalApiMethod = apiMethod || IncomeExpense[$btn.data('api')];
            const finalRedirect = redirectUrl || $btn.data('redirect');

            const data = xsh.getFormData(finalForm);

            finalApiMethod({
                data,
                ok: (res) => {
                    xsh.showNotification({
                        message: res.message,
                        callback: () => {
                            if (onSuccess) onSuccess(res);
                            else if (finalRedirect) xsh.redirectTo(finalRedirect);
                        }
                    });
                }
            });
        });
    },

    bindFormUpdate({ button, form = null, apiMethod = null, redirectUrl = null, onSuccess = null, onBefore = null }) {
        button.off('click').click(() => {
            if (typeof onBefore === 'function') onBefore();
            const $btn = $(button);

            let formSelector = form || $btn.data('form');
            if (typeof formSelector === 'string' && !formSelector.startsWith('.') && !formSelector.startsWith('#')) {
                formSelector = '.' + formSelector;
            }
            const finalForm = (formSelector instanceof jQuery) ? formSelector : $(formSelector);

            const finalApiMethod = apiMethod || IncomeExpense[$btn.data('api')];
            const finalRedirect = redirectUrl || $btn.data('redirect');
            const finalId = $btn.data('id');

            const data = xsh.getFormData(finalForm);

            finalApiMethod({
                id: finalId,
                data,
                ok: (res) => {
                    xsh.showNotification({
                        message: res.message,
                        callback: () => {
                            if (onSuccess) onSuccess(res);
                            else if (finalRedirect) xsh.redirectTo(finalRedirect);
                        }
                    });
                }
            });
        });
    },

    bindFormDelete({ button, apiMethod = null, redirectUrl = null, onSuccess = null, onBefore = null }) {
        button.off('click').click(() => {
            if (typeof onBefore === 'function') onBefore();
            const $btn = $(button);
            const finalApiMethod = apiMethod || IncomeExpense[$btn.data('api')];
            const finalRedirect = $btn.data('redirect');
            const finalId = $btn.data('id');

            finalApiMethod({
                id: finalId,
                ok: (res) => {
                    xsh.showNotification({
                        message: res.message,
                        callback: () => {
                            if (onSuccess) onSuccess(res);
                            else if (finalRedirect) xsh.redirectTo(finalRedirect);
                        }
                    });
                }
            });
        });
    },
    // AUTO BIND
    autoBindFormActions() {
        const map = {
            '.IncomeExpenseBtnCreate': IncomeExpense.bindFormCreate,
            '.IncomeExpenseBtnUpdate': IncomeExpense.bindFormUpdate,
            '.IncomeExpenseBtnDelete': IncomeExpense.bindFormDelete,
        };

        Object.entries(map).forEach(([selector, handler]) => {
            $(selector).each((i, btn) => {
                const $btn = $(btn);
                const formSelector = $btn.data('form');
                const redirectUrl = $btn.data('redirect');
                const apiMethodName = $btn.data('api');

                if (!apiMethodName || typeof this[apiMethodName] !== 'function') return;

                const options = {
                    button: $btn,
                    form: formSelector,
                    apiMethod: this[apiMethodName],
                    redirectUrl
                };

                handler.call(this, options);
            });
        });
    }
};
// AUTO BIND
IncomeExpense.autoBindFormActions();
const IncomeExpenseCategory = {
    // BUTTON
    BtnGet   : $('.IncomeExpenseCategoryBtnGet'),
    BtnGetAll: $('.IncomeExpenseCategoryBtnGetAll'),
    BtnCreate: $('.IncomeExpenseCategoryBtnCreate'),
    BtnUpdate: $('.IncomeExpenseCategoryBtnUpdate'),
    BtnDelete: $('.IncomeExpenseCategoryBtnDelete'),

    // FORM
    FrmGet   : $('.IncomeExpenseCategoryFrmGet'),
    FrmGetAll: $('.IncomeExpenseCategoryFrmGetAll'),
    FrmCreate: $('.IncomeExpenseCategoryFrmCreate'),
    FrmUpdate: $('.IncomeExpenseCategoryFrmUpdate'),
    FrmDelete: $('.IncomeExpenseCategoryFrmDelete'),

    // MODAL
    MdlGet   : $('.IncomeExpenseCategoryMdlGet'),
    MdlGetAll: $('.IncomeExpenseCategoryMdlGetAll'),
    MdlCreate: $('.IncomeExpenseCategoryMdlCreate'),
    MdlUpdate: $('.IncomeExpenseCategoryMdlUpdate'),
    MdlDelete: $('.IncomeExpenseCategoryMdlDelete'),

    // MODAL BUTTON
    BtnMdlGet   : $('.IncomeExpenseCategoryBtnMdlGet'),
    BtnMdlGetAll: $('.IncomeExpenseCategoryBtnMdlGetAll'),
    BtnMdlCreate: $('.IncomeExpenseCategoryBtnMdlCreate'),
    BtnMdlUpdate: $('.IncomeExpenseCategoryBtnMdlUpdate'),
    BtnMdlDelete: $('.IncomeExpenseCategoryBtnMdlDelete'),
    // ROUTE
    Route : {
        Api: {
            index:      '/api/income-expense-category',
            store:      '/api/income-expense-category',
            show:       (id) => '/api/income-expense-category/'+id,
            update:     (id) => '/api/income-expense-category/'+id,
            destroy:    (id) => '/api/income-expense-category/'+id,
        },
        Dashboard: {
            index:      '/dashboard/income-expense-category',
            store:      '/dashboard/income-expense-category',
            create:     '/dashboard/income-expense-category/create',
            show:       (id) => '/dashboard/income-expense-category/'+id,
            update:     (id) => '/dashboard/income-expense-category/'+id,
            edit:       (id) => '/dashboard/income-expense-category/'+id+'/edit',
        },
        Panel: {
            index:      '/panel/income-expense-category',
            store:      '/panel/income-expense-category',
            create:     '/panel/income-expense-category/create',
            show:       (id) => '/panel/income-expense-category/'+id,
            update:     (id) => '/panel/income-expense-category/'+id,
            edit:       (id) => '/panel/income-expense-category/'+id+'/edit',
        },
        Site: {
            index:      '/income-expense-category',
            store:      '/income-expense-category',
            create:     '/income-expense-category/create',
            show:       (id) => '/income-expense-category/'+id,
            update:     (id) => '/income-expense-category/'+id,
            edit:       (id) => '/income-expense-category/'+id+'/edit',
        },
    },
    // AJAX
    req({type, url, data = {}, ok, err}) {
        $.ajax({
            type,
            url,
            data,
            success: res => ok ? ok(res) : console.log(res.message),
            error  : res => err ? err(res) : console.log(res.responseJSON?.message || res.statusText)
        });
    },
    // API METHODS
    Get:    ({id, ok, err})        => IncomeExpenseCategory.req({type: 'GET',    url: 'api/income-expense-category/'+id, ok, err}),
    GetAll: ({q = {}, ok, err} = {}) => {
        const param = obj => Object.keys(obj)
            .map(key => key + '=' + encodeURIComponent(obj[key]))
            .join('&');

        const url = Object.keys(q).length
            ? 'api/income-expense-category?' + param(q)
            : 'api/income-expense-category';

        IncomeExpenseCategory.req({type: 'GET', url, ok, err});
    },
    Create: ({data, ok, err})      => IncomeExpenseCategory.req({type: 'POST',   url: 'api/income-expense-category', data, ok, err}),
    Update: ({id, data, ok, err})  => IncomeExpenseCategory.req({type: 'PUT',    url: 'api/income-expense-category/'+id, data, ok, err}),
    Delete: ({id, ok, err})        => IncomeExpenseCategory.req({type: 'DELETE', url: 'api/income-expense-category/'+id, ok, err}),

    // UPLOAD FILE
    uploadFile({ input, ok, err }) {
        const $input = (input instanceof jQuery) ? input : $(input);
        const fileInput = $input[0];
        if (!$input.length || !fileInput.files.length) return;

        const type = $input.data('type');
        const url = $input.data('upload-url');

        if (!type || !url) {
            console.warn('❌ data-type veya data-upload-url eksik');
            return;
        }

        const isMultiple = EnumUploadMultiple[type] === true;
        const fieldName = fileInput.name || type;

        const formData = new FormData();

        // Çoklu veya tekli dosya ekle
        if (isMultiple) {
            for (let i = 0; i < fileInput.files.length; i++) {
                formData.append(fieldName + '[]', fileInput.files[i]);
            }
        } else {
            formData.append(fieldName, fileInput.files[0]);
        }

        // data-extra-* attributeları topla
        $.each($input.data(), (key, value) => {
            if (key.startsWith('extra')) {
                const cleanKey = key.replace(/^extra/, '').replace(/^[A-Z]/, c => c.toLowerCase());
                formData.append(cleanKey, value);
            }
        });

        $.ajax({
            type: 'POST',
            url,
            data: formData,
            contentType: false,
            processData: false,
            success: res => ok ? ok(res) : console.log(res.message),
            error: res => err ? err(res) : console.log(res.responseJSON?.message || res.statusText)
        });
    },

    // FORM BINDINGS
    bindFormCreate({ button, form = null, apiMethod = null, redirectUrl = null, onSuccess = null, onBefore = null }) {
        button.off('click').click(() => {
            if (typeof onBefore === 'function') onBefore();
            const $btn = $(button);

            let formSelector = form || $btn.data('form');
            if (typeof formSelector === 'string' && !formSelector.startsWith('.') && !formSelector.startsWith('#')) {
                formSelector = '.' + formSelector;
            }
            const finalForm = (formSelector instanceof jQuery) ? formSelector : $(formSelector);

            const finalApiMethod = apiMethod || IncomeExpenseCategory[$btn.data('api')];
            const finalRedirect = redirectUrl || $btn.data('redirect');

            const data = xsh.getFormData(finalForm);

            finalApiMethod({
                data,
                ok: (res) => {
                    xsh.showNotification({
                        message: res.message,
                        callback: () => {
                            if (onSuccess) onSuccess(res);
                            else if (finalRedirect) xsh.redirectTo(finalRedirect);
                        }
                    });
                }
            });
        });
    },

    bindFormUpdate({ button, form = null, apiMethod = null, redirectUrl = null, onSuccess = null, onBefore = null }) {
        button.off('click').click(() => {
            if (typeof onBefore === 'function') onBefore();
            const $btn = $(button);

            let formSelector = form || $btn.data('form');
            if (typeof formSelector === 'string' && !formSelector.startsWith('.') && !formSelector.startsWith('#')) {
                formSelector = '.' + formSelector;
            }
            const finalForm = (formSelector instanceof jQuery) ? formSelector : $(formSelector);

            const finalApiMethod = apiMethod || IncomeExpenseCategory[$btn.data('api')];
            const finalRedirect = redirectUrl || $btn.data('redirect');
            const finalId = $btn.data('id');

            const data = xsh.getFormData(finalForm);

            finalApiMethod({
                id: finalId,
                data,
                ok: (res) => {
                    xsh.showNotification({
                        message: res.message,
                        callback: () => {
                            if (onSuccess) onSuccess(res);
                            else if (finalRedirect) xsh.redirectTo(finalRedirect);
                        }
                    });
                }
            });
        });
    },

    bindFormDelete({ button, apiMethod = null, redirectUrl = null, onSuccess = null, onBefore = null }) {
        button.off('click').click(() => {
            if (typeof onBefore === 'function') onBefore();
            const $btn = $(button);
            const finalApiMethod = apiMethod || IncomeExpenseCategory[$btn.data('api')];
            const finalRedirect = $btn.data('redirect');
            const finalId = $btn.data('id');

            finalApiMethod({
                id: finalId,
                ok: (res) => {
                    xsh.showNotification({
                        message: res.message,
                        callback: () => {
                            if (onSuccess) onSuccess(res);
                            else if (finalRedirect) xsh.redirectTo(finalRedirect);
                        }
                    });
                }
            });
        });
    },
    // AUTO BIND
    autoBindFormActions() {
        const map = {
            '.IncomeExpenseCategoryBtnCreate': IncomeExpenseCategory.bindFormCreate,
            '.IncomeExpenseCategoryBtnUpdate': IncomeExpenseCategory.bindFormUpdate,
            '.IncomeExpenseCategoryBtnDelete': IncomeExpenseCategory.bindFormDelete,
        };

        Object.entries(map).forEach(([selector, handler]) => {
            $(selector).each((i, btn) => {
                const $btn = $(btn);
                const formSelector = $btn.data('form');
                const redirectUrl = $btn.data('redirect');
                const apiMethodName = $btn.data('api');

                if (!apiMethodName || typeof this[apiMethodName] !== 'function') return;

                const options = {
                    button: $btn,
                    form: formSelector,
                    apiMethod: this[apiMethodName],
                    redirectUrl
                };

                handler.call(this, options);
            });
        });
    }
};
// AUTO BIND
IncomeExpenseCategory.autoBindFormActions();
const Invoice = {
    // BUTTON
    BtnGet   : $('.InvoiceBtnGet'),
    BtnGetAll: $('.InvoiceBtnGetAll'),
    BtnCreate: $('.InvoiceBtnCreate'),
    BtnUpdate: $('.InvoiceBtnUpdate'),
    BtnDelete: $('.InvoiceBtnDelete'),

    // FORM
    FrmGet   : $('.InvoiceFrmGet'),
    FrmGetAll: $('.InvoiceFrmGetAll'),
    FrmCreate: $('.InvoiceFrmCreate'),
    FrmUpdate: $('.InvoiceFrmUpdate'),
    FrmDelete: $('.InvoiceFrmDelete'),

    // MODAL
    MdlGet   : $('.InvoiceMdlGet'),
    MdlGetAll: $('.InvoiceMdlGetAll'),
    MdlCreate: $('.InvoiceMdlCreate'),
    MdlUpdate: $('.InvoiceMdlUpdate'),
    MdlDelete: $('.InvoiceMdlDelete'),

    // MODAL BUTTON
    BtnMdlGet   : $('.InvoiceBtnMdlGet'),
    BtnMdlGetAll: $('.InvoiceBtnMdlGetAll'),
    BtnMdlCreate: $('.InvoiceBtnMdlCreate'),
    BtnMdlUpdate: $('.InvoiceBtnMdlUpdate'),
    BtnMdlDelete: $('.InvoiceBtnMdlDelete'),
    // ROUTE
    Route : {
        Api: {
            index:      '/api/invoice',
            store:      '/api/invoice',
            show:       (id) => '/api/invoice/'+id,
            update:     (id) => '/api/invoice/'+id,
            destroy:    (id) => '/api/invoice/'+id,
        },
        Dashboard: {
            index:      '/dashboard/invoice',
            store:      '/dashboard/invoice',
            create:     '/dashboard/invoice/create',
            show:       (id) => '/dashboard/invoice/'+id,
            update:     (id) => '/dashboard/invoice/'+id,
            edit:       (id) => '/dashboard/invoice/'+id+'/edit',
        },
        Panel: {
            index:      '/panel/invoice',
            store:      '/panel/invoice',
            create:     '/panel/invoice/create',
            show:       (id) => '/panel/invoice/'+id,
            update:     (id) => '/panel/invoice/'+id,
            edit:       (id) => '/panel/invoice/'+id+'/edit',
        },
        Site: {
            index:      '/invoice',
            store:      '/invoice',
            create:     '/invoice/create',
            show:       (id) => '/invoice/'+id,
            update:     (id) => '/invoice/'+id,
            edit:       (id) => '/invoice/'+id+'/edit',
        },
    },
    // AJAX
    req({type, url, data = {}, ok, err}) {
        $.ajax({
            type,
            url,
            data,
            success: res => ok ? ok(res) : console.log(res.message),
            error  : res => err ? err(res) : console.log(res.responseJSON?.message || res.statusText)
        });
    },
    // API METHODS
    Get:    ({id, ok, err})        => Invoice.req({type: 'GET',    url: 'api/invoice/'+id, ok, err}),
    GetAll: ({q = {}, ok, err} = {}) => {
        const param = obj => Object.keys(obj)
            .map(key => key + '=' + encodeURIComponent(obj[key]))
            .join('&');

        const url = Object.keys(q).length
            ? 'api/invoice?' + param(q)
            : 'api/invoice';

        Invoice.req({type: 'GET', url, ok, err});
    },
    Create: ({data, ok, err})      => Invoice.req({type: 'POST',   url: 'api/invoice', data, ok, err}),
    Update: ({id, data, ok, err})  => Invoice.req({type: 'PUT',    url: 'api/invoice/'+id, data, ok, err}),
    Delete: ({id, ok, err})        => Invoice.req({type: 'DELETE', url: 'api/invoice/'+id, ok, err}),

    // UPLOAD FILE
    uploadFile({ input, ok, err }) {
        const $input = (input instanceof jQuery) ? input : $(input);
        const fileInput = $input[0];
        if (!$input.length || !fileInput.files.length) return;

        const type = $input.data('type');
        const url = $input.data('upload-url');

        if (!type || !url) {
            console.warn('❌ data-type veya data-upload-url eksik');
            return;
        }

        const isMultiple = EnumUploadMultiple[type] === true;
        const fieldName = fileInput.name || type;

        const formData = new FormData();

        // Çoklu veya tekli dosya ekle
        if (isMultiple) {
            for (let i = 0; i < fileInput.files.length; i++) {
                formData.append(fieldName + '[]', fileInput.files[i]);
            }
        } else {
            formData.append(fieldName, fileInput.files[0]);
        }

        // data-extra-* attributeları topla
        $.each($input.data(), (key, value) => {
            if (key.startsWith('extra')) {
                const cleanKey = key.replace(/^extra/, '').replace(/^[A-Z]/, c => c.toLowerCase());
                formData.append(cleanKey, value);
            }
        });

        $.ajax({
            type: 'POST',
            url,
            data: formData,
            contentType: false,
            processData: false,
            success: res => ok ? ok(res) : console.log(res.message),
            error: res => err ? err(res) : console.log(res.responseJSON?.message || res.statusText)
        });
    },

    // FORM BINDINGS
    bindFormCreate({ button, form = null, apiMethod = null, redirectUrl = null, onSuccess = null, onBefore = null }) {
        button.off('click').click(() => {
            if (typeof onBefore === 'function') onBefore();
            const $btn = $(button);

            let formSelector = form || $btn.data('form');
            if (typeof formSelector === 'string' && !formSelector.startsWith('.') && !formSelector.startsWith('#')) {
                formSelector = '.' + formSelector;
            }
            const finalForm = (formSelector instanceof jQuery) ? formSelector : $(formSelector);

            const finalApiMethod = apiMethod || Invoice[$btn.data('api')];
            const finalRedirect = redirectUrl || $btn.data('redirect');

            const data = xsh.getFormData(finalForm);

            finalApiMethod({
                data,
                ok: (res) => {
                    xsh.showNotification({
                        message: res.message,
                        callback: () => {
                            if (onSuccess) onSuccess(res);
                            else if (finalRedirect) xsh.redirectTo(finalRedirect);
                        }
                    });
                }
            });
        });
    },

    bindFormUpdate({ button, form = null, apiMethod = null, redirectUrl = null, onSuccess = null, onBefore = null }) {
        button.off('click').click(() => {
            if (typeof onBefore === 'function') onBefore();
            const $btn = $(button);

            let formSelector = form || $btn.data('form');
            if (typeof formSelector === 'string' && !formSelector.startsWith('.') && !formSelector.startsWith('#')) {
                formSelector = '.' + formSelector;
            }
            const finalForm = (formSelector instanceof jQuery) ? formSelector : $(formSelector);

            const finalApiMethod = apiMethod || Invoice[$btn.data('api')];
            const finalRedirect = redirectUrl || $btn.data('redirect');
            const finalId = $btn.data('id');

            const data = xsh.getFormData(finalForm);

            finalApiMethod({
                id: finalId,
                data,
                ok: (res) => {
                    xsh.showNotification({
                        message: res.message,
                        callback: () => {
                            if (onSuccess) onSuccess(res);
                            else if (finalRedirect) xsh.redirectTo(finalRedirect);
                        }
                    });
                }
            });
        });
    },

    bindFormDelete({ button, apiMethod = null, redirectUrl = null, onSuccess = null, onBefore = null }) {
        button.off('click').click(() => {
            if (typeof onBefore === 'function') onBefore();
            const $btn = $(button);
            const finalApiMethod = apiMethod || Invoice[$btn.data('api')];
            const finalRedirect = $btn.data('redirect');
            const finalId = $btn.data('id');

            finalApiMethod({
                id: finalId,
                ok: (res) => {
                    xsh.showNotification({
                        message: res.message,
                        callback: () => {
                            if (onSuccess) onSuccess(res);
                            else if (finalRedirect) xsh.redirectTo(finalRedirect);
                        }
                    });
                }
            });
        });
    },
    // AUTO BIND
    autoBindFormActions() {
        const map = {
            '.InvoiceBtnCreate': Invoice.bindFormCreate,
            '.InvoiceBtnUpdate': Invoice.bindFormUpdate,
            '.InvoiceBtnDelete': Invoice.bindFormDelete,
        };

        Object.entries(map).forEach(([selector, handler]) => {
            $(selector).each((i, btn) => {
                const $btn = $(btn);
                const formSelector = $btn.data('form');
                const redirectUrl = $btn.data('redirect');
                const apiMethodName = $btn.data('api');

                if (!apiMethodName || typeof this[apiMethodName] !== 'function') return;

                const options = {
                    button: $btn,
                    form: formSelector,
                    apiMethod: this[apiMethodName],
                    redirectUrl
                };

                handler.call(this, options);
            });
        });
    }
};
// AUTO BIND
Invoice.autoBindFormActions();
const Listing = {
    // BUTTON
    BtnGet   : $('.ListingBtnGet'),
    BtnGetAll: $('.ListingBtnGetAll'),
    BtnCreate: $('.ListingBtnCreate'),
    BtnUpdate: $('.ListingBtnUpdate'),
    BtnDelete: $('.ListingBtnDelete'),

    // FORM
    FrmGet   : $('.ListingFrmGet'),
    FrmGetAll: $('.ListingFrmGetAll'),
    FrmCreate: $('.ListingFrmCreate'),
    FrmUpdate: $('.ListingFrmUpdate'),
    FrmDelete: $('.ListingFrmDelete'),

    // MODAL
    MdlGet   : $('.ListingMdlGet'),
    MdlGetAll: $('.ListingMdlGetAll'),
    MdlCreate: $('.ListingMdlCreate'),
    MdlUpdate: $('.ListingMdlUpdate'),
    MdlDelete: $('.ListingMdlDelete'),

    // MODAL BUTTON
    BtnMdlGet   : $('.ListingBtnMdlGet'),
    BtnMdlGetAll: $('.ListingBtnMdlGetAll'),
    BtnMdlCreate: $('.ListingBtnMdlCreate'),
    BtnMdlUpdate: $('.ListingBtnMdlUpdate'),
    BtnMdlDelete: $('.ListingBtnMdlDelete'),
    // ROUTE
    Route : {
        Api: {
            index:      '/api/listing',
            store:      '/api/listing',
            show:       (id) => '/api/listing/'+id,
            update:     (id) => '/api/listing/'+id,
            destroy:    (id) => '/api/listing/'+id,
        },
        Dashboard: {
            index:      '/dashboard/listing',
            store:      '/dashboard/listing',
            create:     '/dashboard/listing/create',
            show:       (id) => '/dashboard/listing/'+id,
            update:     (id) => '/dashboard/listing/'+id,
            edit:       (id) => '/dashboard/listing/'+id+'/edit',
        },
        Panel: {
            index:      '/panel/listing',
            store:      '/panel/listing',
            create:     '/panel/listing/create',
            show:       (id) => '/panel/listing/'+id,
            update:     (id) => '/panel/listing/'+id,
            edit:       (id) => '/panel/listing/'+id+'/edit',
        },
        Site: {
            index:      '/listing',
            store:      '/listing',
            create:     '/listing/create',
            show:       (id) => '/listing/'+id,
            update:     (id) => '/listing/'+id,
            edit:       (id) => '/listing/'+id+'/edit',
        },
    },
    // AJAX
    req({type, url, data = {}, ok, err}) {
        $.ajax({
            type,
            url,
            data,
            success: res => ok ? ok(res) : console.log(res.message),
            error  : res => err ? err(res) : console.log(res.responseJSON?.message || res.statusText)
        });
    },
    // API METHODS
    Get:    ({id, ok, err})        => Listing.req({type: 'GET',    url: 'api/listing/'+id, ok, err}),
    GetAll: ({q = {}, ok, err} = {}) => {
        const param = obj => Object.keys(obj)
            .map(key => key + '=' + encodeURIComponent(obj[key]))
            .join('&');

        const url = Object.keys(q).length
            ? 'api/listing?' + param(q)
            : 'api/listing';

        Listing.req({type: 'GET', url, ok, err});
    },
    Create: ({data, ok, err})      => Listing.req({type: 'POST',   url: 'api/listing', data, ok, err}),
    Update: ({id, data, ok, err})  => Listing.req({type: 'PUT',    url: 'api/listing/'+id, data, ok, err}),
    Delete: ({id, ok, err})        => Listing.req({type: 'DELETE', url: 'api/listing/'+id, ok, err}),

    // UPLOAD FILE
    uploadFile({ input, ok, err }) {
        const $input = (input instanceof jQuery) ? input : $(input);
        const fileInput = $input[0];
        if (!$input.length || !fileInput.files.length) return;

        const type = $input.data('type');
        const url = $input.data('upload-url');

        if (!type || !url) {
            console.warn('❌ data-type veya data-upload-url eksik');
            return;
        }

        const isMultiple = EnumUploadMultiple[type] === true;
        const fieldName = fileInput.name || type;

        const formData = new FormData();

        // Çoklu veya tekli dosya ekle
        if (isMultiple) {
            for (let i = 0; i < fileInput.files.length; i++) {
                formData.append(fieldName + '[]', fileInput.files[i]);
            }
        } else {
            formData.append(fieldName, fileInput.files[0]);
        }

        // data-extra-* attributeları topla
        $.each($input.data(), (key, value) => {
            if (key.startsWith('extra')) {
                const cleanKey = key.replace(/^extra/, '').replace(/^[A-Z]/, c => c.toLowerCase());
                formData.append(cleanKey, value);
            }
        });

        $.ajax({
            type: 'POST',
            url,
            data: formData,
            contentType: false,
            processData: false,
            success: res => ok ? ok(res) : console.log(res.message),
            error: res => err ? err(res) : console.log(res.responseJSON?.message || res.statusText)
        });
    },

    // FORM BINDINGS
    bindFormCreate({ button, form = null, apiMethod = null, redirectUrl = null, onSuccess = null, onBefore = null }) {
        button.off('click').click(() => {
            if (typeof onBefore === 'function') onBefore();
            const $btn = $(button);

            let formSelector = form || $btn.data('form');
            if (typeof formSelector === 'string' && !formSelector.startsWith('.') && !formSelector.startsWith('#')) {
                formSelector = '.' + formSelector;
            }
            const finalForm = (formSelector instanceof jQuery) ? formSelector : $(formSelector);

            const finalApiMethod = apiMethod || Listing[$btn.data('api')];
            const finalRedirect = redirectUrl || $btn.data('redirect');

            const data = xsh.getFormData(finalForm);

            finalApiMethod({
                data,
                ok: (res) => {
                    xsh.showNotification({
                        message: res.message,
                        callback: () => {
                            if (onSuccess) onSuccess(res);
                            else if (finalRedirect) xsh.redirectTo(finalRedirect);
                        }
                    });
                }
            });
        });
    },

    bindFormUpdate({ button, form = null, apiMethod = null, redirectUrl = null, onSuccess = null, onBefore = null }) {
        button.off('click').click(() => {
            if (typeof onBefore === 'function') onBefore();
            const $btn = $(button);

            let formSelector = form || $btn.data('form');
            if (typeof formSelector === 'string' && !formSelector.startsWith('.') && !formSelector.startsWith('#')) {
                formSelector = '.' + formSelector;
            }
            const finalForm = (formSelector instanceof jQuery) ? formSelector : $(formSelector);

            const finalApiMethod = apiMethod || Listing[$btn.data('api')];
            const finalRedirect = redirectUrl || $btn.data('redirect');
            const finalId = $btn.data('id');

            const data = xsh.getFormData(finalForm);

            finalApiMethod({
                id: finalId,
                data,
                ok: (res) => {
                    xsh.showNotification({
                        message: res.message,
                        callback: () => {
                            if (onSuccess) onSuccess(res);
                            else if (finalRedirect) xsh.redirectTo(finalRedirect);
                        }
                    });
                }
            });
        });
    },

    bindFormDelete({ button, apiMethod = null, redirectUrl = null, onSuccess = null, onBefore = null }) {
        button.off('click').click(() => {
            if (typeof onBefore === 'function') onBefore();
            const $btn = $(button);
            const finalApiMethod = apiMethod || Listing[$btn.data('api')];
            const finalRedirect = $btn.data('redirect');
            const finalId = $btn.data('id');

            finalApiMethod({
                id: finalId,
                ok: (res) => {
                    xsh.showNotification({
                        message: res.message,
                        callback: () => {
                            if (onSuccess) onSuccess(res);
                            else if (finalRedirect) xsh.redirectTo(finalRedirect);
                        }
                    });
                }
            });
        });
    },
    // AUTO BIND
    autoBindFormActions() {
        const map = {
            '.ListingBtnCreate': Listing.bindFormCreate,
            '.ListingBtnUpdate': Listing.bindFormUpdate,
            '.ListingBtnDelete': Listing.bindFormDelete,
        };

        Object.entries(map).forEach(([selector, handler]) => {
            $(selector).each((i, btn) => {
                const $btn = $(btn);
                const formSelector = $btn.data('form');
                const redirectUrl = $btn.data('redirect');
                const apiMethodName = $btn.data('api');

                if (!apiMethodName || typeof this[apiMethodName] !== 'function') return;

                const options = {
                    button: $btn,
                    form: formSelector,
                    apiMethod: this[apiMethodName],
                    redirectUrl
                };

                handler.call(this, options);
            });
        });
    }
};
// AUTO BIND
Listing.autoBindFormActions();
const ListingCategory = {
    // BUTTON
    BtnGet   : $('.ListingCategoryBtnGet'),
    BtnGetAll: $('.ListingCategoryBtnGetAll'),
    BtnCreate: $('.ListingCategoryBtnCreate'),
    BtnUpdate: $('.ListingCategoryBtnUpdate'),
    BtnDelete: $('.ListingCategoryBtnDelete'),

    // FORM
    FrmGet   : $('.ListingCategoryFrmGet'),
    FrmGetAll: $('.ListingCategoryFrmGetAll'),
    FrmCreate: $('.ListingCategoryFrmCreate'),
    FrmUpdate: $('.ListingCategoryFrmUpdate'),
    FrmDelete: $('.ListingCategoryFrmDelete'),

    // MODAL
    MdlGet   : $('.ListingCategoryMdlGet'),
    MdlGetAll: $('.ListingCategoryMdlGetAll'),
    MdlCreate: $('.ListingCategoryMdlCreate'),
    MdlUpdate: $('.ListingCategoryMdlUpdate'),
    MdlDelete: $('.ListingCategoryMdlDelete'),

    // MODAL BUTTON
    BtnMdlGet   : $('.ListingCategoryBtnMdlGet'),
    BtnMdlGetAll: $('.ListingCategoryBtnMdlGetAll'),
    BtnMdlCreate: $('.ListingCategoryBtnMdlCreate'),
    BtnMdlUpdate: $('.ListingCategoryBtnMdlUpdate'),
    BtnMdlDelete: $('.ListingCategoryBtnMdlDelete'),
    // ROUTE
    Route : {
        Api: {
            index:      '/api/listing-category',
            store:      '/api/listing-category',
            show:       (id) => '/api/listing-category/'+id,
            update:     (id) => '/api/listing-category/'+id,
            destroy:    (id) => '/api/listing-category/'+id,
        },
        Dashboard: {
            index:      '/dashboard/listing-category',
            store:      '/dashboard/listing-category',
            create:     '/dashboard/listing-category/create',
            show:       (id) => '/dashboard/listing-category/'+id,
            update:     (id) => '/dashboard/listing-category/'+id,
            edit:       (id) => '/dashboard/listing-category/'+id+'/edit',
        },
        Panel: {
            index:      '/panel/listing-category',
            store:      '/panel/listing-category',
            create:     '/panel/listing-category/create',
            show:       (id) => '/panel/listing-category/'+id,
            update:     (id) => '/panel/listing-category/'+id,
            edit:       (id) => '/panel/listing-category/'+id+'/edit',
        },
        Site: {
            index:      '/listing-category',
            store:      '/listing-category',
            create:     '/listing-category/create',
            show:       (id) => '/listing-category/'+id,
            update:     (id) => '/listing-category/'+id,
            edit:       (id) => '/listing-category/'+id+'/edit',
        },
    },
    // AJAX
    req({type, url, data = {}, ok, err}) {
        $.ajax({
            type,
            url,
            data,
            success: res => ok ? ok(res) : console.log(res.message),
            error  : res => err ? err(res) : console.log(res.responseJSON?.message || res.statusText)
        });
    },
    // API METHODS
    Get:    ({id, ok, err})        => ListingCategory.req({type: 'GET',    url: 'api/listing-category/'+id, ok, err}),
    GetAll: ({q = {}, ok, err} = {}) => {
        const param = obj => Object.keys(obj)
            .map(key => key + '=' + encodeURIComponent(obj[key]))
            .join('&');

        const url = Object.keys(q).length
            ? 'api/listing-category?' + param(q)
            : 'api/listing-category';

        ListingCategory.req({type: 'GET', url, ok, err});
    },
    Create: ({data, ok, err})      => ListingCategory.req({type: 'POST',   url: 'api/listing-category', data, ok, err}),
    Update: ({id, data, ok, err})  => ListingCategory.req({type: 'PUT',    url: 'api/listing-category/'+id, data, ok, err}),
    Delete: ({id, ok, err})        => ListingCategory.req({type: 'DELETE', url: 'api/listing-category/'+id, ok, err}),

    // UPLOAD FILE
    uploadFile({ input, ok, err }) {
        const $input = (input instanceof jQuery) ? input : $(input);
        const fileInput = $input[0];
        if (!$input.length || !fileInput.files.length) return;

        const type = $input.data('type');
        const url = $input.data('upload-url');

        if (!type || !url) {
            console.warn('❌ data-type veya data-upload-url eksik');
            return;
        }

        const isMultiple = EnumUploadMultiple[type] === true;
        const fieldName = fileInput.name || type;

        const formData = new FormData();

        // Çoklu veya tekli dosya ekle
        if (isMultiple) {
            for (let i = 0; i < fileInput.files.length; i++) {
                formData.append(fieldName + '[]', fileInput.files[i]);
            }
        } else {
            formData.append(fieldName, fileInput.files[0]);
        }

        // data-extra-* attributeları topla
        $.each($input.data(), (key, value) => {
            if (key.startsWith('extra')) {
                const cleanKey = key.replace(/^extra/, '').replace(/^[A-Z]/, c => c.toLowerCase());
                formData.append(cleanKey, value);
            }
        });

        $.ajax({
            type: 'POST',
            url,
            data: formData,
            contentType: false,
            processData: false,
            success: res => ok ? ok(res) : console.log(res.message),
            error: res => err ? err(res) : console.log(res.responseJSON?.message || res.statusText)
        });
    },

    // FORM BINDINGS
    bindFormCreate({ button, form = null, apiMethod = null, redirectUrl = null, onSuccess = null, onBefore = null }) {
        button.off('click').click(() => {
            if (typeof onBefore === 'function') onBefore();
            const $btn = $(button);

            let formSelector = form || $btn.data('form');
            if (typeof formSelector === 'string' && !formSelector.startsWith('.') && !formSelector.startsWith('#')) {
                formSelector = '.' + formSelector;
            }
            const finalForm = (formSelector instanceof jQuery) ? formSelector : $(formSelector);

            const finalApiMethod = apiMethod || ListingCategory[$btn.data('api')];
            const finalRedirect = redirectUrl || $btn.data('redirect');

            const data = xsh.getFormData(finalForm);

            finalApiMethod({
                data,
                ok: (res) => {
                    xsh.showNotification({
                        message: res.message,
                        callback: () => {
                            if (onSuccess) onSuccess(res);
                            else if (finalRedirect) xsh.redirectTo(finalRedirect);
                        }
                    });
                }
            });
        });
    },

    bindFormUpdate({ button, form = null, apiMethod = null, redirectUrl = null, onSuccess = null, onBefore = null }) {
        button.off('click').click(() => {
            if (typeof onBefore === 'function') onBefore();
            const $btn = $(button);

            let formSelector = form || $btn.data('form');
            if (typeof formSelector === 'string' && !formSelector.startsWith('.') && !formSelector.startsWith('#')) {
                formSelector = '.' + formSelector;
            }
            const finalForm = (formSelector instanceof jQuery) ? formSelector : $(formSelector);

            const finalApiMethod = apiMethod || ListingCategory[$btn.data('api')];
            const finalRedirect = redirectUrl || $btn.data('redirect');
            const finalId = $btn.data('id');

            const data = xsh.getFormData(finalForm);

            finalApiMethod({
                id: finalId,
                data,
                ok: (res) => {
                    xsh.showNotification({
                        message: res.message,
                        callback: () => {
                            if (onSuccess) onSuccess(res);
                            else if (finalRedirect) xsh.redirectTo(finalRedirect);
                        }
                    });
                }
            });
        });
    },

    bindFormDelete({ button, apiMethod = null, redirectUrl = null, onSuccess = null, onBefore = null }) {
        button.off('click').click(() => {
            if (typeof onBefore === 'function') onBefore();
            const $btn = $(button);
            const finalApiMethod = apiMethod || ListingCategory[$btn.data('api')];
            const finalRedirect = $btn.data('redirect');
            const finalId = $btn.data('id');

            finalApiMethod({
                id: finalId,
                ok: (res) => {
                    xsh.showNotification({
                        message: res.message,
                        callback: () => {
                            if (onSuccess) onSuccess(res);
                            else if (finalRedirect) xsh.redirectTo(finalRedirect);
                        }
                    });
                }
            });
        });
    },
    // AUTO BIND
    autoBindFormActions() {
        const map = {
            '.ListingCategoryBtnCreate': ListingCategory.bindFormCreate,
            '.ListingCategoryBtnUpdate': ListingCategory.bindFormUpdate,
            '.ListingCategoryBtnDelete': ListingCategory.bindFormDelete,
        };

        Object.entries(map).forEach(([selector, handler]) => {
            $(selector).each((i, btn) => {
                const $btn = $(btn);
                const formSelector = $btn.data('form');
                const redirectUrl = $btn.data('redirect');
                const apiMethodName = $btn.data('api');

                if (!apiMethodName || typeof this[apiMethodName] !== 'function') return;

                const options = {
                    button: $btn,
                    form: formSelector,
                    apiMethod: this[apiMethodName],
                    redirectUrl
                };

                handler.call(this, options);
            });
        });
    }
};
// AUTO BIND
ListingCategory.autoBindFormActions();
const Location = {
    // BUTTON
    BtnGet   : $('.LocationBtnGet'),
    BtnGetAll: $('.LocationBtnGetAll'),
    BtnCreate: $('.LocationBtnCreate'),
    BtnUpdate: $('.LocationBtnUpdate'),
    BtnDelete: $('.LocationBtnDelete'),

    // FORM
    FrmGet   : $('.LocationFrmGet'),
    FrmGetAll: $('.LocationFrmGetAll'),
    FrmCreate: $('.LocationFrmCreate'),
    FrmUpdate: $('.LocationFrmUpdate'),
    FrmDelete: $('.LocationFrmDelete'),

    // MODAL
    MdlGet   : $('.LocationMdlGet'),
    MdlGetAll: $('.LocationMdlGetAll'),
    MdlCreate: $('.LocationMdlCreate'),
    MdlUpdate: $('.LocationMdlUpdate'),
    MdlDelete: $('.LocationMdlDelete'),

    // MODAL BUTTON
    BtnMdlGet   : $('.LocationBtnMdlGet'),
    BtnMdlGetAll: $('.LocationBtnMdlGetAll'),
    BtnMdlCreate: $('.LocationBtnMdlCreate'),
    BtnMdlUpdate: $('.LocationBtnMdlUpdate'),
    BtnMdlDelete: $('.LocationBtnMdlDelete'),
    // ROUTE
    Route : {
        Api: {
            index:      '/api/location',
            store:      '/api/location',
            show:       (id) => '/api/location/'+id,
            update:     (id) => '/api/location/'+id,
            destroy:    (id) => '/api/location/'+id,
        },
        Dashboard: {
            index:      '/dashboard/location',
            store:      '/dashboard/location',
            create:     '/dashboard/location/create',
            show:       (id) => '/dashboard/location/'+id,
            update:     (id) => '/dashboard/location/'+id,
            edit:       (id) => '/dashboard/location/'+id+'/edit',
        },
        Panel: {
            index:      '/panel/location',
            store:      '/panel/location',
            create:     '/panel/location/create',
            show:       (id) => '/panel/location/'+id,
            update:     (id) => '/panel/location/'+id,
            edit:       (id) => '/panel/location/'+id+'/edit',
        },
        Site: {
            index:      '/location',
            store:      '/location',
            create:     '/location/create',
            show:       (id) => '/location/'+id,
            update:     (id) => '/location/'+id,
            edit:       (id) => '/location/'+id+'/edit',
        },
    },
    // AJAX
    req({type, url, data = {}, ok, err}) {
        $.ajax({
            type,
            url,
            data,
            success: res => ok ? ok(res) : console.log(res.message),
            error  : res => err ? err(res) : console.log(res.responseJSON?.message || res.statusText)
        });
    },
    // API METHODS
    Get:    ({id, ok, err})        => Location.req({type: 'GET',    url: 'api/location/'+id, ok, err}),
    GetAll: ({q = {}, ok, err} = {}) => {
        const param = obj => Object.keys(obj)
            .map(key => key + '=' + encodeURIComponent(obj[key]))
            .join('&');

        const url = Object.keys(q).length
            ? 'api/location?' + param(q)
            : 'api/location';

        Location.req({type: 'GET', url, ok, err});
    },
    Create: ({data, ok, err})      => Location.req({type: 'POST',   url: 'api/location', data, ok, err}),
    Update: ({id, data, ok, err})  => Location.req({type: 'PUT',    url: 'api/location/'+id, data, ok, err}),
    Delete: ({id, ok, err})        => Location.req({type: 'DELETE', url: 'api/location/'+id, ok, err}),

    // UPLOAD FILE
    uploadFile({ input, ok, err }) {
        const $input = (input instanceof jQuery) ? input : $(input);
        const fileInput = $input[0];
        if (!$input.length || !fileInput.files.length) return;

        const type = $input.data('type');
        const url = $input.data('upload-url');

        if (!type || !url) {
            console.warn('❌ data-type veya data-upload-url eksik');
            return;
        }

        const isMultiple = EnumUploadMultiple[type] === true;
        const fieldName = fileInput.name || type;

        const formData = new FormData();

        // Çoklu veya tekli dosya ekle
        if (isMultiple) {
            for (let i = 0; i < fileInput.files.length; i++) {
                formData.append(fieldName + '[]', fileInput.files[i]);
            }
        } else {
            formData.append(fieldName, fileInput.files[0]);
        }

        // data-extra-* attributeları topla
        $.each($input.data(), (key, value) => {
            if (key.startsWith('extra')) {
                const cleanKey = key.replace(/^extra/, '').replace(/^[A-Z]/, c => c.toLowerCase());
                formData.append(cleanKey, value);
            }
        });

        $.ajax({
            type: 'POST',
            url,
            data: formData,
            contentType: false,
            processData: false,
            success: res => ok ? ok(res) : console.log(res.message),
            error: res => err ? err(res) : console.log(res.responseJSON?.message || res.statusText)
        });
    },

    // FORM BINDINGS
    bindFormCreate({ button, form = null, apiMethod = null, redirectUrl = null, onSuccess = null, onBefore = null }) {
        button.off('click').click(() => {
            if (typeof onBefore === 'function') onBefore();
            const $btn = $(button);

            let formSelector = form || $btn.data('form');
            if (typeof formSelector === 'string' && !formSelector.startsWith('.') && !formSelector.startsWith('#')) {
                formSelector = '.' + formSelector;
            }
            const finalForm = (formSelector instanceof jQuery) ? formSelector : $(formSelector);

            const finalApiMethod = apiMethod || Location[$btn.data('api')];
            const finalRedirect = redirectUrl || $btn.data('redirect');

            const data = xsh.getFormData(finalForm);

            finalApiMethod({
                data,
                ok: (res) => {
                    xsh.showNotification({
                        message: res.message,
                        callback: () => {
                            if (onSuccess) onSuccess(res);
                            else if (finalRedirect) xsh.redirectTo(finalRedirect);
                        }
                    });
                }
            });
        });
    },

    bindFormUpdate({ button, form = null, apiMethod = null, redirectUrl = null, onSuccess = null, onBefore = null }) {
        button.off('click').click(() => {
            if (typeof onBefore === 'function') onBefore();
            const $btn = $(button);

            let formSelector = form || $btn.data('form');
            if (typeof formSelector === 'string' && !formSelector.startsWith('.') && !formSelector.startsWith('#')) {
                formSelector = '.' + formSelector;
            }
            const finalForm = (formSelector instanceof jQuery) ? formSelector : $(formSelector);

            const finalApiMethod = apiMethod || Location[$btn.data('api')];
            const finalRedirect = redirectUrl || $btn.data('redirect');
            const finalId = $btn.data('id');

            const data = xsh.getFormData(finalForm);

            finalApiMethod({
                id: finalId,
                data,
                ok: (res) => {
                    xsh.showNotification({
                        message: res.message,
                        callback: () => {
                            if (onSuccess) onSuccess(res);
                            else if (finalRedirect) xsh.redirectTo(finalRedirect);
                        }
                    });
                }
            });
        });
    },

    bindFormDelete({ button, apiMethod = null, redirectUrl = null, onSuccess = null, onBefore = null }) {
        button.off('click').click(() => {
            if (typeof onBefore === 'function') onBefore();
            const $btn = $(button);
            const finalApiMethod = apiMethod || Location[$btn.data('api')];
            const finalRedirect = $btn.data('redirect');
            const finalId = $btn.data('id');

            finalApiMethod({
                id: finalId,
                ok: (res) => {
                    xsh.showNotification({
                        message: res.message,
                        callback: () => {
                            if (onSuccess) onSuccess(res);
                            else if (finalRedirect) xsh.redirectTo(finalRedirect);
                        }
                    });
                }
            });
        });
    },
    // AUTO BIND
    autoBindFormActions() {
        const map = {
            '.LocationBtnCreate': Location.bindFormCreate,
            '.LocationBtnUpdate': Location.bindFormUpdate,
            '.LocationBtnDelete': Location.bindFormDelete,
        };

        Object.entries(map).forEach(([selector, handler]) => {
            $(selector).each((i, btn) => {
                const $btn = $(btn);
                const formSelector = $btn.data('form');
                const redirectUrl = $btn.data('redirect');
                const apiMethodName = $btn.data('api');

                if (!apiMethodName || typeof this[apiMethodName] !== 'function') return;

                const options = {
                    button: $btn,
                    form: formSelector,
                    apiMethod: this[apiMethodName],
                    redirectUrl
                };

                handler.call(this, options);
            });
        });
    }
};
// AUTO BIND
Location.autoBindFormActions();
const Migration = {
    // BUTTON
    BtnGet   : $('.MigrationBtnGet'),
    BtnGetAll: $('.MigrationBtnGetAll'),
    BtnCreate: $('.MigrationBtnCreate'),
    BtnUpdate: $('.MigrationBtnUpdate'),
    BtnDelete: $('.MigrationBtnDelete'),

    // FORM
    FrmGet   : $('.MigrationFrmGet'),
    FrmGetAll: $('.MigrationFrmGetAll'),
    FrmCreate: $('.MigrationFrmCreate'),
    FrmUpdate: $('.MigrationFrmUpdate'),
    FrmDelete: $('.MigrationFrmDelete'),

    // MODAL
    MdlGet   : $('.MigrationMdlGet'),
    MdlGetAll: $('.MigrationMdlGetAll'),
    MdlCreate: $('.MigrationMdlCreate'),
    MdlUpdate: $('.MigrationMdlUpdate'),
    MdlDelete: $('.MigrationMdlDelete'),

    // MODAL BUTTON
    BtnMdlGet   : $('.MigrationBtnMdlGet'),
    BtnMdlGetAll: $('.MigrationBtnMdlGetAll'),
    BtnMdlCreate: $('.MigrationBtnMdlCreate'),
    BtnMdlUpdate: $('.MigrationBtnMdlUpdate'),
    BtnMdlDelete: $('.MigrationBtnMdlDelete'),
    // ROUTE
    Route : {
        Api: {
            index:      '/api/migration',
            store:      '/api/migration',
            show:       (id) => '/api/migration/'+id,
            update:     (id) => '/api/migration/'+id,
            destroy:    (id) => '/api/migration/'+id,
        },
        Dashboard: {
            index:      '/dashboard/migration',
            store:      '/dashboard/migration',
            create:     '/dashboard/migration/create',
            show:       (id) => '/dashboard/migration/'+id,
            update:     (id) => '/dashboard/migration/'+id,
            edit:       (id) => '/dashboard/migration/'+id+'/edit',
        },
        Panel: {
            index:      '/panel/migration',
            store:      '/panel/migration',
            create:     '/panel/migration/create',
            show:       (id) => '/panel/migration/'+id,
            update:     (id) => '/panel/migration/'+id,
            edit:       (id) => '/panel/migration/'+id+'/edit',
        },
        Site: {
            index:      '/migration',
            store:      '/migration',
            create:     '/migration/create',
            show:       (id) => '/migration/'+id,
            update:     (id) => '/migration/'+id,
            edit:       (id) => '/migration/'+id+'/edit',
        },
    },
    // AJAX
    req({type, url, data = {}, ok, err}) {
        $.ajax({
            type,
            url,
            data,
            success: res => ok ? ok(res) : console.log(res.message),
            error  : res => err ? err(res) : console.log(res.responseJSON?.message || res.statusText)
        });
    },
    // API METHODS
    Get:    ({id, ok, err})        => Migration.req({type: 'GET',    url: 'api/migration/'+id, ok, err}),
    GetAll: ({q = {}, ok, err} = {}) => {
        const param = obj => Object.keys(obj)
            .map(key => key + '=' + encodeURIComponent(obj[key]))
            .join('&');

        const url = Object.keys(q).length
            ? 'api/migration?' + param(q)
            : 'api/migration';

        Migration.req({type: 'GET', url, ok, err});
    },
    Create: ({data, ok, err})      => Migration.req({type: 'POST',   url: 'api/migration', data, ok, err}),
    Update: ({id, data, ok, err})  => Migration.req({type: 'PUT',    url: 'api/migration/'+id, data, ok, err}),
    Delete: ({id, ok, err})        => Migration.req({type: 'DELETE', url: 'api/migration/'+id, ok, err}),

    // UPLOAD FILE
    uploadFile({ input, ok, err }) {
        const $input = (input instanceof jQuery) ? input : $(input);
        const fileInput = $input[0];
        if (!$input.length || !fileInput.files.length) return;

        const type = $input.data('type');
        const url = $input.data('upload-url');

        if (!type || !url) {
            console.warn('❌ data-type veya data-upload-url eksik');
            return;
        }

        const isMultiple = EnumUploadMultiple[type] === true;
        const fieldName = fileInput.name || type;

        const formData = new FormData();

        // Çoklu veya tekli dosya ekle
        if (isMultiple) {
            for (let i = 0; i < fileInput.files.length; i++) {
                formData.append(fieldName + '[]', fileInput.files[i]);
            }
        } else {
            formData.append(fieldName, fileInput.files[0]);
        }

        // data-extra-* attributeları topla
        $.each($input.data(), (key, value) => {
            if (key.startsWith('extra')) {
                const cleanKey = key.replace(/^extra/, '').replace(/^[A-Z]/, c => c.toLowerCase());
                formData.append(cleanKey, value);
            }
        });

        $.ajax({
            type: 'POST',
            url,
            data: formData,
            contentType: false,
            processData: false,
            success: res => ok ? ok(res) : console.log(res.message),
            error: res => err ? err(res) : console.log(res.responseJSON?.message || res.statusText)
        });
    },

    // FORM BINDINGS
    bindFormCreate({ button, form = null, apiMethod = null, redirectUrl = null, onSuccess = null, onBefore = null }) {
        button.off('click').click(() => {
            if (typeof onBefore === 'function') onBefore();
            const $btn = $(button);

            let formSelector = form || $btn.data('form');
            if (typeof formSelector === 'string' && !formSelector.startsWith('.') && !formSelector.startsWith('#')) {
                formSelector = '.' + formSelector;
            }
            const finalForm = (formSelector instanceof jQuery) ? formSelector : $(formSelector);

            const finalApiMethod = apiMethod || Migration[$btn.data('api')];
            const finalRedirect = redirectUrl || $btn.data('redirect');

            const data = xsh.getFormData(finalForm);

            finalApiMethod({
                data,
                ok: (res) => {
                    xsh.showNotification({
                        message: res.message,
                        callback: () => {
                            if (onSuccess) onSuccess(res);
                            else if (finalRedirect) xsh.redirectTo(finalRedirect);
                        }
                    });
                }
            });
        });
    },

    bindFormUpdate({ button, form = null, apiMethod = null, redirectUrl = null, onSuccess = null, onBefore = null }) {
        button.off('click').click(() => {
            if (typeof onBefore === 'function') onBefore();
            const $btn = $(button);

            let formSelector = form || $btn.data('form');
            if (typeof formSelector === 'string' && !formSelector.startsWith('.') && !formSelector.startsWith('#')) {
                formSelector = '.' + formSelector;
            }
            const finalForm = (formSelector instanceof jQuery) ? formSelector : $(formSelector);

            const finalApiMethod = apiMethod || Migration[$btn.data('api')];
            const finalRedirect = redirectUrl || $btn.data('redirect');
            const finalId = $btn.data('id');

            const data = xsh.getFormData(finalForm);

            finalApiMethod({
                id: finalId,
                data,
                ok: (res) => {
                    xsh.showNotification({
                        message: res.message,
                        callback: () => {
                            if (onSuccess) onSuccess(res);
                            else if (finalRedirect) xsh.redirectTo(finalRedirect);
                        }
                    });
                }
            });
        });
    },

    bindFormDelete({ button, apiMethod = null, redirectUrl = null, onSuccess = null, onBefore = null }) {
        button.off('click').click(() => {
            if (typeof onBefore === 'function') onBefore();
            const $btn = $(button);
            const finalApiMethod = apiMethod || Migration[$btn.data('api')];
            const finalRedirect = $btn.data('redirect');
            const finalId = $btn.data('id');

            finalApiMethod({
                id: finalId,
                ok: (res) => {
                    xsh.showNotification({
                        message: res.message,
                        callback: () => {
                            if (onSuccess) onSuccess(res);
                            else if (finalRedirect) xsh.redirectTo(finalRedirect);
                        }
                    });
                }
            });
        });
    },
    // AUTO BIND
    autoBindFormActions() {
        const map = {
            '.MigrationBtnCreate': Migration.bindFormCreate,
            '.MigrationBtnUpdate': Migration.bindFormUpdate,
            '.MigrationBtnDelete': Migration.bindFormDelete,
        };

        Object.entries(map).forEach(([selector, handler]) => {
            $(selector).each((i, btn) => {
                const $btn = $(btn);
                const formSelector = $btn.data('form');
                const redirectUrl = $btn.data('redirect');
                const apiMethodName = $btn.data('api');

                if (!apiMethodName || typeof this[apiMethodName] !== 'function') return;

                const options = {
                    button: $btn,
                    form: formSelector,
                    apiMethod: this[apiMethodName],
                    redirectUrl
                };

                handler.call(this, options);
            });
        });
    }
};
// AUTO BIND
Migration.autoBindFormActions();
const Settings = {
    // BUTTON
    BtnGet   : $('.SettingsBtnGet'),
    BtnGetAll: $('.SettingsBtnGetAll'),
    BtnCreate: $('.SettingsBtnCreate'),
    BtnUpdate: $('.SettingsBtnUpdate'),
    BtnDelete: $('.SettingsBtnDelete'),

    // FORM
    FrmGet   : $('.SettingsFrmGet'),
    FrmGetAll: $('.SettingsFrmGetAll'),
    FrmCreate: $('.SettingsFrmCreate'),
    FrmUpdate: $('.SettingsFrmUpdate'),
    FrmDelete: $('.SettingsFrmDelete'),

    // MODAL
    MdlGet   : $('.SettingsMdlGet'),
    MdlGetAll: $('.SettingsMdlGetAll'),
    MdlCreate: $('.SettingsMdlCreate'),
    MdlUpdate: $('.SettingsMdlUpdate'),
    MdlDelete: $('.SettingsMdlDelete'),

    // MODAL BUTTON
    BtnMdlGet   : $('.SettingsBtnMdlGet'),
    BtnMdlGetAll: $('.SettingsBtnMdlGetAll'),
    BtnMdlCreate: $('.SettingsBtnMdlCreate'),
    BtnMdlUpdate: $('.SettingsBtnMdlUpdate'),
    BtnMdlDelete: $('.SettingsBtnMdlDelete'),
    // ROUTE
    Route : {
        Api: {
            index:      '/api/settings',
            store:      '/api/settings',
            show:       (id) => '/api/settings/'+id,
            update:     (id) => '/api/settings/'+id,
            destroy:    (id) => '/api/settings/'+id,
        },
        Dashboard: {
            index:      '/dashboard/settings',
            store:      '/dashboard/settings',
            create:     '/dashboard/settings/create',
            show:       (id) => '/dashboard/settings/'+id,
            update:     (id) => '/dashboard/settings/'+id,
            edit:       (id) => '/dashboard/settings/'+id+'/edit',
        },
        Panel: {
            index:      '/panel/settings',
            store:      '/panel/settings',
            create:     '/panel/settings/create',
            show:       (id) => '/panel/settings/'+id,
            update:     (id) => '/panel/settings/'+id,
            edit:       (id) => '/panel/settings/'+id+'/edit',
        },
        Site: {
            index:      '/settings',
            store:      '/settings',
            create:     '/settings/create',
            show:       (id) => '/settings/'+id,
            update:     (id) => '/settings/'+id,
            edit:       (id) => '/settings/'+id+'/edit',
        },
    },
    // AJAX
    req({type, url, data = {}, ok, err}) {
        $.ajax({
            type,
            url,
            data,
            success: res => ok ? ok(res) : console.log(res.message),
            error  : res => err ? err(res) : console.log(res.responseJSON?.message || res.statusText)
        });
    },
    // API METHODS
    Get:    ({id, ok, err})        => Settings.req({type: 'GET',    url: 'api/settings/'+id, ok, err}),
    GetAll: ({q = {}, ok, err} = {}) => {
        const param = obj => Object.keys(obj)
            .map(key => key + '=' + encodeURIComponent(obj[key]))
            .join('&');

        const url = Object.keys(q).length
            ? 'api/settings?' + param(q)
            : 'api/settings';

        Settings.req({type: 'GET', url, ok, err});
    },
    Create: ({data, ok, err})      => Settings.req({type: 'POST',   url: 'api/settings', data, ok, err}),
    Update: ({id, data, ok, err})  => Settings.req({type: 'PUT',    url: 'api/settings/'+id, data, ok, err}),
    Delete: ({id, ok, err})        => Settings.req({type: 'DELETE', url: 'api/settings/'+id, ok, err}),

    // UPLOAD FILE
    uploadFile({ input, ok, err }) {
        const $input = (input instanceof jQuery) ? input : $(input);
        const fileInput = $input[0];
        if (!$input.length || !fileInput.files.length) return;

        const type = $input.data('type');
        const url = $input.data('upload-url');

        if (!type || !url) {
            console.warn('❌ data-type veya data-upload-url eksik');
            return;
        }

        const isMultiple = EnumUploadMultiple[type] === true;
        const fieldName = fileInput.name || type;

        const formData = new FormData();

        // Çoklu veya tekli dosya ekle
        if (isMultiple) {
            for (let i = 0; i < fileInput.files.length; i++) {
                formData.append(fieldName + '[]', fileInput.files[i]);
            }
        } else {
            formData.append(fieldName, fileInput.files[0]);
        }

        // data-extra-* attributeları topla
        $.each($input.data(), (key, value) => {
            if (key.startsWith('extra')) {
                const cleanKey = key.replace(/^extra/, '').replace(/^[A-Z]/, c => c.toLowerCase());
                formData.append(cleanKey, value);
            }
        });

        $.ajax({
            type: 'POST',
            url,
            data: formData,
            contentType: false,
            processData: false,
            success: res => ok ? ok(res) : console.log(res.message),
            error: res => err ? err(res) : console.log(res.responseJSON?.message || res.statusText)
        });
    },

    // FORM BINDINGS
    bindFormCreate({ button, form = null, apiMethod = null, redirectUrl = null, onSuccess = null, onBefore = null }) {
        button.off('click').click(() => {
            if (typeof onBefore === 'function') onBefore();
            const $btn = $(button);

            let formSelector = form || $btn.data('form');
            if (typeof formSelector === 'string' && !formSelector.startsWith('.') && !formSelector.startsWith('#')) {
                formSelector = '.' + formSelector;
            }
            const finalForm = (formSelector instanceof jQuery) ? formSelector : $(formSelector);

            const finalApiMethod = apiMethod || Settings[$btn.data('api')];
            const finalRedirect = redirectUrl || $btn.data('redirect');

            const data = xsh.getFormData(finalForm);

            finalApiMethod({
                data,
                ok: (res) => {
                    xsh.showNotification({
                        message: res.message,
                        callback: () => {
                            if (onSuccess) onSuccess(res);
                            else if (finalRedirect) xsh.redirectTo(finalRedirect);
                        }
                    });
                }
            });
        });
    },

    bindFormUpdate({ button, form = null, apiMethod = null, redirectUrl = null, onSuccess = null, onBefore = null }) {
        button.off('click').click(() => {
            if (typeof onBefore === 'function') onBefore();
            const $btn = $(button);

            let formSelector = form || $btn.data('form');
            if (typeof formSelector === 'string' && !formSelector.startsWith('.') && !formSelector.startsWith('#')) {
                formSelector = '.' + formSelector;
            }
            const finalForm = (formSelector instanceof jQuery) ? formSelector : $(formSelector);

            const finalApiMethod = apiMethod || Settings[$btn.data('api')];
            const finalRedirect = redirectUrl || $btn.data('redirect');
            const finalId = $btn.data('id');

            const data = xsh.getFormData(finalForm);

            finalApiMethod({
                id: finalId,
                data,
                ok: (res) => {
                    xsh.showNotification({
                        message: res.message,
                        callback: () => {
                            if (onSuccess) onSuccess(res);
                            else if (finalRedirect) xsh.redirectTo(finalRedirect);
                        }
                    });
                }
            });
        });
    },

    bindFormDelete({ button, apiMethod = null, redirectUrl = null, onSuccess = null, onBefore = null }) {
        button.off('click').click(() => {
            if (typeof onBefore === 'function') onBefore();
            const $btn = $(button);
            const finalApiMethod = apiMethod || Settings[$btn.data('api')];
            const finalRedirect = $btn.data('redirect');
            const finalId = $btn.data('id');

            finalApiMethod({
                id: finalId,
                ok: (res) => {
                    xsh.showNotification({
                        message: res.message,
                        callback: () => {
                            if (onSuccess) onSuccess(res);
                            else if (finalRedirect) xsh.redirectTo(finalRedirect);
                        }
                    });
                }
            });
        });
    },
    // AUTO BIND
    autoBindFormActions() {
        const map = {
            '.SettingsBtnCreate': Settings.bindFormCreate,
            '.SettingsBtnUpdate': Settings.bindFormUpdate,
            '.SettingsBtnDelete': Settings.bindFormDelete,
        };

        Object.entries(map).forEach(([selector, handler]) => {
            $(selector).each((i, btn) => {
                const $btn = $(btn);
                const formSelector = $btn.data('form');
                const redirectUrl = $btn.data('redirect');
                const apiMethodName = $btn.data('api');

                if (!apiMethodName || typeof this[apiMethodName] !== 'function') return;

                const options = {
                    button: $btn,
                    form: formSelector,
                    apiMethod: this[apiMethodName],
                    redirectUrl
                };

                handler.call(this, options);
            });
        });
    }
};
// AUTO BIND
Settings.autoBindFormActions();
const User = {
    // BUTTON
    BtnGet   : $('.UserBtnGet'),
    BtnGetAll: $('.UserBtnGetAll'),
    BtnCreate: $('.UserBtnCreate'),
    BtnUpdate: $('.UserBtnUpdate'),
    BtnDelete: $('.UserBtnDelete'),

    // FORM
    FrmGet   : $('.UserFrmGet'),
    FrmGetAll: $('.UserFrmGetAll'),
    FrmCreate: $('.UserFrmCreate'),
    FrmUpdate: $('.UserFrmUpdate'),
    FrmDelete: $('.UserFrmDelete'),

    // MODAL
    MdlGet   : $('.UserMdlGet'),
    MdlGetAll: $('.UserMdlGetAll'),
    MdlCreate: $('.UserMdlCreate'),
    MdlUpdate: $('.UserMdlUpdate'),
    MdlDelete: $('.UserMdlDelete'),

    // MODAL BUTTON
    BtnMdlGet   : $('.UserBtnMdlGet'),
    BtnMdlGetAll: $('.UserBtnMdlGetAll'),
    BtnMdlCreate: $('.UserBtnMdlCreate'),
    BtnMdlUpdate: $('.UserBtnMdlUpdate'),
    BtnMdlDelete: $('.UserBtnMdlDelete'),
    // ROUTE
    Route : {
        Api: {
            index:      '/api/user',
            store:      '/api/user',
            show:       (id) => '/api/user/'+id,
            update:     (id) => '/api/user/'+id,
            destroy:    (id) => '/api/user/'+id,
        },
        Dashboard: {
            index:      '/dashboard/user',
            store:      '/dashboard/user',
            create:     '/dashboard/user/create',
            show:       (id) => '/dashboard/user/'+id,
            update:     (id) => '/dashboard/user/'+id,
            edit:       (id) => '/dashboard/user/'+id+'/edit',
        },
        Panel: {
            index:      '/panel/user',
            store:      '/panel/user',
            create:     '/panel/user/create',
            show:       (id) => '/panel/user/'+id,
            update:     (id) => '/panel/user/'+id,
            edit:       (id) => '/panel/user/'+id+'/edit',
        },
        Site: {
            index:      '/user',
            store:      '/user',
            create:     '/user/create',
            show:       (id) => '/user/'+id,
            update:     (id) => '/user/'+id,
            edit:       (id) => '/user/'+id+'/edit',
        },
    },
    // AJAX
    req({type, url, data = {}, ok, err}) {
        $.ajax({
            type,
            url,
            data,
            success: res => ok ? ok(res) : console.log(res.message),
            error  : res => err ? err(res) : console.log(res.responseJSON?.message || res.statusText)
        });
    },
    // API METHODS
    Get:    ({id, ok, err})        => User.req({type: 'GET',    url: 'api/user/'+id, ok, err}),
    GetAll: ({q = {}, ok, err} = {}) => {
        const param = obj => Object.keys(obj)
            .map(key => key + '=' + encodeURIComponent(obj[key]))
            .join('&');

        const url = Object.keys(q).length
            ? 'api/user?' + param(q)
            : 'api/user';

        User.req({type: 'GET', url, ok, err});
    },
    Create: ({data, ok, err})      => User.req({type: 'POST',   url: 'api/user', data, ok, err}),
    Update: ({id, data, ok, err})  => User.req({type: 'PUT',    url: 'api/user/'+id, data, ok, err}),
    Delete: ({id, ok, err})        => User.req({type: 'DELETE', url: 'api/user/'+id, ok, err}),

    // UPLOAD FILE
    uploadFile({ input, ok, err }) {
        const $input = (input instanceof jQuery) ? input : $(input);
        const fileInput = $input[0];
        if (!$input.length || !fileInput.files.length) return;

        const type = $input.data('type');
        const url = $input.data('upload-url');

        if (!type || !url) {
            console.warn('❌ data-type veya data-upload-url eksik');
            return;
        }

        const isMultiple = EnumUploadMultiple[type] === true;
        const fieldName = fileInput.name || type;

        const formData = new FormData();

        // Çoklu veya tekli dosya ekle
        if (isMultiple) {
            for (let i = 0; i < fileInput.files.length; i++) {
                formData.append(fieldName + '[]', fileInput.files[i]);
            }
        } else {
            formData.append(fieldName, fileInput.files[0]);
        }

        // data-extra-* attributeları topla
        $.each($input.data(), (key, value) => {
            if (key.startsWith('extra')) {
                const cleanKey = key.replace(/^extra/, '').replace(/^[A-Z]/, c => c.toLowerCase());
                formData.append(cleanKey, value);
            }
        });

        $.ajax({
            type: 'POST',
            url,
            data: formData,
            contentType: false,
            processData: false,
            success: res => ok ? ok(res) : console.log(res.message),
            error: res => err ? err(res) : console.log(res.responseJSON?.message || res.statusText)
        });
    },

    // FORM BINDINGS
    bindFormCreate({ button, form = null, apiMethod = null, redirectUrl = null, onSuccess = null, onBefore = null }) {
        button.off('click').click(() => {
            if (typeof onBefore === 'function') onBefore();
            const $btn = $(button);

            let formSelector = form || $btn.data('form');
            if (typeof formSelector === 'string' && !formSelector.startsWith('.') && !formSelector.startsWith('#')) {
                formSelector = '.' + formSelector;
            }
            const finalForm = (formSelector instanceof jQuery) ? formSelector : $(formSelector);

            const finalApiMethod = apiMethod || User[$btn.data('api')];
            const finalRedirect = redirectUrl || $btn.data('redirect');

            const data = xsh.getFormData(finalForm);

            finalApiMethod({
                data,
                ok: (res) => {
                    xsh.showNotification({
                        message: res.message,
                        callback: () => {
                            if (onSuccess) onSuccess(res);
                            else if (finalRedirect) xsh.redirectTo(finalRedirect);
                        }
                    });
                }
            });
        });
    },

    bindFormUpdate({ button, form = null, apiMethod = null, redirectUrl = null, onSuccess = null, onBefore = null }) {
        button.off('click').click(() => {
            if (typeof onBefore === 'function') onBefore();
            const $btn = $(button);

            let formSelector = form || $btn.data('form');
            if (typeof formSelector === 'string' && !formSelector.startsWith('.') && !formSelector.startsWith('#')) {
                formSelector = '.' + formSelector;
            }
            const finalForm = (formSelector instanceof jQuery) ? formSelector : $(formSelector);

            const finalApiMethod = apiMethod || User[$btn.data('api')];
            const finalRedirect = redirectUrl || $btn.data('redirect');
            const finalId = $btn.data('id');

            const data = xsh.getFormData(finalForm);

            finalApiMethod({
                id: finalId,
                data,
                ok: (res) => {
                    xsh.showNotification({
                        message: res.message,
                        callback: () => {
                            if (onSuccess) onSuccess(res);
                            else if (finalRedirect) xsh.redirectTo(finalRedirect);
                        }
                    });
                }
            });
        });
    },

    bindFormDelete({ button, apiMethod = null, redirectUrl = null, onSuccess = null, onBefore = null }) {
        button.off('click').click(() => {
            if (typeof onBefore === 'function') onBefore();
            const $btn = $(button);
            const finalApiMethod = apiMethod || User[$btn.data('api')];
            const finalRedirect = $btn.data('redirect');
            const finalId = $btn.data('id');

            finalApiMethod({
                id: finalId,
                ok: (res) => {
                    xsh.showNotification({
                        message: res.message,
                        callback: () => {
                            if (onSuccess) onSuccess(res);
                            else if (finalRedirect) xsh.redirectTo(finalRedirect);
                        }
                    });
                }
            });
        });
    },
    // AUTO BIND
    autoBindFormActions() {
        const map = {
            '.UserBtnCreate': User.bindFormCreate,
            '.UserBtnUpdate': User.bindFormUpdate,
            '.UserBtnDelete': User.bindFormDelete,
        };

        Object.entries(map).forEach(([selector, handler]) => {
            $(selector).each((i, btn) => {
                const $btn = $(btn);
                const formSelector = $btn.data('form');
                const redirectUrl = $btn.data('redirect');
                const apiMethodName = $btn.data('api');

                if (!apiMethodName || typeof this[apiMethodName] !== 'function') return;

                const options = {
                    button: $btn,
                    form: formSelector,
                    apiMethod: this[apiMethodName],
                    redirectUrl
                };

                handler.call(this, options);
            });
        });
    }
};
// AUTO BIND
User.autoBindFormActions();
const Worker = {
    // BUTTON
    BtnGet   : $('.WorkerBtnGet'),
    BtnGetAll: $('.WorkerBtnGetAll'),
    BtnCreate: $('.WorkerBtnCreate'),
    BtnUpdate: $('.WorkerBtnUpdate'),
    BtnDelete: $('.WorkerBtnDelete'),

    // FORM
    FrmGet   : $('.WorkerFrmGet'),
    FrmGetAll: $('.WorkerFrmGetAll'),
    FrmCreate: $('.WorkerFrmCreate'),
    FrmUpdate: $('.WorkerFrmUpdate'),
    FrmDelete: $('.WorkerFrmDelete'),

    // MODAL
    MdlGet   : $('.WorkerMdlGet'),
    MdlGetAll: $('.WorkerMdlGetAll'),
    MdlCreate: $('.WorkerMdlCreate'),
    MdlUpdate: $('.WorkerMdlUpdate'),
    MdlDelete: $('.WorkerMdlDelete'),

    // MODAL BUTTON
    BtnMdlGet   : $('.WorkerBtnMdlGet'),
    BtnMdlGetAll: $('.WorkerBtnMdlGetAll'),
    BtnMdlCreate: $('.WorkerBtnMdlCreate'),
    BtnMdlUpdate: $('.WorkerBtnMdlUpdate'),
    BtnMdlDelete: $('.WorkerBtnMdlDelete'),
    // ROUTE
    Route : {
        Api: {
            index:      '/api/worker',
            store:      '/api/worker',
            show:       (id) => '/api/worker/'+id,
            update:     (id) => '/api/worker/'+id,
            destroy:    (id) => '/api/worker/'+id,
        },
        Dashboard: {
            index:      '/dashboard/worker',
            store:      '/dashboard/worker',
            create:     '/dashboard/worker/create',
            show:       (id) => '/dashboard/worker/'+id,
            update:     (id) => '/dashboard/worker/'+id,
            edit:       (id) => '/dashboard/worker/'+id+'/edit',
        },
        Panel: {
            index:      '/panel/worker',
            store:      '/panel/worker',
            create:     '/panel/worker/create',
            show:       (id) => '/panel/worker/'+id,
            update:     (id) => '/panel/worker/'+id,
            edit:       (id) => '/panel/worker/'+id+'/edit',
        },
        Site: {
            index:      '/worker',
            store:      '/worker',
            create:     '/worker/create',
            show:       (id) => '/worker/'+id,
            update:     (id) => '/worker/'+id,
            edit:       (id) => '/worker/'+id+'/edit',
        },
    },
    // AJAX
    req({type, url, data = {}, ok, err}) {
        $.ajax({
            type,
            url,
            data,
            success: res => ok ? ok(res) : console.log(res.message),
            error  : res => err ? err(res) : console.log(res.responseJSON?.message || res.statusText)
        });
    },
    // API METHODS
    Get:    ({id, ok, err})        => Worker.req({type: 'GET',    url: 'api/worker/'+id, ok, err}),
    GetAll: ({q = {}, ok, err} = {}) => {
        const param = obj => Object.keys(obj)
            .map(key => key + '=' + encodeURIComponent(obj[key]))
            .join('&');

        const url = Object.keys(q).length
            ? 'api/worker?' + param(q)
            : 'api/worker';

        Worker.req({type: 'GET', url, ok, err});
    },
    Create: ({data, ok, err})      => Worker.req({type: 'POST',   url: 'api/worker', data, ok, err}),
    Update: ({id, data, ok, err})  => Worker.req({type: 'PUT',    url: 'api/worker/'+id, data, ok, err}),
    Delete: ({id, ok, err})        => Worker.req({type: 'DELETE', url: 'api/worker/'+id, ok, err}),

    // UPLOAD FILE
    uploadFile({ input, ok, err }) {
        const $input = (input instanceof jQuery) ? input : $(input);
        const fileInput = $input[0];
        if (!$input.length || !fileInput.files.length) return;

        const type = $input.data('type');
        const url = $input.data('upload-url');

        if (!type || !url) {
            console.warn('❌ data-type veya data-upload-url eksik');
            return;
        }

        const isMultiple = EnumUploadMultiple[type] === true;
        const fieldName = fileInput.name || type;

        const formData = new FormData();

        // Çoklu veya tekli dosya ekle
        if (isMultiple) {
            for (let i = 0; i < fileInput.files.length; i++) {
                formData.append(fieldName + '[]', fileInput.files[i]);
            }
        } else {
            formData.append(fieldName, fileInput.files[0]);
        }

        // data-extra-* attributeları topla
        $.each($input.data(), (key, value) => {
            if (key.startsWith('extra')) {
                const cleanKey = key.replace(/^extra/, '').replace(/^[A-Z]/, c => c.toLowerCase());
                formData.append(cleanKey, value);
            }
        });

        $.ajax({
            type: 'POST',
            url,
            data: formData,
            contentType: false,
            processData: false,
            success: res => ok ? ok(res) : console.log(res.message),
            error: res => err ? err(res) : console.log(res.responseJSON?.message || res.statusText)
        });
    },

    // FORM BINDINGS
    bindFormCreate({ button, form = null, apiMethod = null, redirectUrl = null, onSuccess = null, onBefore = null }) {
        button.off('click').click(() => {
            if (typeof onBefore === 'function') onBefore();
            const $btn = $(button);

            let formSelector = form || $btn.data('form');
            if (typeof formSelector === 'string' && !formSelector.startsWith('.') && !formSelector.startsWith('#')) {
                formSelector = '.' + formSelector;
            }
            const finalForm = (formSelector instanceof jQuery) ? formSelector : $(formSelector);

            const finalApiMethod = apiMethod || Worker[$btn.data('api')];
            const finalRedirect = redirectUrl || $btn.data('redirect');

            const data = xsh.getFormData(finalForm);

            finalApiMethod({
                data,
                ok: (res) => {
                    xsh.showNotification({
                        message: res.message,
                        callback: () => {
                            if (onSuccess) onSuccess(res);
                            else if (finalRedirect) xsh.redirectTo(finalRedirect);
                        }
                    });
                }
            });
        });
    },

    bindFormUpdate({ button, form = null, apiMethod = null, redirectUrl = null, onSuccess = null, onBefore = null }) {
        button.off('click').click(() => {
            if (typeof onBefore === 'function') onBefore();
            const $btn = $(button);

            let formSelector = form || $btn.data('form');
            if (typeof formSelector === 'string' && !formSelector.startsWith('.') && !formSelector.startsWith('#')) {
                formSelector = '.' + formSelector;
            }
            const finalForm = (formSelector instanceof jQuery) ? formSelector : $(formSelector);

            const finalApiMethod = apiMethod || Worker[$btn.data('api')];
            const finalRedirect = redirectUrl || $btn.data('redirect');
            const finalId = $btn.data('id');

            const data = xsh.getFormData(finalForm);

            finalApiMethod({
                id: finalId,
                data,
                ok: (res) => {
                    xsh.showNotification({
                        message: res.message,
                        callback: () => {
                            if (onSuccess) onSuccess(res);
                            else if (finalRedirect) xsh.redirectTo(finalRedirect);
                        }
                    });
                }
            });
        });
    },

    bindFormDelete({ button, apiMethod = null, redirectUrl = null, onSuccess = null, onBefore = null }) {
        button.off('click').click(() => {
            if (typeof onBefore === 'function') onBefore();
            const $btn = $(button);
            const finalApiMethod = apiMethod || Worker[$btn.data('api')];
            const finalRedirect = $btn.data('redirect');
            const finalId = $btn.data('id');

            finalApiMethod({
                id: finalId,
                ok: (res) => {
                    xsh.showNotification({
                        message: res.message,
                        callback: () => {
                            if (onSuccess) onSuccess(res);
                            else if (finalRedirect) xsh.redirectTo(finalRedirect);
                        }
                    });
                }
            });
        });
    },
    // AUTO BIND
    autoBindFormActions() {
        const map = {
            '.WorkerBtnCreate': Worker.bindFormCreate,
            '.WorkerBtnUpdate': Worker.bindFormUpdate,
            '.WorkerBtnDelete': Worker.bindFormDelete,
        };

        Object.entries(map).forEach(([selector, handler]) => {
            $(selector).each((i, btn) => {
                const $btn = $(btn);
                const formSelector = $btn.data('form');
                const redirectUrl = $btn.data('redirect');
                const apiMethodName = $btn.data('api');

                if (!apiMethodName || typeof this[apiMethodName] !== 'function') return;

                const options = {
                    button: $btn,
                    form: formSelector,
                    apiMethod: this[apiMethodName],
                    redirectUrl
                };

                handler.call(this, options);
            });
        });
    }
};
// AUTO BIND
Worker.autoBindFormActions();
const Authorize = {
    // BUTTON
    BtnLogin       : $('.BtnLogin'),
    BtnRegister    : $('.BtnRegister'),
    BtnLostPassword: $('.BtnLostPassword'),
    BtnLogout      : $('.BtnLogout'),
    // FORM
    FrmLogin       : $('.FrmLogin'),
    FrmRegister    : $('.FrmRegister'),
    FrmLostPassword: $('.FrmLostPassword'),
    FrmLogout      : $('.FrmLogout'),
    // MODAL
    MdlLogin       : $('.MdlLogin'),
    MdlRegister    : $('.MdlRegister'),
    MdlLostPassword: $('.MdlLostPassword'),
    MdlLogout      : $('.MdlLogout'),
    // MODAL BUTTON
    BtnMdlLogin       : $('.BtnMdlLogin'),
    BtnMdlRegister    : $('.BtnMdlRegister'),
    BtnMdlLostPassword: $('.BtnMdlLostPassword'),
    BtnMdlLogout      : $('.BtnMdlLogout'),
    // ROUTE
    Route : {
        Api: {
            login:              '/api/login',
            loginCheck:         '/api/login',
            lostPassword:       '/api/lost-password',
            lostPasswordCheck:  '/api/lost-password',
            register:           '/api/register',
            registerCheck:      '/api/register',
            logout:             '/api/logout',
            logoutCheck:        '/api/logout',
        },
        Dashboard: {
            login:              '/dashboard/login',
            loginCheck:         '/dashboard/login',
            lostPassword:       '/dashboard/lost-password',
            lostPasswordCheck:  '/dashboard/lost-password',
            register:           '/dashboard/register',
            registerCheck:      '/dashboard/register',
            logout:             '/dashboard/logout',
            logoutCheck:        '/dashboard/logout',
        },
        Panel: {
            login:              '/panel/login',
            loginCheck:         '/panel/login',
            lostPassword:       '/panel/lost-password',
            lostPasswordCheck:  '/panel/lost-password',
            register:           '/panel/register',
            registerCheck:      '/panel/register',
            logout:             '/panel/logout',
            logoutCheck:        '/panel/logout',
        },
        Site: {
            login:              '/login',
            loginCheck:         '/login',
            lostPassword:       '/lost-password',
            lostPasswordCheck:  '/lost-password',
            register:           '/register',
            registerCheck:      '/register',
            logout:             '/logout',
            logoutCheck:        '/logout',
        },
    },
    // AJAX
    req({type, url, data = {}, ok, err}) {
        $.ajax({
                   type,
                   url,
                   data,
                   success: res => ok ? ok(res) : console.log(res.message),
                   error  : res => err ? err(res) : xsh.showNotification({message : res.responseJSON.message})
               });
    },
    Login({data, ok, err}) {
        this.req({type: 'POST', url: 'api/login', data, ok, err});
    },
    Register({data, ok, err}) {
        this.req({type: 'POST', url: 'api/register', data, ok, err});
    },
    LostPassword({data, ok, err}) {
        this.req({type: 'POST', url: 'api/lost-password', data, ok, err});
    },
    Logout({data, ok, err}) {
        this.req({type: 'POST', url: 'api/logout', data, ok, err});
    }
};

// ROUTE BASE
const Route = {
Site : '/',
Panel : '/panel',
Dashboard : '/dashboard',
Api : '/api',
}
