/*
 * @version 1.0.0
 * @link https://codecanyon.net/user/abndevs/portfolio
 * @author Bishwajit Adhikary
 * @copyright (c) 2023 abnDevs
 * @license https://codecanyon.net/licenses/terms/regular
 */

"use strict";

const agreeCheckbox = document.getElementById('agreeCheckbox');
const continueButton = document.getElementById('continueButton');

if (agreeCheckbox) {
    agreeCheckbox.addEventListener('change', function () {
        if (this.checked) {
            continueButton.classList.remove('disabled');
        } else {
            continueButton.classList.add('disabled');
        }
    });
}

let formSelect = document.querySelectorAll(".formSelect");
let formSelectLen = formSelect.length;

if (formSelectLen > 0) {
    for (let i = 0; i < formSelectLen; i++) {
        NiceSelect.bind(formSelect[i], {
            searchable: false
        });
    }
}

let formSelectSearch = document.querySelectorAll(".formSelectwithSearch");
let formSelectSearchLen = formSelectSearch.length;

if (formSelectSearchLen > 0) {
    for (let i = 0; i < formSelectSearchLen; i++) {
        NiceSelect.bind(formSelectSearch[i], {
            searchable: true
        });
    }
}

let anchor = document.querySelectorAll('a[href="#"]');
let anchorLength = anchor.length;

if (anchorLength > 0) {
    for (let i = 0; i < anchorLength; i++) {
        anchor[i].addEventListener('click', function (e) {
            e.preventDefault();
        });
    }
}

let tooltips = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
let tooltipList = tooltips.map(function (tooltipss) {
    return new bootstrap.Tooltip(tooltipss);
});

let flash = function (type, message, url = null) {
    flasher.flash(type, message, {
        timeout: 3000,
    });
}


$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
$.fn.initValidate = function () {
    $(this).validate({
        errorClass: 'is-invalid text-danger', validClass: ''
    });
};

$.fn.initFormValidation = function () {
    var validator = $(this).validate({
        errorClass: 'is-invalid text-danger', highlight: function (element, errorClass) {
            var elem = $(element);
            if (elem.hasClass("select2-hidden-accessible")) {
                $("#select2-" + elem.attr("id") + "-container").parent().addClass(errorClass);
            } else if (elem.hasClass('input-group')) {
                $('#' + elem.add("id")).parents('.input-group').append(errorClass);
            } else {
                elem.addClass(errorClass);
            }
        }, unhighlight: function (element, errorClass) {
            var elem = $(element);
            if (elem.hasClass("select2-hidden-accessible")) {
                $("#select2-" + elem.attr("id") + "-container").parent().removeClass(errorClass);
            } else {
                elem.removeClass(errorClass);
            }
        }, errorPlacement: function (error, element) {
            var elem = $(element);
            if (elem.hasClass("select2-hidden-accessible")) {
                element = $("#select2-" + elem.attr("id") + "-container").parent();
                error.insertAfter(element);
            } else if (elem.parents().hasClass('iti--allow-dropdown')) {
                error.insertAfter(element.parent());
            } else if (elem.parent().hasClass('form-check')) {
                error.insertAfter(element.parent());
            } else if (elem.parent().hasClass('form-floating')) {
                error.insertAfter(element.parent().css('color', 'text-danger'));
            } else if (elem.parent().hasClass('input-group')) {
                error.insertAfter(element.parent());
            } else if (elem.parent().hasClass('custom-checkbox')) {
                error.insertAfter(element.parent());
            } else {
                error.insertAfter(element);
            }
        }
    });
};

/**
 *
 * @param { function } callBack
 */
$.fn.initFormSubmit = function () {
    $(this).initFormValidation();

    let $this = $(this);
    let insideForm = $this.find('button[type="submit"]');

    let submitButton = insideForm.length ? insideForm : $(document).find('button[form="' + $this.attr('id') + '"]');
    let submitButtonText = submitButton.html();

    $(this).ajaxForm({
        dataType: 'json',
        beforeSubmit: function () {
            submitButton.prop('disabled', true);
            submitButton.html(spinnerSM);
            $this.trigger('formSubmitBefore');
            $this.trigger('ajaxFormSubmitBefore');
        },
        success: function (response) {
            if (!response) {
                location.reload();
            }
            if (response.redirect) {
                //Save to local storage
                if (response.message) {
                    localStorage.setItem('messageType', response.status);
                    localStorage.setItem('message', response.message);
                    localStorage.setItem('hasMessage', response.hasMessage);
                }
                window.location.href = response.redirect;
            } else if (response.two_factor) {
                window.location.href = '/two-factor-challenge';
            } else {
                if (response.type === 'warning' || response.status === 'warning') {
                    flash('warning', response.message);
                } else if (response.message) {
                    flash('success', response.message);
                } else {
                    flash('success', 'Successfully saved');
                }
            }

            if (response.reset) {
                $this[0].reset();
            }


            submitButton.html(submitButtonText);
            // Fire event for success
            $this.trigger('formSubmitSuccess', [response]);
            $this.trigger('ajaxFormSuccess', [response]);
        },
        error: function (response) {
            if (response.status == 0){
                submitButton.prop('disabled', false);
                submitButton.html(submitButtonText);

                flash('error', 'Device is offline. Cannot complete request.\'');
                return;
            }

            var errors = response?.responseJSON;
            if (errors.type === 'warning') {
                flash('warning', response.message || response?.responseJSON?.message || 'Something went wrong');
            } else {
                flash('error', response.message || response?.responseJSON?.message || 'Something went wrong');
            }
            showInputErrors(errors);
            submitButton.prop('disabled', false);
            submitButton.html(submitButtonText);

            $this.trigger('formSubmitError', [response]);
            $this.trigger('ajaxFormError', [response]);
        },
        complete: function () {
            submitButton.prop('disabled', false);

            // Fire event for complete
            $this.trigger('formSubmitComplete');
            $this.trigger('ajaxFormComplete');
        }
    });
};

let spinnerSM = '<div class="spinner-border spinner-border-sm" role="status"><span class="visually-hidden">Loading...</span></div>';

/**
 * Initialize Show error to each input
 * @param {Array} errors
 */

let showInputErrors = function (errors) {
    $.each(errors['errors'], function (index, value) {
        $('#' + index + '-error').remove();
        if ($('#' + index).parent().hasClass('input-group')) {
            $('#' + index).addClass('is-invalid')
                .parent().after('<label id="' + index + '-error" class="is-invalid text-danger" for="' + index + '">' + value + '</label>')
        } else if ($('#' + index).parent().hasClass('form-floating')) {
            $('#' + index).addClass('is-invalid')
                .parent().after('<label id="' + index + '-error" class="is-invalid text-danger" for="' + index + '">' + value + '</label>')
        } else {
            $('#' + index).addClass('is-invalid')
                .after('<label id="' + index + '-error" class="is-invalid text-danger" for="' + index + '">' + value + '</label>')
        }
    });
}

$('.ajaxform').each(function () {
    $(this).initFormSubmit();
});

let initRequiredLabel = function () {
    $('input[required], select[required], textarea[required]').each(function () {
        $(this).siblings('label:first').addClass('required')
    })

    // Remove required label
    $('input:not([required]), select:not([required]), textarea:not([required])').each(function () {
        $(this).siblings('label:first').removeClass('required')
    })
}

$(document).ready(function () {
    initRequiredLabel();

    let hasMessage = localStorage.getItem('hasMessage') || false;
    let message = localStorage.getItem('message') || null;
    let type = localStorage.getItem('messageType') || null;

    if (hasMessage && message !== null && type !== null) {
        flash(type, message);
        localStorage.removeItem('hasMessage');
        localStorage.removeItem('message');
        localStorage.removeItem('messageType');
    }
})
