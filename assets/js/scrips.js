$(function () {
    var BASE = (typeof BASE_URL !== 'undefined') ? BASE_URL : '';
    function getURL(path) {
        return BASE + path;
    }

    function hashPassword(pw) {
        var buf = new TextEncoder().encode(pw);
        return crypto.subtle.digest('SHA-256', buf).then(function (digest) {
            var bytes = new Uint8Array(digest);
            var hex = '';
            for (var i = 0; i < bytes.length; i++) {
                hex += bytes[i].toString(16).padStart(2, '0');
            }
            return hex;
        });
    }

    // ===== MOBILE MENU TOGGLE (logo icon) =====
    var $menuToggle = $('#menuToggle');
    var $homeNavLinks = $('#homeNavLinks');
    var $sidebarClose = $('#sidebarClose');
    var $sidebarBackdrop = $('#sidebarBackdrop');

    function closeMenu() {
        if ($homeNavLinks.length) $homeNavLinks.removeClass('menu-open');
        if ($menuToggle.length) $menuToggle.attr('aria-expanded', 'false');
        if ($sidebarBackdrop.length) $sidebarBackdrop.removeClass('show');
    }

    if ($menuToggle.length && $homeNavLinks.length) {
        $menuToggle.on('click', function (e) {
            e.preventDefault();
            $homeNavLinks.toggleClass('menu-open');
            $menuToggle.attr('aria-expanded', $homeNavLinks.hasClass('menu-open') ? 'true' : 'false');
            if ($sidebarBackdrop.length) $sidebarBackdrop.toggleClass('show', $homeNavLinks.hasClass('menu-open'));
        });

        $homeNavLinks.find('.nav-link').on('click', closeMenu);

        if ($sidebarClose.length) $sidebarClose.on('click', closeMenu);
        if ($sidebarBackdrop.length) $sidebarBackdrop.on('click', closeMenu);
    }

    // ===== USER ACCOUNT MENU (avatar dropdown) =====
    var $userAvatarBtn = $('#userAvatarBtn');
    var $userDropdown = $('#userDropdown');
    if ($userAvatarBtn.length && $userDropdown.length) {
        $userAvatarBtn.on('click', function (e) {
            e.stopPropagation();
            $userDropdown.toggleClass('show');
        });
        $(document).on('click', function (e) {
            if (!$(e.target).closest('.user-menu').length) {
                $userDropdown.removeClass('show');
            }
        });
    }

    // ===== USER PROFILE MODAL (fetched from server) =====
    function initEditProfileModal() {
        var $userModal = $('#editProfileModal');
        if (!$userModal.length) return;

        $('.edit-profile-link').on('click', function (e) {
            e.preventDefault();
            var $dd = $('#userDropdown');
            if ($dd.length) $dd.removeClass('show');
            $userModal.addClass('show');
        });
        $userModal.on('click', '[data-close-modal]', function () {
            $userModal.removeClass('show');
        });
    }

    if ($('#userAvatarBtn').length) {
        $.get(getURL('user/edit_profile_modal'), function (html) {
            if (html) {
                $(document.body).append(html);
                initEditProfileModal();
                if ($('#editProfileForm').length) initEditProfileForm();
            }
        });
    }

    // ===== PASSWORD TOGGLE (works for login + register + profile) =====
    $(document).on('click', '.toggle-password', function () {
        var $btn = $(this);
        var $input = $('#' + ($btn.attr('data-target') || 'password'));
        if (!$input.length) return;

        var isPassword = $input.attr('type') === 'password';
        $input.attr('type', isPassword ? 'text' : 'password');

        $btn.find('.eye-open').css('display', isPassword ? 'none' : 'block');
        $btn.find('.eye-closed').css('display', isPassword ? 'block' : 'none');
    });

    function isValidEmail(email) {
        var re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }

    // Show error: add red border + error message text
    function showError(input, message) {
        var $el = $(input);
        $el.addClass('input-error');
        var id = $el.attr('id') + 'Error';
        var $err = $('#' + id);
        if (!$err.length) {
            $err = $('<span>', { id: id, 'class': 'error-message' });
            var $group = $el.closest('.input-wrapper');
            var $grid = $el.closest('.role-prices-dates, .capacity-grid, .form-grid-2');
            if ($group.length) {
                $group.after($err);
            } else if ($grid.length) {
                $err.css('grid-column', '1 / -1');
                $grid.append($err);
            } else {
                $el.parent().after($err);
            }
        }
        $err.text((message || '').trim());
    }

    // Clear error from a field
    function clearError(input) {
        var $el = $(input);
        $el.removeClass('input-error');
        var $err = $('#' + $el.attr('id') + 'Error');
        if ($err.length) $err.text('');
    }

    // ===== LOGIN FORM =====
    var $loginForm = $('#loginForm');
    if ($loginForm.length) {
        var $emailInput = $('#email');
        var $passwordInput = $('#password');
        var $btnLogin = $('#btnLogin');

        $emailInput.on('input', function () {
            clearError($emailInput);
        });

        $passwordInput.on('input', function () {
            clearError($passwordInput);
        });

        $loginForm.on('submit', async function (e) {
            e.preventDefault();
            var valid = true;

            if (!$emailInput.val().trim()) {
                showError($emailInput, 'Email is required.');
                valid = false;
            } else if (!isValidEmail($emailInput.val().trim())) {
                showError($emailInput, 'Please enter a valid email.');
                valid = false;
            }

            if (!$passwordInput.val()) {
                showError($passwordInput, 'Password is required.');
                valid = false;
            } else if ($passwordInput.val().length < 6) {
                showError($passwordInput, 'Password must be at least 6 characters.');
                valid = false;
            }

            if (!valid) return;

            // Show loading state
            $btnLogin.prop('disabled', true);
            $btnLogin.find('.btn-text').css('display', 'none');
            $btnLogin.find('.btn-loader').css('display', 'inline-flex');

            // Pull all inputs and hash the password, then send to the controller
            var data = {};
            $loginForm.find('input').each(function () {
                var $i = $(this);
                data[$i.attr('name') || $i.attr('id')] = $i.val();
            });
            data.password = await hashPassword(data.password);
            console.log('do_login payload:', data);

            $.ajax({
                url: getURL('auth/do_login'),
                method: 'POST',
                data: data,
                dataType: 'json',
                success: function (res) {
                    if (res.success) {
                        window.location.href = getURL('home');
                    } else {
                        $btnLogin.prop('disabled', false);
                        $btnLogin.find('.btn-text').css('display', 'inline');
                        $btnLogin.find('.btn-loader').css('display', 'none');
                        showError($emailInput, 'Invalid email or password.');
                    }
                }
            });
        });
    }

    // ===== REGISTER FORM =====
    var $registerForm = $('#registerForm');
    if ($registerForm.length) {
        var $fields = {
            firstName: $('#firstName'),
            lastName: $('#lastName'),
            email: $('#email'),
            confirmPassword: $('#confirmPassword')
        };
        var $btnRegister = $('#btnRegister');

        $btnRegister.on('click', function () {
            var $form = $registerForm;
            var data = {};
            $form.find('input').each(function () {
                var $i = $(this);
                data[$i.attr('id') || $i.attr('name') || 'input'] = $i.val();
            });
            console.log(data);
        });

        $.each($fields, function (key, $field) {
            $field.on('input', function () {
                clearError($field);
            });
        });

        $registerForm.on('submit', async function (e) {
            e.preventDefault();
            var valid = true;

            if (!$fields.firstName.val().trim()) {
                showError($fields.firstName, 'First name is required.');
                valid = false;
            }

            if (!$fields.lastName.val().trim()) {
                showError($fields.lastName, 'Last name is required.');
                valid = false;
            }

            if (!$fields.email.val().trim()) {
                showError($fields.email, 'Email is required.');
                valid = false;
            } else if (!isValidEmail($fields.email.val().trim())) {
                showError($fields.email, 'Please enter a valid email.');
                valid = false;
            }

            if (!$fields.confirmPassword.val()) {
                showError($fields.confirmPassword, 'Password is required.');
                valid = false;
            } else if ($fields.confirmPassword.val().length < 6) {
                showError($fields.confirmPassword, 'Password must be at least 6 characters.');
                valid = false;
            }

            if (!valid) return;

            // Show loading state
            $btnRegister.prop('disabled', true);
            $btnRegister.find('.btn-text').css('display', 'none');
            $btnRegister.find('.btn-loader').css('display', 'inline-flex');

            // Hash the password and submit the form normally (server redirects)
            $('#password').val(await hashPassword($('#confirmPassword').val()));
            $registerForm[0].submit();
        });
    }

    // ===== EDIT PROFILE =====
    function initEditProfileForm() {
        var $editProfileForm = $('#editProfileForm');
        if (!$editProfileForm.length) return;
        var epRole = parseInt($editProfileForm.attr('data-role') || '1', 10);
        if (epRole < 1 || epRole > 3) epRole = 1;
        var epInstitution = $editProfileForm.attr('data-institution') || '';

        var roleOptions = [
            { value: 1, label: 'Guest', icon: '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>' },
            { value: 2, label: 'Student', icon: '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"></path><path d="M6 12v5c3 3 9 3 12 0v-5"></path></svg>' },
            { value: 3, label: 'Member', icon: '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 3h12l4 6-10 13L2 9z"></path><path d="M11 3 8 9l4 13 4-13-3-6"></path><path d="M2 9h20"></path></svg>' }
        ];
        var cameraIcon = '<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path><circle cx="12" cy="13" r="4"></circle></svg>';

        var epHtml = '<div class="form-group"><label>Account Role</label><div class="role-radio-group">';
        $.each(roleOptions, function (i, r) {
            epHtml += '<label class="role-radio">';
            epHtml += '<input type="radio" name="r_id" value="' + r.value + '"' + (epRole === r.value ? ' checked' : '') + '>';
            epHtml += '<span class="role-radio-card">' + r.icon + r.label + '</span>';
            epHtml += '</label>';
        });
        epHtml += '</div></div>';

        epHtml += '<div class="form-group" id="institutionFormGroup"' + (epRole === 1 ? ' style="display:none;"' : '') + '>';
        epHtml += '<label for="institution">Institution</label>';
        epHtml += '<label class="photo-upload" id="institutionUpload">';
        epHtml += '<input type="file" id="institution" name="institution" accept="image/*" hidden>';
        if (epInstitution) {
            epHtml += '<img id="institutionPreview" class="photo-preview" src="' + getURL(epInstitution) + '" style="display:block;">';
            epHtml += '<span id="institutionEmpty" style="display:none;">' + cameraIcon + 'Upload institution picture</span>';
        } else {
            epHtml += '<span id="institutionEmpty">' + cameraIcon + 'Upload institution picture</span>';
        }
        epHtml += '</label>';
        epHtml += '<span id="institutionError" class="error-message"></span>';
        epHtml += '</div>';

        $('#editRolePhotoFields').html(epHtml);

        $('#editRolePhotoFields').on('change', 'input[name="r_id"]', function () {
            var isGuest = $(this).val() === '1';
            $('#institutionFormGroup').css('display', isGuest ? 'none' : '');
        });
        var $ep = {
            firstName: $('#firstName'),
            lastName: $('#lastName'),
            email: $('#email'),
            phone: $('#phone'),
            newPassword: $('#newPassword')
        };
        var $btnSaveProfile = $('#btnSaveProfile');

        $.each($ep, function (key, $field) {
            if ($field.length) {
                $field.on('input', function () {
                    clearError($field);
                });
            }
        });

        $editProfileForm.on('submit', function (e) {
            e.preventDefault();
            var valid = true;

            if (!$ep.firstName.val().trim()) {
                showError($ep.firstName, 'First name is required.');
                valid = false;
            }
            if (!$ep.lastName.val().trim()) {
                showError($ep.lastName, 'Last name is required.');
                valid = false;
            }
            if (!$ep.email.val().trim()) {
                showError($ep.email, 'Email is required.');
                valid = false;
            } else if (!isValidEmail($ep.email.val().trim())) {
                showError($ep.email, 'Please enter a valid email.');
                valid = false;
            }

            var $newPassword = $('#newPassword');
            if ($newPassword.length && $newPassword.val()) {
                if ($newPassword.val().length < 6) {
                    showError($newPassword, 'Password must be at least 6 characters.');
                    valid = false;
                } else {
                    $('#newPasswordError').text('');
                }
            }

            if (!valid) return;

            $btnSaveProfile.prop('disabled', true);
            $btnSaveProfile.find('.btn-text').css('display', 'none');
            $btnSaveProfile.find('.btn-loader').css('display', 'inline-flex');

            var fd = new FormData($editProfileForm[0]);

            $.ajax({
                url: getURL('user/save_profile'),
                method: 'POST',
                data: fd,
                processData: false,
                contentType: false,
                success: function (res) {
                    $btnSaveProfile.prop('disabled', false);
                    $btnSaveProfile.find('.btn-text').css('display', 'inline');
                    $btnSaveProfile.find('.btn-loader').css('display', 'none');
                    if (res === 'success') {
                        var newName = $ep.firstName.val().trim();
                        var newEmail = $ep.email.val().trim();
                        var newInitial = newName ? newName.charAt(0).toUpperCase() : '?';
                        $('.user-avatar').text(newInitial);
                        $('.user-avatar-lg').text(newInitial);
                        $('.user-name').text(newName);
                        $('.user-email').text(newEmail);
                        $('#editProfileModal').removeClass('show');
                        alert('Profile updated.');
                    }
                }
            });
        });
    }

    // ===== HOME PAGE: SEARCH + FILTER =====
    var $searchInput = $('#searchInput');
    var $eventsGrid = $('#eventsGrid');
    var $noResults = $('#noResults');

    function applyFilter() {
        if (!$eventsGrid.length) return;
        var term = ($searchInput.length ? $searchInput.val() : '').trim().toLowerCase();
        var visible = 0;

        $eventsGrid.find('.event-card').each(function () {
            var $card = $(this);
            var text = ($card.attr('data-title') + ' ' +
                        $card.attr('data-location')).toLowerCase();
            var match = text.indexOf(term) !== -1;
            $card.css('display', match ? '' : 'none');
            if (match) visible++;
        });

        if ($noResults.length) $noResults.css('display', visible ? 'none' : 'block');
        var $countEl = $('#eventCount');
        if ($countEl.length) $countEl.text(visible + ' event' + (visible === 1 ? '' : 's'));
    }

    var $btnSearch = $('#btnSearch');
    if ($btnSearch.length) $btnSearch.on('click', applyFilter);
    if ($searchInput.length) $searchInput.on('input', applyFilter);
    $(window).on('load', applyFilter);

    // ===== BOOKING / PAYMENT MODALS (fetched from server) =====
    var $bookingOverlay, $bookingClose, $confirmBooking, $ticketQty, $payOverlay, $payClose, $confirmPay;

    function initBookingModals() {
        $bookingOverlay = $('#bookingOverlay');
        $bookingClose = $('#bookingClose');
        $confirmBooking = $('#confirmBooking');
        $ticketQty = $('#ticketQty');
        $payOverlay = $('#payOverlay');
        $payClose = $('#payClose');
        $confirmPay = $('#confirmPay');

        if (!$bookingOverlay.length) return;

    if ($eventsGrid.length) {
        $eventsGrid.on('click', function (e) {
            var $btn = $(e.target).closest('.event-book-btn');
            if (!$btn.length || !$bookingOverlay.length) return;

            var eventId = $btn.attr('data-id');
            $bookingOverlay.data('eventId', eventId);
            $('#bookingTitle').text($btn.attr('data-title'));
            $('#bookingMeta').text(
                $btn.attr('data-date') + ' \u00b7 ' + $btn.attr('data-time') + ' \u00b7 ' +
                $btn.attr('data-location'));
            $('#ticketOptions').empty().html('<p class="booking-loading">Loading tickets...</p>');
            $ticketQty.val(1);
            $('#qtyError').text('');
            $bookingOverlay.css('display', 'flex');

            $.ajax({
                url: getURL('event/event_detail/' + eventId),
                method: 'GET',
                dataType: 'json',
                success: function (res) {
                    if (!res.success) {
                        $('#ticketOptions').empty().html('<p class="error-message">No tickets available.</p>');
                        $bookingOverlay.data('price', 0);
                        updateTotal(0);
                        return;
                    }
                    renderTicketOptions(res);
                },
                error: function () {
                    $('#ticketOptions').empty().html('<p class="error-message">Could not load tickets.</p>');
                }
            });
        });
    }

    function formatTicketDate(value) {
        if (!value) return '';
        var m = String(value).match(/(\d{4})-(\d{2})-(\d{2})/);
        if (m) {
            var months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            var mi = parseInt(m[2], 10) - 1;
            return parseInt(m[3], 10) + ' ' + months[mi] + ' ' + m[1];
        }
        return value;
    }

    function renderTicketOptions(res) {
        var $container = $('#ticketOptions');
        $container.empty();

        var today = new Date();
        function ymdTime(value) {
            var m = String(value || '').match(/(\d{4})-(\d{2})-(\d{2})/);
            if (!m) return null;
            return new Date(parseInt(m[1], 10), parseInt(m[2], 10) - 1, parseInt(m[3], 10)).getTime();
        }
        function ticketStatus(t) {
            var startOfToday = new Date(today.getFullYear(), today.getMonth(), today.getDate()).getTime();
            var start = ymdTime(t.ticket_start_date);
            var end = ymdTime(t.ticket_end_date);
            if (start !== null && start > startOfToday) return 'future';
            if (end !== null && end < startOfToday) return 'past';
            return 'active';
        }

        var sections = [];
        sections.push({
            title: res.event.event_name,
            tickets: res.tickets || [],
            isRequired: false
        });
        $.each(res.required_events || [], function (i, re) {
            sections.push({
                title: re.event.event_name,
                tickets: re.tickets || [],
                isRequired: true
            });
        });

        var html = '<label class="booking-ticket-label">Select your ticket</label>';

        $.each(sections, function (si, sec) {
            var secKey = 'ticket' + si;
            var anyEnabled = false;
            html += '<div class="booking-event-section">';
            html += '<div class="booking-event-title">';
            if (sec.isRequired) html += '<span class="booking-req-badge">Requires</span>';
            html += $('<span>').text(sec.title).html() + '</div>';

            if (!sec.tickets.length) {
                html += '<p class="booking-loading">No tickets for your role.</p></div>';
                return;
            }

            var grouped = {};
            $.each(sec.tickets, function (i, t) {
                var key = t.role_name || 'all';
                if (!grouped[key]) grouped[key] = [];
                grouped[key].push(t);
            });

            $.each(grouped, function (role, list) {
                var roleLabel = role.charAt(0).toUpperCase() + role.slice(1);
                html += '<div class="booking-ticket-role">' + roleLabel + '</div>';
                html += '<div class="radio-group booking-ticket-group">';
                $.each(list, function (i, t) {
                    var price = parseFloat(t.ticket_price) || 0;
                    var status = ticketStatus(t);
                    var unavailable = status !== 'active';
                    if (!unavailable) anyEnabled = true;
                    var typeLabel = t.ticket_type.charAt(0).toUpperCase() + t.ticket_type.slice(1);
                    var validText;
                    if (status === 'future') {
                        validText = 'Available from ' + (formatTicketDate(t.ticket_start_date) || 'start');
                    } else {
                        validText = 'Valid until ' + (formatTicketDate(t.ticket_end_date) || 'Event day') + (status === 'past' ? ' \u00b7 Expired' : '');
                    }
                    html += '<label class="radio-option book-ticket-option' + (unavailable ? ' is-disabled' : '') + '">' +
                        '<input type="radio" name="' + secKey + '" value="' + t.ticket_id + '" data-price="' + price + '"' + (unavailable ? ' disabled' : '') + '>' +
                        '<span class="book-ticket-text">' +
                        '<span class="book-ticket-name">' + typeLabel + ' \u2014 $' + price.toFixed(2) + '</span>' +
                        '<span class="book-ticket-dates">' + validText + '</span>' +
                        '</span></label>';
                });
                html += '</div>';
            });

            if (!anyEnabled) {
                html += '<p class="booking-loading">No tickets currently available for this event.</p>';
            }
            html += '</div>';
        });

        $container.html(html);

        $.each(sections, function (si) {
            var $first = $container.find('input[name="ticket' + si + '"]:not(:disabled)').first();
            if ($first.length) {
                $first.prop('checked', true);
                $first.closest('.radio-option').addClass('selected');
            }
        });

        $container.on('change', 'input[type="radio"]', function () {
            var $el = $(this);
            if ($el.is(':disabled')) return;
            $container.find('input[name="' + $el.attr('name') + '"]').closest('.radio-option').removeClass('selected');
            $el.closest('.radio-option').addClass('selected');
            recomputeTotal();
        });

        function recomputeTotal() {
            var total = 0;
            $.each(sections, function (si) {
                var $sel = $container.find('input[name="ticket' + si + '"]:checked');
                if ($sel.length) total += parseFloat($sel.attr('data-price')) || 0;
            });
            $bookingOverlay.data('price', total);
            updateTotal(total);
        }

        recomputeTotal();
    }

    function updateTotal(price) {
        var $el = $('#bookingTotal');
        var qty = parseInt($ticketQty.val(), 10) || 1;
        if ($el.length) $el.text('$' + (price * qty).toFixed(2));
    }

    if ($ticketQty.length) $ticketQty.on('input', function () {
        var price = parseFloat($bookingOverlay.data('price')) || 0;
        $('#qtyError').text('');
        updateTotal(price);
    });

    if ($bookingClose.length) $bookingClose.on('click', function () {
        $bookingOverlay.css('display', 'none');
    });

    if ($bookingOverlay.length) {
        $bookingOverlay.on('click', function (e) {
            if (e.target === $bookingOverlay[0]) $bookingOverlay.css('display', 'none');
        });
    }

    if ($confirmBooking.length) {
        $confirmBooking.on('click', function () {
            var qty = parseInt($ticketQty.val(), 10);
            var $qtyError = $('#qtyError');

            if (!qty || qty < 1) {
                $qtyError.text('Please enter a valid number of tickets.');
                return;
            }
            if (qty > 10) {
                $qtyError.text('Maximum 10 tickets per booking.');
                return;
            }

            var total = parseFloat($bookingOverlay.data('price')) || 0;
            var title = $('#bookingTitle').text() || 'Event';

            $('#payMeta').text(title + ' \u00b7 Total $' + (total * qty).toFixed(2));
            $('#receipt').val('');
            $('#receipt').removeClass('input-error');
            $('#receiptError').text('');
            $('#receiptEmpty').css('display', '');
            $('#receiptPreview').css('display', 'none').attr('src', '');
            drawFakeQr($('#payQrCanvas'), title + '|' + total + '|' + qty);

            $bookingOverlay.css('display', 'none');
            $payOverlay.css('display', 'flex');
        });
    }

    if ($payClose.length) $payClose.on('click', function () {
        $payOverlay.css('display', 'none');
    });

    if ($payOverlay.length) {
        $payOverlay.on('click', function (e) {
            if (e.target === $payOverlay[0]) $payOverlay.css('display', 'none');
        });
    }

    if ($confirmPay.length) {
        $confirmPay.on('click', function () {
            var $receipt = $('#receipt');
            if (!$receipt[0].files || !$receipt[0].files.length) {
                $('#receiptUpload').addClass('input-error');
                $('#receiptError').text('Please upload the receipt picture.');
                return;
            }

            var ticketIds = [];
            $('#ticketOptions').find('input[type="radio"]:checked').each(function () {
                ticketIds.push($(this).val());
            });
            if (!ticketIds.length) {
                $('#receiptError').text('No ticket selected.');
                return;
            }

            var eventId = $bookingOverlay.data('eventId') || 0;
            var qty = parseInt($ticketQty.val(), 10) || 1;

            var fd = new FormData();
            fd.append('event_id', eventId);
            fd.append('qty', qty);
            fd.append('receipt', $receipt[0].files[0]);
            $.each(ticketIds, function (i, id) {
                fd.append('ticket_ids[]', id);
            });

            $confirmPay.prop('disabled', true);
            $('#receiptError').text('');

            $.ajax({
                url: getURL('buying/do_booking'),
                method: 'POST',
                data: fd,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function (res) {
                    $confirmPay.prop('disabled', false);
                    if (res && res.success) {
                        $payOverlay.css('display', 'none');
                        $('#receipt').val('');
                        $('#receiptEmpty').css('display', '');
                        $('#receiptPreview').css('display', 'none').attr('src', '');
                        $('#receiptUpload').removeClass('input-error');
                        alert('Payment confirmed! ' + res.count + ' ticket(s) booked.');
                    } else {
                        $('#receiptError').text((res && res.message) ? res.message : 'Booking failed.');
                    }
                },
                error: function () {
                    $confirmPay.prop('disabled', false);
                    $('#receiptError').text('Could not save the booking.');
                }
            });
        });
    }
    }

    if ($('#eventsGrid').length) {
        $.when(
            $.get(getURL('buying/booking_modal')),
            $.get(getURL('buying/payment_modal'))
        ).then(function (a, b) {
            var bookingHtml = (a && a[0]) || '';
            var payHtml = (b && b[0]) || '';
            $(document.body).append(bookingHtml + payHtml);
            if ($('#bookingOverlay').length && $('#payOverlay').length) initBookingModals();
        });
    }

    $(document).on('change', '#institution', function () {
        var file = this.files && this.files[0];
        if (!file) return;
        var reader = new FileReader();
        reader.onload = function (e) {
            $('#institutionPreview').attr('src', e.target.result).css('display', 'block');
            $('#institutionEmpty').css('display', 'none');
        };
        reader.readAsDataURL(file);
    });

    $(document).on('click', '.btn-verify', function () {
        var $btn = $(this);
        var $input = $('#' + $btn.attr('data-verify'));
        var isEmail = $input.attr('type') === 'email';
        var $err = $('#' + $input.attr('id') + 'Error');
        var check = '<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>';

        if ($btn.hasClass('verified')) {
            $btn.removeClass('verified').html(check + '<span>Verify</span>');
            $('#' + (isEmail ? 'comfirmedEmail' : 'comfirmedPhone')).val('');
            return;
        }

        var ok = isEmail
            ? /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test($input.val().trim())
            : $input.val().trim().replace(/[^0-9]/g, '').length >= 7;

        if (!ok) {
            if ($err.length) $err.text('Please enter a valid ' + (isEmail ? 'email' : 'phone number') + '.');
            return;
        }

        if ($err.length) $err.text('');
        $btn.addClass('verified').html(check + '<span>Verified</span>');
        $('#' + (isEmail ? 'comfirmedEmail' : 'comfirmedPhone')).val($input.val().trim());
    });

    $(document).on('change', '#receipt', function () {
        var file = this.files && this.files[0];
        if (!file) return;
        var reader = new FileReader();
        reader.onload = function (e) {
            $('#receiptPreview').attr('src', e.target.result).css('display', 'block');
            $('#receiptEmpty').css('display', 'none');
            $('#receiptUpload').removeClass('input-error');
            $('#receiptError').text('');
        };
        reader.readAsDataURL(file);
    });

    function drawFakeQr($canvas, seed) {
        if (!$canvas.length || !$canvas[0].getContext) return;
        var size = 30;
        var width = parseInt($canvas.attr('width'), 10) || 200;
        var cell = Math.floor(width / size);
        var offset = Math.floor((width - cell * size) / 2);
        var ctx = $canvas[0].getContext('2d');

        ctx.fillStyle = '#ffffff';
        ctx.fillRect(0, 0, width, width);
        ctx.fillStyle = '#111111';

        var s = 7;
        for (var i = 0; i < String(seed).length; i++) {
            s = (s * 31 + String(seed).charCodeAt(i)) >>> 0;
        }
        function rnd() {
            s = (s * 1103515245 + 12345) >>> 0;
            return s / 4294967296;
        }

        var inFinder = function (r, c) {
            return (r < 8 && c < 8) || (r < 8 && c >= size - 8) || (r >= size - 8 && c < 8);
        };
        var drawFinder = function (r, c) {
            var x = offset + c * cell, y = offset + r * cell;
            ctx.fillStyle = '#111111';
            ctx.fillRect(x, y, cell * 7, cell * 7);
            ctx.fillStyle = '#ffffff';
            ctx.fillRect(x + cell, y + cell, cell * 5, cell * 5);
            ctx.fillStyle = '#111111';
            ctx.fillRect(x + cell * 2, y + cell * 2, cell * 3, cell * 3);
        };

        for (var r = 0; r < size; r++) {
            for (var c = 0; c < size; c++) {
                if (!inFinder(r, c) && rnd() > 0.5) {
                    ctx.fillRect(offset + c * cell, offset + r * cell, cell - 1, cell - 1);
                }
            }
        }
        drawFinder(0, 0);
        drawFinder(0, size - 7);
        drawFinder(size - 7, 0);
    }

    // ===== ADD EVENT FORM =====
    var $addEventForm = $('#addEventForm');
    if ($addEventForm.length) {
        var $addFields = {
            eventTitle: $('#eventTitle'),
            eventLocation: $('#eventLocation'),
            eventDescription: $('#eventDescription')
        };
        var $btnAddEvent = $('#btnAddEvent');

        function checkedValue(name) {
            var $el = $('input[name="' + name + '"]:checked');
            return $el.length ? $el.val() : '';
        }

        function setVisibility(ids, visible) {
            $.each(ids, function (i, id) {
                var $el = $('#' + id);
                if ($el.length) $el.css('display', visible ? '' : 'none');
            });
        }

        function bindRadioGroup(name, handler) {
            var $radios = $('input[name="' + name + '"]');
            $radios.on('change', function () {
                $radios.closest('.radio-option').removeClass('selected');
                $radios.filter(':checked').closest('.radio-option').addClass('selected');
                handler(checkedValue(name));
            });
            $radios.closest('.radio-option').removeClass('selected');
            $radios.filter(':checked').closest('.radio-option').addClass('selected');
            handler(checkedValue(name));
        }

        // ---- JS-rendered icons (inline SVG keeps currentColor tinting) ----
        function iconMarkup(name) {
            var icons = {
                dollar: '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="input-icon"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>',
                cal: '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="input-icon"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>',
                users: '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="input-icon"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"></path></svg>'
            };
            return icons[name] || '';
        }

        // ---- render helpers ----
        function radioGroup(name, options, cls) {
            var html = '<div class="radio-group' + (cls ? ' ' + cls : '') + '">';
            $.each(options, function (i, o) {
                html += '<label class="radio-option' + (o.default ? ' selected' : '') + '">' +
                    '<input type="radio" name="' + name + '" value="' + o.value + '"' + (o.default ? ' checked' : '') + '>' +
                    '<span>' + o.label + '</span>' +
                    '</label>';
            });
            return html + '</div>';
        }

        function dateInput(id, name, label) {
            return '<div class="form-group">' +
                '<label for="' + id + '">' + label + '</label>' +
                '<div class="input-wrapper">' + iconMarkup('cal') +
                '<input type="date" id="' + id + '" name="' + name + '">' +
                '</div></div>';
        }

        function numberInput(cfg) {
            return '<div class="form-group">' +
                (cfg.label ? '<label for="' + cfg.id + '">' + cfg.label + '</label>' : '') +
                '<div class="input-wrapper">' + iconMarkup(cfg.icon || 'dollar') +
                '<input type="number" id="' + cfg.id + '" name="' + cfg.name + '"' +
                (cfg.min != null ? ' min="' + cfg.min + '"' : '') +
                (cfg.step != null ? ' step="' + cfg.step + '"' : '') +
                (cfg.placeholder ? ' placeholder="' + cfg.placeholder + '"' : '') +
                '></div></div>';
        }

        function rolePricesGrid(roleInputFn, roleDateFn) {
            var roles = ['Guest', 'Student', 'Member'];
            var html = '<div class="role-prices role-prices-dates">';
            html += '<span class="role-price-head">role</span>';
            html += '<span class="role-price-head">price</span>';
            html += '<span class="role-price-head role-price-head-date">Valid until</span>';
            $.each(roles, function (i, role) {
                html += '<span class="role-price-label">' + role + '</span>';
                html += roleInputFn(role);
                html += roleDateFn(role);
            });
            return html + '</div>';
        }

        // ---- section renderers ----
        function renderWhenSection() {
            var html = '';
            html += radioGroup('eventDateType', [
                { value: 'single', label: 'One Day', default: true },
                { value: 'period', label: 'Date Period' }
            ]);
            html += '<div id="whenFieldsBody"></div>';
            $('#whenFields').html(html);
            renderWhenBody();
        }

        function renderWhenBody() {
            var single = checkedValue('eventDateType') !== 'period';
            var html = '';
            if (single) {
                html += '<div class="form-row" id="singleDateFields">' +
                    dateInput('eventDate', 'eventDate', 'Date') +
                    '</div>';
            } else {
                html += '<div class="form-row" id="periodDateFields">' +
                    dateInput('eventStartDate', 'eventStartDate', 'Start Date') +
                    dateInput('eventEndDate', 'eventEndDate', 'End Date') +
                    '</div>';
            }
            $('#whenFieldsBody').html(html);
        }

        function renderCapacitySection() {
            var html = '<div class="capacity-grid">';
            html += radioGroup('eventCapacityType', [
                { value: 'unlimited', label: 'Unlimited', default: true },
                { value: 'limited', label: 'Limited' }
            ]);
            html += '<div id="capacityFieldsBody"></div>';
            html += '</div>';
            $('#capacityFields').html(html);
            renderCapacityBody();
        }

        function renderCapacityBody() {
            var limited = checkedValue('eventCapacityType') === 'limited';
            var html = '';
            if (limited) {
                html += '<div class="input-wrapper" id="capacityLimitedField">' + iconMarkup('users') +
                    '<input type="number" id="eventCapacity" name="eventCapacity" min="1" placeholder="Number of seats">' +
                    '</div>';
            }
            $('#capacityFieldsBody').html(html);
        }

        function renderSinglePrice() {
            return '<div class="price-tiers" id="singlePriceField">' +
                '<div class="price-tier">' +
                '<div class="price-tier-title">Ticket Price</div>' +
                numberInput({ id: 'eventPrice', name: 'eventPrice', label: 'Price ($)', placeholder: 'Price per ticket', min: 0, step: 0.01 }) +
                dateInput('eventPriceDate', 'eventPriceDate', 'Valid until') +
                '</div></div>';
        }

        function renderSingleRolePrices() {
            return '<div class="price-tiers" id="singleRolePrices">' +
                '<div class="price-tier">' +
                '<div class="price-tier-title">Ticket Price</div>' +
                rolePricesGrid(
                    function (role) {
                        var names = { Guest: 'guestPrice', Student: 'studentPrice', Member: 'memberPrice' };
                        return '<div class="input-wrapper">' + iconMarkup('dollar') +
                            '<input type="number" id="price' + role + '" name="' + names[role] + '" min="0" step="0.01" placeholder="Price">' +
                            '</div>';
                    },
                    function (role) {
                        var id = 'price' + role + 'Date';
                        return '<div class="input-wrapper">' + iconMarkup('cal') +
                            '<input type="date" id="' + id + '" name="' + id + '">' +
                            '</div>';
                    }
                ) + '</div></div>';
        }

        function renderFixedTiers() {
            var early = '<div class="price-tier">' +
                '<div class="price-tier-title">Early Ticket Price</div>' +
                numberInput({ id: 'earlyPrice', name: 'earlyPrice', label: 'Price ($)', placeholder: 'Early price', min: 0, step: 0.01 }) +
                dateInput('earlyDate', 'earlyDate', 'Valid until') +
                '</div>';
            var late = '<div class="price-tier">' +
                '<div class="price-tier-title">Late Ticket</div>' +
                numberInput({ id: 'latePrice', name: 'latePrice', label: 'Late Price ($)', placeholder: 'Late price', min: 0, step: 0.01 }) +
                dateInput('lateDate', 'lateDate', 'Valid until') +
                '</div>';
            return '<div class="price-tiers" id="fixedTiersField">' + early + late + '</div>';
        }

        function renderRoleTierRow(prefix, title) {
            return '<div class="price-tier">' +
                '<div class="price-tier-title">' + title + '</div>' +
                rolePricesGrid(
                    function (role) {
                        var id = prefix + role;
                        return '<div class="input-wrapper">' + iconMarkup('dollar') +
                            '<input type="number" id="' + id + '" name="' + id + '" min="0" step="0.01" placeholder="Price">' +
                            '</div>';
                    },
                    function (role) {
                        var id = prefix + role + 'Date';
                        return '<div class="input-wrapper">' + iconMarkup('cal') +
                            '<input type="date" id="' + id + '" name="' + id + '">' +
                            '</div>';
                    }
                ) + '</div>';
        }

        function renderRoleTiers() {
            return '<div class="price-tiers" id="roleTiersField">' +
                renderRoleTierRow('early', 'Early Ticket') +
                renderRoleTierRow('late', 'Late Ticket') +
                '</div>';
        }

        function renderPricingSection() {
            var html = '';
            html += '<div class="price-mode">';
            html += '<div class="price-mode-step"><span class="price-mode-label">Who pays?</span>';
            html += radioGroup('eventRolePricing', [
                { value: 'no', label: 'One price', default: true },
                { value: 'yes', label: 'Each price' }
            ], 'price-mode-group');
            html += '</div>';
            html += '<div class="price-mode-step"><span class="price-mode-label">Ticket Type</span>';
            html += radioGroup('eventPriceType', [
                { value: 'single', label: 'Single Ticket', default: true },
                { value: 'tiers', label: 'Early + Late' }
            ], 'price-mode-group');
            html += '</div>';
            html += '</div>';

            html += '<div id="pricingFieldsBody"></div>';
            $('#pricingFields').html(html);
            renderPricingBody();
        }

        function renderPricingBody() {
            var byRole = checkedValue('eventRolePricing') === 'yes';
            var isTiers = checkedValue('eventPriceType') === 'tiers';
            var variant;
            if (!byRole && !isTiers) variant = renderSinglePrice();
            else if (byRole && !isTiers) variant = renderSingleRolePrices();
            else if (!byRole && isTiers) variant = renderFixedTiers();
            else variant = renderRoleTiers();
            $('#pricingFieldsBody').html(variant);
        }

        // Render dynamic sections
        renderWhenSection();
        renderCapacitySection();
        renderPricingSection();

        // Clear errors on any input/select/textarea edit
        $addEventForm.on('input', 'input, select, textarea', function () {
            clearError($(this));
        });

        // Date type: single day vs period
        bindRadioGroup('eventDateType', function () {
            renderWhenBody();
        });

        // Capacity: unlimited vs limited
        bindRadioGroup('eventCapacityType', function () {
            renderCapacityBody();
        });

        // Ticket requirement: show event picker when a ticket is required
        bindRadioGroup('eventRequiresTicket', function (value) {
            setVisibility(['ticketRequirementField'], value === 'yes');
        });

        // Pricing: by role or not + 1 cost / 2 costs
        bindRadioGroup('eventRolePricing', function () {
            renderPricingBody();
        });
        bindRadioGroup('eventPriceType', function () {
            renderPricingBody();
        });

        function showAddError(key, message) {
            var $field = ($addFields[key] && $addFields[key].length) ? $addFields[key] : $('#' + key);
            if ($field.length) showError($field, message);
        }

        $addEventForm.on('submit', function (e) {
            e.preventDefault();
            var valid = true;

            if (!$addFields.eventTitle.val().trim()) {
                showAddError('eventTitle', 'Event title is required.');
                valid = false;
            }

            var isSingleDay = $('input[name="eventDateType"]:checked').val() === 'single';
            if (isSingleDay) {
                if (!$('#eventDate').val()) {
                    showAddError('eventDate', 'Please pick a date.');
                    valid = false;
                }
            } else {
                var startDate = $('#eventStartDate').val();
                var endDate = $('#eventEndDate').val();
                if (!startDate) {
                    showAddError('eventStartDate', 'Please pick a start date.');
                    valid = false;
                }
                if (!endDate) {
                    showAddError('eventEndDate', 'Please pick an end date.');
                    valid = false;
                }
                if (startDate && endDate && endDate < startDate) {
                    showAddError('eventEndDate', 'End date must be after the start date.');
                    valid = false;
                }
            }

            if (!$addFields.eventLocation.val().trim()) {
                showAddError('eventLocation', 'Location is required.');
                valid = false;
            }

            var isLimited = $('input[name="eventCapacityType"]:checked').val() === 'limited';
            if (isLimited) {
                var capVal = $('#eventCapacity').val();
                if (!capVal || parseInt(capVal, 10) < 1) {
                    showAddError('eventCapacity', 'Enter a valid capacity (min 1).');
                    valid = false;
                }
            }

            var byRole = checkedValue('eventRolePricing') === 'yes';
            function checkPrice(id, roleName) {
                var $el = $('#' + id);
                if ($el.length && ($el.val() === '' || parseFloat($el.val()) < 0)) {
                    showAddError(id, (roleName ? roleName + ' price' : 'Price') + ' is required.');
                    valid = false;
                }
            }
            function checkTierDates(prefix, dateId) {
                if (!$('#' + dateId).val()) {
                    showAddError(dateId, 'Please pick a date.');
                    valid = false;
                }
            }
            if (byRole) {
                if (checkedValue('eventPriceType') === 'tiers') {
                    ['early', 'late'].forEach(function (prefix) {
                        ['Guest', 'Student', 'Member'].forEach(function (role) {
                            checkPrice(prefix + role, role);
                            checkTierDates(prefix, prefix + role + 'Date');
                        });
                    });
                } else {
                    ['Guest', 'Student', 'Member'].forEach(function (role) {
                        checkPrice('price' + role, role);
                        checkTierDates('price', 'price' + role + 'Date');
                    });
                }
            } else {
                if (checkedValue('eventPriceType') === 'tiers') {
                    ['early', 'late'].forEach(function (prefix) {
                        checkPrice(prefix + 'Price');
                        checkTierDates(prefix, prefix + 'Date');
                    });
                } else {
                    checkPrice('eventPrice');
                    checkTierDates('eventPrice', 'eventPriceDate');
                }
            }

            if (!$addFields.eventDescription.val().trim()) {
                showAddError('eventDescription', 'Please add a description.');
                valid = false;
            }

            if (!valid) return;

            // Pull all input/select/textarea values into one object with 6 groups
            var sectionNames = ['Basics', 'When', 'Capacity', 'Pricing', 'Event Required', 'About'];
            var addEventAll = {};
            $addEventForm.find('.event-section').each(function (i, section) {
                var groupKey = sectionNames[i];
                addEventAll[groupKey] = {};
                $(section).find('input, select, textarea').each(function () {
                    var $el = $(this);
                    var key = $el.attr('name') || $el.attr('id');
                    var group = addEventAll[groupKey];
                    if (!key) return;
                    if ($el.is(':radio')) {
                        if ($el.is(':checked')) group[key] = $el.val();
                    } else if ($el.is(':checkbox')) {
                        if ($el.is(':checked')) {
                            group[key] = (Array.isArray(group[key])) ? group[key].concat($el.val()) : [$el.val()];
                        }
                    } else {
                        group[key] = $el.val();
                    }
                });
            });

            console.log('addEvent payload:', addEventAll);

            var $payload = $('#addEventPayload');
            if (!$payload.length) {
                $payload = $('<input type="hidden" name="payload" id="addEventPayload">');
                $addEventForm.append($payload);
            }
            $payload.val(JSON.stringify(addEventAll));
            $addEventForm[0].submit();
        });
    }
});
