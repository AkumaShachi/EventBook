document.addEventListener('DOMContentLoaded', function () {
    var BASE = (typeof BASE_URL !== 'undefined') ? BASE_URL : '';
    function getURL(path) {
        return BASE + path;
    }

    // ===== MOBILE MENU TOGGLE (logo icon) =====
    var menuToggle = document.getElementById('menuToggle');
    var homeNavLinks = document.getElementById('homeNavLinks');
    var sidebarClose = document.getElementById('sidebarClose');
    var sidebarBackdrop = document.getElementById('sidebarBackdrop');

    function closeMenu() {
        if (homeNavLinks) homeNavLinks.classList.remove('menu-open');
        if (menuToggle) menuToggle.setAttribute('aria-expanded', 'false');
        if (sidebarBackdrop) sidebarBackdrop.classList.remove('show');
    }

    if (menuToggle && homeNavLinks) {
        menuToggle.addEventListener('click', function (e) {
            e.preventDefault();
            var open = homeNavLinks.classList.toggle('menu-open');
            menuToggle.setAttribute('aria-expanded', open ? 'true' : 'false');
            if (sidebarBackdrop) sidebarBackdrop.classList.toggle('show', open);
        });

        homeNavLinks.querySelectorAll('.nav-link').forEach(function (link) {
            link.addEventListener('click', closeMenu);
        });

        if (sidebarClose) sidebarClose.addEventListener('click', closeMenu);
        if (sidebarBackdrop) sidebarBackdrop.addEventListener('click', closeMenu);
    }
    // ===== PASSWORD TOGGLE (works for login + register) =====
    document.querySelectorAll('.toggle-password').forEach(function (button) {
        button.addEventListener('click', function () {
            var targetId = button.getAttribute('data-target') || 'password';
            var input = document.getElementById(targetId);
            if (!input) return;

            var isPassword = input.type === 'password';
            input.type = isPassword ? 'text' : 'password';

            var eyeOpen = button.querySelector('.eye-open');
            var eyeClosed = button.querySelector('.eye-closed');
            eyeOpen.style.display = isPassword ? 'none' : 'block';
            eyeClosed.style.display = isPassword ? 'block' : 'none';
        });
    });

    function isValidEmail(email) {
        var re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }

    // Show error: add red border + error message text
    function showError(input, message) {
        input.classList.add('input-error');
        var el = document.getElementById(input.getAttribute('id') + 'Error');
        if (el) el.textContent = message.trim();
    }

    // Clear error from a field
    function clearError(input) {
        input.classList.remove('input-error');
        var el = document.getElementById(input.getAttribute('id') + 'Error');
        if (el) el.textContent = '';
    }

    // ===== LOGIN FORM =====
    var loginForm = document.getElementById('loginForm');
    if (loginForm) {
        var emailInput = document.getElementById('email');
        var passwordInput = document.getElementById('password');
        var btnLogin = document.getElementById('btnLogin');

        emailInput.addEventListener('input', function () {
            clearError(emailInput);
        });

        passwordInput.addEventListener('input', function () {
            clearError(passwordInput);
        });

        loginForm.addEventListener('submit', function (e) {
            e.preventDefault();
            var valid = true;

            if (!emailInput.value.trim()) {
                showError(emailInput, 'Email is required.');
                valid = false;
            } else if (!isValidEmail(emailInput.value.trim())) {
                showError(emailInput, 'Please enter a valid email.');
                valid = false;
            }

            if (!passwordInput.value) {
                showError(passwordInput, 'Password is required.');
                valid = false;
            } else if (passwordInput.value.length < 6) {
                showError(passwordInput, 'Password must be at least 6 characters.');
                valid = false;
            }

            if (!valid) return;

            // Show loading state
            btnLogin.disabled = true;
            btnLogin.querySelector('.btn-text').style.display = 'none';
            btnLogin.querySelector('.btn-loader').style.display = 'inline-flex';

            // Simulate login request (replace with actual AJAX call)
            setTimeout(function () {
                window.location.href = getURL('home');
            }, 800);
        });
    }

    // ===== REGISTER FORM =====
    var registerForm = document.getElementById('registerForm');
    if (registerForm) {
        var fields = {
            firstName: registerForm.querySelector('#firstName'),
            lastName: registerForm.querySelector('#lastName'),
            email: registerForm.querySelector('#email'),
            password: registerForm.querySelector('#password'),
            confirmPassword: registerForm.querySelector('#confirmPassword')
        };
        var btnRegister = document.getElementById('btnRegister');

        Object.keys(fields).forEach(function (key) {
            fields[key].addEventListener('input', function () {
                clearError(fields[key]);
            });
        });

        registerForm.addEventListener('submit', function (e) {
            e.preventDefault();
            var valid = true;

            if (!fields.firstName.value.trim()) {
                showError(fields.firstName);
                valid = false;
            }

            if (!fields.lastName.value.trim()) {
                showError(fields.lastName);
                valid = false;
            }

            if (!fields.email.value.trim()) {
                showError(fields.email);
                valid = false;
            } else if (!isValidEmail(fields.email.value.trim())) {
                showError(fields.email);
                valid = false;
            }

            if (!fields.password.value) {
                showError(fields.password);
                valid = false;
            } else if (fields.password.value.length < 6) {
                showError(fields.password);
                valid = false;
            }

            if (!fields.confirmPassword.value) {
                showError(fields.confirmPassword);
                valid = false;
            } else if (fields.confirmPassword.value !== fields.password.value) {
                showError(fields.confirmPassword);
                valid = false;
            }

            if (!valid) return;

            // Show loading state
            btnRegister.disabled = true;
            btnRegister.querySelector('.btn-text').style.display = 'none';
            btnRegister.querySelector('.btn-loader').style.display = 'inline-flex';

            // Simulate registration request (replace with actual AJAX call)
            setTimeout(function () {
                btnRegister.disabled = false;
                btnRegister.querySelector('.btn-text').style.display = 'inline';
                btnRegister.querySelector('.btn-loader').style.display = 'none';
            }, 2000);
        });
    }

    // ===== HOME PAGE: SEARCH + FILTER =====
    var searchInput = document.getElementById('searchInput');
    var eventsGrid = document.getElementById('eventsGrid');
    var noResults = document.getElementById('noResults');

    function applyFilter() {
        if (!eventsGrid) return;
        var term = (searchInput ? searchInput.value : '').trim().toLowerCase();
        var cards = eventsGrid.querySelectorAll('.event-card');
        var visible = 0;

        cards.forEach(function (card) {
            var text = (card.getAttribute('data-title') + ' ' +
                        card.getAttribute('data-location') + ' ' +
                        card.getAttribute('data-category')).toLowerCase();
            var match = text.indexOf(term) !== -1;
            card.style.display = match ? '' : 'none';
            if (match) visible++;
        });

        if (noResults) noResults.style.display = visible ? 'none' : 'block';
        var countEl = document.getElementById('eventCount');
        if (countEl) countEl.textContent = visible + ' event' + (visible === 1 ? '' : 's');
    }

    var btnSearch = document.getElementById('btnSearch');
    if (btnSearch) btnSearch.addEventListener('click', applyFilter);
    if (searchInput) searchInput.addEventListener('input', applyFilter);
    window.addEventListener('load', applyFilter);

    // ===== HOME PAGE: BOOKING MODAL =====
    var bookingOverlay = document.getElementById('bookingOverlay');
    var bookingClose = document.getElementById('bookingClose');
    var confirmBooking = document.getElementById('confirmBooking');
    var ticketQty = document.getElementById('ticketQty');

    if (eventsGrid) {
        eventsGrid.addEventListener('click', function (e) {
            var btn = e.target.closest('.event-book-btn');
            if (!btn || !bookingOverlay) return;

            var price = parseFloat(btn.getAttribute('data-price')) || 0;
            document.getElementById('bookingTitle').textContent = btn.getAttribute('data-title');
            document.getElementById('bookingMeta').textContent =
                btn.getAttribute('data-date') + ' \u00b7 ' + btn.getAttribute('data-time') + ' \u00b7 ' +
                btn.getAttribute('data-location');
            ticketQty.value = 1;
            document.getElementById('qtyError').textContent = '';
            updateTotal(price);
            bookingOverlay.dataset.price = price;
            bookingOverlay.style.display = 'flex';
        });
    }

    function updateTotal(price) {
        var el = document.getElementById('bookingTotal');
        var qty = parseInt(ticketQty.value, 10) || 1;
        if (el) el.textContent = '$' + (price * qty).toFixed(2);
    }

    if (ticketQty) ticketQty.addEventListener('input', function () {
        var price = parseFloat(bookingOverlay.dataset.price) || 0;
        document.getElementById('qtyError').textContent = '';
        updateTotal(price);
    });

    if (bookingClose) bookingClose.addEventListener('click', function () {
        bookingOverlay.style.display = 'none';
    });

    if (bookingOverlay) {
        bookingOverlay.addEventListener('click', function (e) {
            if (e.target === bookingOverlay) bookingOverlay.style.display = 'none';
        });
    }

    if (confirmBooking) {
        confirmBooking.addEventListener('click', function () {
            var qty = parseInt(ticketQty.value, 10);
            var qtyError = document.getElementById('qtyError');

            if (!qty || qty < 1) {
                qtyError.textContent = 'Please enter a valid number of tickets.';
                return;
            }
            if (qty > 10) {
                qtyError.textContent = 'Maximum 10 tickets per booking.';
                return;
            }

            confirmBooking.disabled = true;
            confirmBooking.querySelector('.btn-text').style.display = 'none';
            confirmBooking.querySelector('.btn-loader').style.display = 'inline-flex';

            setTimeout(function () {
                bookingOverlay.style.display = 'none';
                confirmBooking.disabled = false;
                confirmBooking.querySelector('.btn-text').style.display = 'inline';
                confirmBooking.querySelector('.btn-loader').style.display = 'none';
                alert('Booking confirmed! Check "My Tickets" for your passes.');
            }, 800);
        });
    }

    // ===== ADD EVENT FORM =====
    var addEventForm = document.getElementById('addEventForm');
    if (addEventForm) {
        var addFields = {
            eventTitle: document.getElementById('eventTitle'),
            eventLocation: document.getElementById('eventLocation'),
            eventCapacity: document.getElementById('eventCapacity'),
            eventPrice: document.getElementById('eventPrice'),
            eventDescription: document.getElementById('eventDescription')
        };
        var btnAddEvent = document.getElementById('btnAddEvent');

        var btnAddEvent = document.getElementById('btnAddEvent');

        function checkedValue(name) {
            var el = document.querySelector('input[name="' + name + '"]:checked');
            return el ? el.value : '';
        }

        function setVisibility(ids, visible) {
            ids.forEach(function (id) {
                var el = document.getElementById(id);
                if (el) el.style.display = visible ? '' : 'none';
            });
        }

        function bindRadioGroup(name, handler) {
            var radios = document.querySelectorAll('input[name="' + name + '"]');
            radios.forEach(function (radio) {
                radio.addEventListener('change', function () {
                    radios.forEach(function (r) {
                        r.closest('.radio-option').classList.toggle('selected', r.checked);
                    });
                    handler(checkedValue(name));
                });
            });
            radios.forEach(function (r) {
                r.closest('.radio-option').classList.toggle('selected', r.checked);
            });
            handler(checkedValue(name));
        }

        // Date type: single day vs period
        bindRadioGroup('eventDateType', function (value) {
            setVisibility(['singleDateFields'], value === 'single');
            setVisibility(['periodDateFields'], value === 'period');
        });

        // Capacity: unlimited vs limited
        bindRadioGroup('eventCapacityType', function (value) {
            setVisibility(['capacityLimitedField'], value === 'limited');
        });

        // Ticket requirement: show event picker when a ticket is required
        bindRadioGroup('eventRequiresTicket', function (value) {
            setVisibility(['ticketRequirementField'], value === 'yes');
        });

        // Pricing: by role or not + 1 cost / 2 costs
        function renderPricing() {
            var byRole = checkedValue('eventRolePricing') === 'yes';
            var isTiers = checkedValue('eventPriceType') === 'tiers';
            setVisibility(['singlePriceField'], !byRole && !isTiers);
            setVisibility(['singleRolePrices'], byRole && !isTiers);
            setVisibility(['fixedTiersField'], !byRole && isTiers);
            setVisibility(['roleTiersField'], byRole && isTiers);
        }

        bindRadioGroup('eventRolePricing', renderPricing);
        bindRadioGroup('eventPriceType', renderPricing);

        Object.keys(addFields).forEach(function (key) {
            if (addFields[key]) {
                addFields[key].addEventListener('input', function () {
                    clearError(addFields[key]);
                });
            }
        });

        document.querySelectorAll('#singleDateFields input, #periodDateFields input, #capacityLimitedField input').forEach(function (el) {
            el.addEventListener('input', function () {
                clearError(el);
            });
        });

        document.querySelectorAll('#singlePriceField input, #singleRolePrices input, #fixedTiersField input, #roleTiersField input').forEach(function (el) {
            el.addEventListener('input', function () {
                clearError(el);
            });
        });

        function showAddError(key, message) {
            var field = addFields[key] || document.getElementById(key);
            if (field) showError(field, message);
        }

        addEventForm.addEventListener('submit', function (e) {
            e.preventDefault();
            var valid = true;

            if (!addFields.eventTitle.value.trim()) {
                showAddError('eventTitle', 'Event title is required.');
                valid = false;
            }

            var isSingleDay = document.querySelector('input[name="eventDateType"]:checked').value === 'single';
            if (isSingleDay) {
                if (!document.getElementById('eventDate').value) {
                    showAddError('eventDate', 'Please pick a date.');
                    valid = false;
                }
            } else {
                var startDate = document.getElementById('eventStartDate').value;
                var endDate = document.getElementById('eventEndDate').value;
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

            if (!addFields.eventLocation.value.trim()) {
                showAddError('eventLocation', 'Location is required.');
                valid = false;
            }

            var isLimited = document.querySelector('input[name="eventCapacityType"]:checked').value === 'limited';
            if (isLimited) {
                if (!addFields.eventCapacity.value || parseInt(addFields.eventCapacity.value, 10) < 1) {
                    showAddError('eventCapacity', 'Enter a valid capacity (min 1).');
                    valid = false;
                }
            }

            var byRole = checkedValue('eventRolePricing') === 'yes';
            function checkPrice(id, roleName) {
                var el = document.getElementById(id);
                if (el && (el.value === '' || parseFloat(el.value) < 0)) {
                    showAddError(id, (roleName ? roleName + ' price' : 'Price') + ' is required.');
                    valid = false;
                }
            }
            function checkTierDates(prefix, dateId) {
                if (!document.getElementById(dateId).value) {
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

            if (!addFields.eventDescription.value.trim()) {
                showAddError('eventDescription', 'Please add a description.');
                valid = false;
            }

            if (!valid) return;

            // Show loading state
            btnAddEvent.disabled = true;
            btnAddEvent.querySelector('.btn-text').style.display = 'none';
            btnAddEvent.querySelector('.btn-loader').style.display = 'inline-flex';

            // Simulate save request (replace with actual AJAX call)
            setTimeout(function () {
                btnAddEvent.disabled = false;
                btnAddEvent.querySelector('.btn-text').style.display = 'inline';
                btnAddEvent.querySelector('.btn-loader').style.display = 'none';
                alert('Event posted successfully!');
                window.location.href = getURL('home');
            }, 800);
        });
    }
});