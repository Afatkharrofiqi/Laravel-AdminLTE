@include('layouts.assets.js.customfileinput')
@include('layouts.assets.js.sweetalert')
<script>
    $(function () {
        bsCustomFileInput.init();
    });

    $("input[data-bootstrap-switch]").each(function(){
        $(this).bootstrapSwitch('state', $(this).prop('checked'));
    });

    // replace  default alert js to swal
    // keep default js alert to use in specific cases
    window.legacyAlert = window.alert;

    // types alert and confirm: "success", "error", "warning", "info", "question". Default: "warning"
    // overwrite default js alert
    window.alert = function(msg, title, type, params) {
        const newTitle = (title == null) ? 'Ada yang tidak beres dengan aplikasi' : title;
        const newType = (type == null) ? 'warning' : type;
        Swal.fire($.extend({
            title: newTitle,
            text: msg,
            icon: newType
        }, params || {}));
    };

    function resetForm(formId){
        const form = $(formId).closest('form');
        form.find("input[type=text], textarea, .form-control").removeAttr('value');
        form.find(".select2").val(null).trigger('change');
    }

    const readUrl = (options) => {
        const reader = new FileReader();
        reader.onload = function (e) {
            $(options.selector).attr('src', '' + e.target.result);
        };
        reader.readAsDataURL(options.file);
    };

    const swalSuccess = (message, html = "") => {
        swalSetup('Operasi Sukses', message, 'success', html);
    }
    const swalError = (message, html = "") => {
        swalSetup('Ada kesalahan', message, 'error', html);
    }
    const swalWarning = (message, html = "") => {
        swalSetup('Perhatian', message, 'warning', html);
    }
    const swalCancel = (message, html = "") => {
        swalSetup('Dibatalkan', message, 'error', html);
    }
    const swalValidation = (errors) => {
        let values = '<span>';
        jQuery.each(errors, function(key, value) {
            values += `<p>${value}</p>`;
        });
        swalWarning('Validation Error', values + '</span>');
    }
    const swalConfirm = (options) => {
        Swal.fire({
            title: options.title,
            html: options.html,
            text: "",
            icon: options.icon,
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: options.confirm,
            cancelButtonText: options.cancel
        }).then((result) => {
            if (result.isConfirmed) {
                options.complete(result);
            }
        })
    }
    const swalSetup = (title, message, type, html) => {
        Swal.fire({
            icon: type,
            title: title,
            text: message,
            html: html
        });
    }

    $.xhrPool = []; // array of uncompleted requests
    $.xhrPool.abortAll = function() { // our abort function
        $(this).each(function(idx, jqXHR) {
            jqXHR.abort();
        });
        $.xhrPool.length = 0
    };

    $.ajaxSetup({
        beforeSend: function(xhr) {
            xhr.setRequestHeader('X-CSRF-Token', '{{ csrf_token() }}');
            $.xhrPool.push(xhr);
        },
        error: function(data) {
            const resp = data.responseJSON;
            if (data.status === 422) {
                if(resp.errors) {
                    return swalValidation(resp.errors);
                }
                return swalWarning('', resp.message);
            } else if (data.status === 500) {
                swalError('', resp.error ?? resp.message)
            } else if(data.status === 401) {
                Swal.fire({
                    title: 'Sesi anda telah berakhir!',
                    text: "Silahkan login kembali.",
                    icon: 'warning',
                }).then((result) => {
                    location.href = '/login';
                });
            } else if (data.status === 403) {
                if (typeof resp.errors != 'undefined') {
                    let html = `<p>${resp.message}</p>`;
                    html += '<p>Silahkan hapus role yang tertera dibawah ini:<p>';
                    html += '<span>';
                    resp.errors.forEach((item, index) => {
                        html += `<p>${+index+1}. ${item}</p>`
                    });
                    html += '</span>';
                    swalWarning(resp.message, html);
                } else {
                    swalError('' ,resp.message)
                }
            }
        },
        complete: function(jqXHR) { // when some of the requests completed it will splice from the array
            var index = $.xhrPool.indexOf(jqXHR);
            if (index > -1) {
                $.xhrPool.splice(index, 1);
            }
        }
    });
</script>
