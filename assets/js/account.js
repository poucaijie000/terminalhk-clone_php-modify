/**
 * Group: [Group Number]
 * Members: [Member Name] ([Student ID]), [Member Name] ([Student ID])
 * Date: 2026-11-22
 * Assignment: Final Project Part II - TerminalHK
 *
 * Client-side validation for login, registration and password-reset forms.
 * The server repeats every rule in PHP, so this file only improves feedback.
 */

(function () {
    'use strict';

    /**
     * Check the password policy and attach a custom validity message.
     *
     * @param {HTMLInputElement} input Password field.
     * @returns {boolean} True when the password passes every rule.
     */
    function validatePassword(input) {
        var value = input.value;
        var message = '';

        if (value.length < 8) {
            message = '密碼至少需要 8 個字元。';
        } else if (!/[A-Z]/.test(value)) {
            message = '密碼必須包含至少一個大寫英文字母。';
        } else if (!/[a-z]/.test(value)) {
            message = '密碼必須包含至少一個小寫英文字母。';
        } else if (!/[0-9]/.test(value)) {
            message = '密碼必須包含至少一個數字。';
        } else if (/\s/.test(value)) {
            message = '密碼不可以包含空白字元。';
        }

        input.setCustomValidity(message);
        return message === '';
    }

    /**
     * Check that two password fields contain the same value.
     *
     * @param {HTMLInputElement} input Confirmation field.
     * @returns {boolean} True when both fields match.
     */
    function validatePasswordMatch(input) {
        var targetId = input.getAttribute('data-match-password');
        var target = targetId ? document.getElementById(targetId) : null;
        var message = target && input.value !== target.value ? '兩次輸入的密碼不一致。' : '';

        input.setCustomValidity(message);
        return message === '';
    }

    /**
     * Restrict an email field to the @gmail.com domain.
     *
     * @param {HTMLInputElement} input Email field.
     * @returns {boolean} True when the address ends with @gmail.com.
     */
    function validateGmail(input) {
        var value = input.value.trim().toLowerCase();
        var message = '';

        if (value !== '' && !value.endsWith('@gmail.com')) {
            message = '此系統只接受 @gmail.com 地址。';
        }

        input.setCustomValidity(message);
        return message === '';
    }

    /**
     * Wire validation to every form marked with .needs-validation.
     */
    function initForms() {
        var forms = document.querySelectorAll('.needs-validation');

        Array.prototype.forEach.call(forms, function (form) {
            var password = form.querySelector('[data-password-policy]');
            var confirm = form.querySelector('[data-match-password]');
            var gmail = form.querySelector('[data-gmail-only]');

            if (password) {
                password.addEventListener('input', function () {
                    validatePassword(password);
                });
            }

            if (confirm) {
                confirm.addEventListener('input', function () {
                    validatePasswordMatch(confirm);
                });
            }

            if (gmail) {
                gmail.addEventListener('input', function () {
                    validateGmail(gmail);
                });
            }

            form.addEventListener('submit', function (event) {
                var valid = true;

                if (password) {
                    valid = validatePassword(password) && valid;
                }

                if (confirm) {
                    valid = validatePasswordMatch(confirm) && valid;
                }

                if (gmail) {
                    valid = validateGmail(gmail) && valid;
                }

                if (!form.checkValidity()) {
                    valid = false;
                }

                if (!valid) {
                    event.preventDefault();
                    event.stopPropagation();
                }

                form.classList.add('was-validated');
            }, false);
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initForms);
    } else {
        initForms();
    }
})();
