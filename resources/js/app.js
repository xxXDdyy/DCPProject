import './bootstrap';

const $ = window.jQuery;

if ($) {
    $(function () {
        let activeNavigationRequest = null;
        let navigationToken = 0;
        let pendingSuccessMessage = '';
        const syncStorageKey = 'admin_ajax_data_changed';
        const documentSuccessKey = 'ajax_document_success';

        function setupAjaxHeaders() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Accept': 'application/json',
                },
            });
        }

        setupAjaxHeaders();

        function fieldSelector(name) {
            return '[name="' + String(name).replace(/"/g, '\\"') + '"]';
        }

        function collectData($scope) {
            const hasFileInput = $scope.find('input[type="file"][name]').length > 0;
            const data = hasFileInput ? new FormData() : {};

            $scope.find('input[name], select[name], textarea[name]').each(function () {
                const $field = $(this);
                const type = ($field.attr('type') || '').toLowerCase();
                const name = $field.attr('name');

                if (type === 'file') {
                    if (hasFileInput && this.files && this.files.length) {
                        data.append(name, this.files[0]);
                    }
                    return;
                }

                if (type === 'radio') {
                    if ($field.is(':checked')) {
                        if (hasFileInput) {
                            data.append(name, $field.val());
                        } else {
                            data[name] = $field.val();
                        }
                    }
                    return;
                }

                if (type === 'checkbox') {
                    const value = $field.is(':checked') ? ($field.val() || 1) : 0;
                    if (hasFileInput) {
                        data.append(name, value);
                    } else {
                        data[name] = value;
                    }
                    return;
                }

                if (hasFileInput) {
                    data.append(name, $field.val());
                } else {
                    data[name] = $field.val();
                }
            });

            return data;
        }

        function clearErrors($scope) {
            $('.js-ajax-alert, .js-ajax-error').remove();
            $('.is-invalid').removeClass('is-invalid');
        }

        function showErrors($scope, errors, fallbackMessage) {
            const messages = [];

            $.each(errors || {}, function (field, fieldMessages) {
                const message = $.isArray(fieldMessages) ? fieldMessages[0] : fieldMessages;
                messages.push(message);

                const $field = $scope.find(fieldSelector(field)).first();
                if ($field.length) {
                    $field.addClass('is-invalid');
                    $('<div class="invalid-feedback js-ajax-error d-block"></div>')
                        .text(message)
                        .insertAfter($field);
                }
            });

            if (!messages.length && fallbackMessage) {
                messages.push(fallbackMessage);
            }

            if (messages.length) {
                const $alert = $('<div class="alert alert-danger js-ajax-alert rounded-3"></div>');
                $alert.append('<strong>Please fix the following errors:</strong>');

                const $list = $('<ul class="mb-0 mt-2 ps-3"></ul>');
                $.each(messages, function (_, message) {
                    $list.append($('<li></li>').text(message));
                });

                $alert.append($list);
                $scope.prepend($alert);
            }
        }

        function showNotice($anchor, $scope, message, errors) {
            const $notice = $('<div class="notice notice-error js-ajax-error"></div>');

            if (errors) {
                $.each(errors, function (field, fieldMessages) {
                    const text = $.isArray(fieldMessages) ? fieldMessages[0] : fieldMessages;
                    $notice.append($('<div></div>').text(text));

                    const $field = $scope.find(fieldSelector(field)).first();
                    if ($field.length) {
                        $field.addClass('is-invalid');
                    }
                });
            } else {
                $notice.text(message || 'Something went wrong. Please try again.');
            }

            $anchor.after($notice);
        }

        function showSuccessMessage(message) {
            if (!message) {
                return;
            }

            $('.js-ajax-success').remove();

            const $alert = $('<div class="alert alert-success js-ajax-success rounded-3 mb-4"></div>')
                .text(message);

            const $pageShell = $('.page-shell').first();
            if ($pageShell.length) {
                $pageShell.prepend($alert);
                return;
            }

            const $formSubtitle = $('.form-subtitle, .subtitle').first();
            if ($formSubtitle.length) {
                $('<div class="notice notice-info js-ajax-success"></div>')
                    .text(message)
                    .insertAfter($formSubtitle);
            }
        }

        try {
            const storedMessage = sessionStorage.getItem(documentSuccessKey);
            if (storedMessage) {
                sessionStorage.removeItem(documentSuccessKey);
                showSuccessMessage(storedMessage);
            }
        } catch (error) {
            // Session storage can be blocked by browser settings.
        }

        function parseUrl(url) {
            try {
                return new URL(url, window.location.href);
            } catch (error) {
                return null;
            }
        }

        function canAjaxNavigate(url) {
            const parsedUrl = parseUrl(url);

            if (!parsedUrl || parsedUrl.origin !== window.location.origin) {
                return false;
            }

            return parsedUrl.pathname === '/admin' || parsedUrl.pathname.indexOf('/admin/') === 0;
        }

        function canAjaxReplaceDocument(url) {
            const parsedUrl = parseUrl(url);

            if (!parsedUrl || parsedUrl.origin !== window.location.origin) {
                return false;
            }

            return parsedUrl.pathname === '/login' ||
                parsedUrl.pathname === '/password/change-first-login' ||
                parsedUrl.pathname === '/student-portal' ||
                parsedUrl.pathname === '/teacher-portal';
        }

        function adminResourceFromUrl(url) {
            const parsedUrl = parseUrl(url);

            if (!parsedUrl || parsedUrl.origin !== window.location.origin) {
                return '';
            }

            const matches = parsedUrl.pathname.match(/^\/admin\/(students|teachers|degrees)(?:\/|$)/);

            return matches ? matches[1] : '';
        }

        function currentAdminIndexResource() {
            const path = window.location.pathname.replace(/\/$/, '');

            if (path === '/admin/students') {
                return 'students';
            }

            if (path === '/admin/teachers') {
                return 'teachers';
            }

            if (path === '/admin/degrees') {
                return 'degrees';
            }

            return '';
        }

        function refreshCurrentAdminIndex() {
            if (!currentAdminIndexResource()) {
                return;
            }

            if ($('.js-ajax-success').length) {
                return;
            }

            loadAdminPage(window.location.href, false, { preserveScroll: true });
        }

        function notifyAdminDataChanged(url) {
            const resource = adminResourceFromUrl(url);

            if (!resource) {
                return;
            }

            try {
                localStorage.setItem(syncStorageKey, JSON.stringify({
                    resource: resource,
                    changedAt: Date.now(),
                }));
            } catch (error) {
                // Local storage can be disabled; polling below still catches the change.
            }
        }

        function replaceFullDocument(html, targetUrl) {
            const parsedDocument = new DOMParser().parseFromString(html, 'text/html');
            const newHead = parsedDocument.querySelector('head');
            const newBody = parsedDocument.querySelector('body');
            const newTitle = parsedDocument.querySelector('title');

            if (!newHead || !newBody) {
                window.location.href = targetUrl;
                return;
            }

            if (pendingSuccessMessage) {
                try {
                    sessionStorage.setItem(documentSuccessKey, pendingSuccessMessage);
                } catch (error) {
                    // The next page still loads even when session storage is unavailable.
                }
            }

            history.pushState({ ajaxPage: true }, '', targetUrl);

            if (newTitle) {
                document.title = newTitle.textContent;
            }

            document.head.innerHTML = newHead.innerHTML;
            while (document.body.attributes.length) {
                document.body.removeAttribute(document.body.attributes[0].name);
            }

            $.each(newBody.attributes, function () {
                document.body.setAttribute(this.name, this.value);
            });

            document.body.innerHTML = newBody.innerHTML;
            setupAjaxHeaders();

            try {
                const storedMessage = sessionStorage.getItem(documentSuccessKey);
                if (storedMessage) {
                    sessionStorage.removeItem(documentSuccessKey);
                    showSuccessMessage(storedMessage);
                }
            } catch (error) {
                // Session storage can be blocked by browser settings.
            }

            pendingSuccessMessage = '';
            window.scrollTo(0, 0);
        }

        function loadFullPage(url) {
            const parsedUrl = parseUrl(url);

            if (!parsedUrl || !canAjaxReplaceDocument(parsedUrl.href)) {
                window.location.href = url;
                return;
            }

            $.ajax({
                url: parsedUrl.href,
                method: 'GET',
                dataType: 'html',
                headers: {
                    'Accept': 'text/html, */*; q=0.01',
                },
                success: function (html) {
                    replaceFullDocument(html, parsedUrl.href);
                },
                error: function () {
                    window.location.href = parsedUrl.href;
                },
            });
        }

        function replacePage(html, targetUrl, shouldPushState, options = {}) {
            const previousScrollX = window.scrollX;
            const previousScrollY = window.scrollY;
            const parsedDocument = new DOMParser().parseFromString(html, 'text/html');
            const newTitle = parsedDocument.querySelector('title');
            const newNavbar = parsedDocument.querySelector('nav.custom-navbar');
            const newMain = parsedDocument.querySelector('main.content-wrapper');
            const newFooter = parsedDocument.querySelector('footer.site-footer');

            if (!newMain) {
                window.location.href = targetUrl;
                return;
            }

            if (newTitle) {
                document.title = newTitle.textContent;
            }

            if (newNavbar && $('nav.custom-navbar').length) {
                $('nav.custom-navbar').replaceWith(newNavbar);
            }

            $('main.content-wrapper').replaceWith(newMain);

            if (newFooter && $('footer.site-footer').length) {
                $('footer.site-footer').replaceWith(newFooter);
            }

            if (shouldPushState && targetUrl !== window.location.href) {
                history.pushState({ ajaxPage: true }, '', targetUrl);
            }

            showSuccessMessage(pendingSuccessMessage);
            pendingSuccessMessage = '';

            if (options.preserveScroll) {
                window.scrollTo(previousScrollX, previousScrollY);
            } else {
                window.scrollTo(0, 0);
            }
        }

        function loadAdminPage(url, shouldPushState = true, options = {}) {
            const parsedUrl = parseUrl(url);

            if (!parsedUrl || !canAjaxNavigate(parsedUrl.href)) {
                window.location.href = url;
                return;
            }

            const currentToken = ++navigationToken;

            if (activeNavigationRequest) {
                activeNavigationRequest.abort();
            }

            activeNavigationRequest = $.ajax({
                url: parsedUrl.href,
                method: 'GET',
                dataType: 'html',
                headers: {
                    'Accept': 'text/html, */*; q=0.01',
                },
                success: function (html) {
                    if (currentToken !== navigationToken) {
                        return;
                    }

                    replacePage(html, parsedUrl.href, shouldPushState, options);
                },
                error: function (_, status) {
                    if (status !== 'abort') {
                        window.location.href = parsedUrl.href;
                    }
                },
                complete: function () {
                    if (currentToken === navigationToken) {
                        activeNavigationRequest = null;
                    }
                },
            });
        }

        function redirectAfter(response, fallbackUrl) {
            const targetUrl = response.redirect || fallbackUrl || window.location.href;
            pendingSuccessMessage = response.message || '';
            notifyAdminDataChanged(targetUrl);

            if (canAjaxNavigate(targetUrl)) {
                loadAdminPage(targetUrl);
                return;
            }

            if (canAjaxReplaceDocument(targetUrl)) {
                loadFullPage(targetUrl);
                return;
            }

            window.location.href = targetUrl;
        }

        function submitAjax($button, data) {
            let $scope = $button.closest('.js-ajax-fields');
            if (!$scope.length) {
                $scope = $button.closest('.page-shell');
            }

            const url = $button.data('url');
            const method = String($button.data('method') || 'POST').toUpperCase();
            const redirectUrl = $button.data('redirect');
            const defaultText = $button.text();
            const isFormData = data instanceof FormData;
            let ajaxMethod = method;

            if (isFormData && !['GET', 'POST'].includes(method)) {
                data.append('_method', method);
                ajaxMethod = 'POST';
            }

            clearErrors($scope);
            $button.prop('disabled', true).text('Saving...');

            $.ajax({
                url: url,
                method: ajaxMethod,
                data: data,
                processData: !isFormData,
                contentType: isFormData ? false : 'application/x-www-form-urlencoded; charset=UTF-8',
                success: function (response) {
                    redirectAfter(response, redirectUrl);
                },
                error: function (xhr) {
                    if (xhr.responseJSON) {
                        showErrors($scope, xhr.responseJSON.errors, xhr.responseJSON.message);
                    } else {
                        showErrors($scope, {}, 'Something went wrong. Please try again.');
                    }
                },
                complete: function () {
                    $button.prop('disabled', false).text(defaultText);
                },
            });
        }

        function submitLogin() {
            const $fields = $('.js-auth-fields');
            const $button = $('.js-login-submit');
            const defaultText = $button.text();

            clearErrors($fields);
            $button.prop('disabled', true).text('Logging in...');

            $.ajax({
                url: $fields.data('url'),
                method: 'POST',
                data: {
                    username: $('#username').val(),
                    password: $('#password').val(),
                },
                success: function (response) {
                    loadFullPage(response.redirect);
                },
                error: function (xhr) {
                    if (xhr.responseJSON) {
                        showNotice($('.form-subtitle'), $fields, xhr.responseJSON.message, xhr.responseJSON.errors);
                    } else {
                        showNotice($('.form-subtitle'), $fields, 'Something went wrong. Please try again.');
                    }
                },
                complete: function () {
                    $button.prop('disabled', false).text(defaultText);
                },
            });
        }

        function submitPasswordChange() {
            const $fields = $('.js-password-fields');
            const $button = $('.js-password-submit');
            const defaultText = $button.text();

            clearErrors($fields);
            $button.prop('disabled', true).text('Changing...');

            $.ajax({
                url: $fields.data('url'),
                method: 'POST',
                data: {
                    old_password: $('#old_password').val(),
                    new_password: $('#new_password').val(),
                    new_password_confirmation: $('#new_password_confirmation').val(),
                },
                success: function (response) {
                    loadFullPage(response.redirect);
                },
                error: function (xhr) {
                    if (xhr.responseJSON) {
                        showNotice($('.subtitle'), $fields, xhr.responseJSON.message, xhr.responseJSON.errors);
                    } else {
                        showNotice($('.subtitle'), $fields, 'Something went wrong. Please try again.');
                    }
                },
                complete: function () {
                    $button.prop('disabled', false).text(defaultText);
                },
            });
        }

        $(document).on('click', 'a', function (event) {
            const href = this.getAttribute('href');

            if (
                event.defaultPrevented ||
                event.which !== 1 ||
                event.metaKey ||
                event.ctrlKey ||
                event.shiftKey ||
                event.altKey ||
                !href ||
                href.indexOf('#') === 0 ||
                this.target ||
                this.hasAttribute('download') ||
                $(this).data('ajax') === false ||
                !canAjaxNavigate(href)
            ) {
                return;
            }

            event.preventDefault();
            loadAdminPage(href);
        });

        window.addEventListener('popstate', function () {
            if (canAjaxNavigate(window.location.href)) {
                loadAdminPage(window.location.href, false);
            } else {
                window.location.reload();
            }
        });

        window.addEventListener('storage', function (event) {
            if (event.key !== syncStorageKey || !event.newValue) {
                return;
            }

            try {
                const change = JSON.parse(event.newValue);
                if (change.resource === currentAdminIndexResource()) {
                    refreshCurrentAdminIndex();
                }
            } catch (error) {
                refreshCurrentAdminIndex();
            }
        });

        setInterval(function () {
            if (document.visibilityState === 'visible') {
                refreshCurrentAdminIndex();
            }
        }, 5000);

        $(document).on('click', '.js-ajax-save', function () {
            const $button = $(this);
            const $scope = $button.closest('.js-ajax-fields');

            submitAjax($button, collectData($scope));
        });

        $(document).on('click', '.js-ajax-delete', function () {
            const $button = $(this);
            const confirmMessage = $button.data('confirm') || 'Are you sure you want to delete this record?';

            if (!confirm(confirmMessage)) {
                return;
            }

            submitAjax($button, {});
        });

        $(document).on('click', '.js-ajax-action', function () {
            submitAjax($(this), {});
        });

        $(document).on('click', '.js-login-submit', submitLogin);
        $(document).on('keydown', '.js-auth-fields input', function (event) {
            if (event.key === 'Enter') {
                submitLogin();
            }
        });

        $(document).on('click', '.js-password-submit', submitPasswordChange);
        $(document).on('keydown', '.js-password-fields input', function (event) {
            if (event.key === 'Enter') {
                submitPasswordChange();
            }
        });
    });
}
