<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$i_dollar = '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="input-icon"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>';
$i_cal = '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="input-icon"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>';
$i_tag = '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="input-icon"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>';
$i_pin = '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="input-icon"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>';
$i_users = '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="input-icon"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"></path></svg>';
$ticketEvents = array(
    array('id' => 1, 'title' => 'Summer Music Festival', 'date' => 'Jun 20, 2026'),
    array('id' => 2, 'title' => 'Tech Conference 2026', 'date' => 'Jul 08, 2026'),
    array('id' => 3, 'title' => 'Food & Wine Expo', 'date' => 'Aug 02, 2026'),
    array('id' => 4, 'title' => 'Jazz Night Gala', 'date' => 'Aug 15, 2026'),
    array('id' => 5, 'title' => 'Startup Pitch Day', 'date' => 'Sep 05, 2026'),
    array('id' => 6, 'title' => 'Art & Design Fair', 'date' => 'Oct 12, 2026'),
);
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>EventBook - Add Event</title>
    <link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap/bootstrap.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/styles.css'); ?>">
</head>
<body class="home-body">
    <!-- ===== NAVBAR ===== -->
    <nav class="home-navbar">
        <div class="home-navbar-inner">
            <button type="button" class="home-menu-toggle" id="menuToggle" aria-label="Toggle menu" aria-expanded="false">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                    <line x1="16" y1="2" x2="16" y2="6"></line>
                    <line x1="8" y1="2" x2="8" y2="6"></line>
                    <line x1="3" y1="10" x2="21" y2="10"></line>
                    <circle cx="12" cy="15" r="2"></circle>
                </svg>
            </button>
            <div class="home-nav-links" id="homeNavLinks">
                <a href="<?php echo base_url('home#events'); ?>" class="nav-link">
                    <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
                    Browse Events
                </a>
                <a href="<?php echo base_url('home/add_event'); ?>" class="nav-link active">
                    <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg>
                    Add Event
                </a>
                <a href="#" class="nav-link">
                    <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z"></path><path d="M13 5v2"></path><path d="M13 17v2"></path><path d="M13 11v2"></path></svg>
                    My Tickets
                </a>
            </div>
            <a href="<?php echo base_url('login'); ?>" class="btn-login home-btn-logout">Logout</a>
        </div>
    </nav>

    <!-- ===== ADD EVENT FORM ===== -->
    <main class="home-main">
        <div class="home-main-inner add-event-body">
            <div class="section-head">
                <h2>Add New Event</h2>
            </div>

            <div class="login-card add-event-card">
                <form id="addEventForm" class="login-form" novalidate>

                    <div class="event-section">
                        <span class="event-section-label"><span class="section-num">01</span> Basics</span>
                        <div class="form-grid-2">
                            <div class="form-group">
                                <label for="eventTitle">Event Title</label>
                                <div class="input-wrapper"><?php echo $i_tag; ?>
                                    <input type="text" id="eventTitle" name="eventTitle" placeholder="Event title" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="eventLocation">Location</label>
                                <div class="input-wrapper"><?php echo $i_pin; ?>
                                    <input type="text" id="eventLocation" name="eventLocation" placeholder="Event location" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="event-section">
                        <span class="event-section-label"><span class="section-num">02</span> When</span>
                        <div class="radio-group">
                            <label class="radio-option">
                                <input type="radio" name="eventDateType" value="single" checked>
                                <span>One Day</span>
                            </label>
                            <label class="radio-option">
                                <input type="radio" name="eventDateType" value="period">
                                <span>Date Period</span>
                            </label>
                        </div>

                        <div class="form-row" id="singleDateFields">
                            <div class="form-group">
                                <label for="eventDate">Date</label>
                                <div class="input-wrapper"><?php echo $i_cal; ?>
                                    <input type="date" id="eventDate" name="eventDate">
                                </div>
                            </div>
                        </div>

                        <div class="form-row" id="periodDateFields" style="display:none;">
                            <div class="form-group">
                                <label for="eventStartDate">Start Date</label>
                                <div class="input-wrapper"><?php echo $i_cal; ?>
                                    <input type="date" id="eventStartDate" name="eventStartDate">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="eventEndDate">End Date</label>
                                <div class="input-wrapper"><?php echo $i_cal; ?>
                                    <input type="date" id="eventEndDate" name="eventEndDate">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="event-section">
                        <span class="event-section-label"><span class="section-num">03</span> Capacity</span>
                        <div class="capacity-grid">
                            <div class="radio-group">
                                <label class="radio-option">
                                    <input type="radio" name="eventCapacityType" value="unlimited" checked>
                                    <span>Unlimited</span>
                                </label>
                                <label class="radio-option">
                                    <input type="radio" name="eventCapacityType" value="limited">
                                    <span>Limited</span>
                                </label>
                            </div>
                            <div class="input-wrapper" id="capacityLimitedField" style="display:none;"><?php echo $i_users; ?>
                                <input type="number" id="eventCapacity" name="eventCapacity" min="1" placeholder="Number of seats">
                            </div>
                        </div>
                    </div>

                    <div class="event-section">
                        <span class="event-section-label"><span class="section-num">04</span> Pricing</span>
                        <div class="price-mode">
                            <div class="price-mode-step">
                                <span class="price-mode-label">Who pays?</span>
                                <div class="radio-group price-mode-group">
                                    <label class="radio-option">
                                        <input type="radio" name="eventRolePricing" value="no" checked>
                                        <span>One price</span>
                                    </label>
                                    <label class="radio-option">
                                        <input type="radio" name="eventRolePricing" value="yes">
                                        <span>Each price</span>
                                    </label>
                                </div>
                            </div>
                            <div class="price-mode-step">
                                <span class="price-mode-label">Ticket Type</span>
                                <div class="radio-group price-mode-group">
                                    <label class="radio-option">
                                        <input type="radio" name="eventPriceType" value="single" checked>
                                        <span>Single Ticket</span>
                                    </label>
                                    <label class="radio-option">
                                        <input type="radio" name="eventPriceType" value="tiers">
                                        <span>Early + Late</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="price-tiers" id="singlePriceField">
                            <div class="price-tier">
                                <div class="price-tier-title">Ticket Price</div>
                                <div class="form-group">
                                    <label for="eventPrice">Price ($)</label>
                                    <div class="input-wrapper"><?php echo $i_dollar; ?>
                                        <input type="number" id="eventPrice" name="eventPrice" min="0" step="0.01" placeholder="Price per ticket">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="eventPriceDate">Valid until</label>
                                    <div class="input-wrapper"><?php echo $i_cal; ?>
                                        <input type="date" id="eventPriceDate" name="eventPriceDate">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="price-tiers" id="singleRolePrices" style="display:none;">
                            <div class="price-tier">
                                <div class="price-tier-title">Ticket Price</div>
                                <div class="role-prices role-prices-dates">
                                    <span class="role-price-head">role</span>
                                    <span class="role-price-head">price</span>
                                    <span class="role-price-head role-price-head-date">Valid until</span>
                                    <span class="role-price-label">Guest</span>
                                    <div class="input-wrapper"><?php echo $i_dollar; ?>
                                        <input type="number" id="priceGuest" name="guestPrice" min="0" step="0.01" placeholder="Price">
                                    </div>
                                    <div class="input-wrapper"><?php echo $i_cal; ?>
                                        <input type="date" id="priceGuestDate" name="priceGuestDate">
                                    </div>
                                    <span class="role-price-label">Student</span>
                                    <div class="input-wrapper"><?php echo $i_dollar; ?>
                                        <input type="number" id="priceStudent" name="studentPrice" min="0" step="0.01" placeholder="Price">
                                    </div>
                                    <div class="input-wrapper"><?php echo $i_cal; ?>
                                        <input type="date" id="priceStudentDate" name="priceStudentDate">
                                    </div>
                                    <span class="role-price-label">Member</span>
                                    <div class="input-wrapper"><?php echo $i_dollar; ?>
                                        <input type="number" id="priceMember" name="memberPrice" min="0" step="0.01" placeholder="Price">
                                    </div>
                                    <div class="input-wrapper"><?php echo $i_cal; ?>
                                        <input type="date" id="priceMemberDate" name="priceMemberDate">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="price-tiers" id="fixedTiersField" style="display:none;">
                            <div class="price-tier">
                                <div class="price-tier-title">Early Ticket Price</div>
                                <div class="form-group">
                                    <label for="earlyPrice">Price ($)</label>
                                    <div class="input-wrapper"><?php echo $i_dollar; ?>
                                        <input type="number" id="earlyPrice" name="earlyPrice" min="0" step="0.01" placeholder="Early price">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="earlyDate">Valid until</label>
                                    <div class="input-wrapper"><?php echo $i_cal; ?>
                                        <input type="date" id="earlyDate" name="earlyDate">
                                    </div>
                                </div>
                            </div>
                            <div class="price-tier">
                                <div class="price-tier-title">Late Ticket</div>
                                <div class="form-group">
                                    <label for="latePrice">Late Price ($)</label>
                                    <div class="input-wrapper"><?php echo $i_dollar; ?>
                                        <input type="number" id="latePrice" name="latePrice" min="0" step="0.01" placeholder="Late price">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="lateDate">Valid until</label>
                                    <div class="input-wrapper"><?php echo $i_cal; ?>
                                        <input type="date" id="lateDate" name="lateDate">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="price-tiers" id="roleTiersField" style="display:none;">
                            <div class="price-tier">
                                <div class="price-tier-title">Early Ticket</div>
                                <div class="role-prices role-prices-dates">
                                    <span class="role-price-head">role</span>
                                    <span class="role-price-head">price</span>
                                    <span class="role-price-head role-price-head-date">Valid until</span>
                                    <span class="role-price-label">Guest</span>
                                    <div class="input-wrapper"><?php echo $i_dollar; ?>
                                        <input type="number" id="earlyGuest" name="earlyGuest" min="0" step="0.01" placeholder="Price">
                                    </div>
                                    <div class="input-wrapper"><?php echo $i_cal; ?>
                                        <input type="date" id="earlyGuestDate" name="earlyGuestDate">
                                    </div>
                                    <span class="role-price-label">Student</span>
                                    <div class="input-wrapper"><?php echo $i_dollar; ?>
                                        <input type="number" id="earlyStudent" name="earlyStudent" min="0" step="0.01" placeholder="Price">
                                    </div>
                                    <div class="input-wrapper"><?php echo $i_cal; ?>
                                        <input type="date" id="earlyStudentDate" name="earlyStudentDate">
                                    </div>
                                    <span class="role-price-label">Member</span>
                                    <div class="input-wrapper"><?php echo $i_dollar; ?>
                                        <input type="number" id="earlyMember" name="earlyMember" min="0" step="0.01" placeholder="Price">
                                    </div>
                                    <div class="input-wrapper"><?php echo $i_cal; ?>
                                        <input type="date" id="earlyMemberDate" name="earlyMemberDate">
                                    </div>
                                </div>
                            </div>
                            <div class="price-tier">
                                <div class="price-tier-title">Late Ticket</div>
                                <div class="role-prices role-prices-dates">
                                    <span class="role-price-head">role</span>
                                    <span class="role-price-head">price</span>
                                    <span class="role-price-head role-price-head-date">Valid until</span>
                                    <span class="role-price-label">Guest</span>
                                    <div class="input-wrapper"><?php echo $i_dollar; ?>
                                        <input type="number" id="lateGuest" name="lateGuest" min="0" step="0.01" placeholder="Price">
                                    </div>
                                    <div class="input-wrapper"><?php echo $i_cal; ?>
                                        <input type="date" id="lateGuestDate" name="lateGuestDate">
                                    </div>
                                    <span class="role-price-label">Student</span>
                                    <div class="input-wrapper"><?php echo $i_dollar; ?>
                                        <input type="number" id="lateStudent" name="lateStudent" min="0" step="0.01" placeholder="Price">
                                    </div>
                                    <div class="input-wrapper"><?php echo $i_cal; ?>
                                        <input type="date" id="lateStudentDate" name="lateStudentDate">
                                    </div>
                                    <span class="role-price-label">Member</span>
                                    <div class="input-wrapper"><?php echo $i_dollar; ?>
                                        <input type="number" id="lateMember" name="lateMember" min="0" step="0.01" placeholder="Price">
                                    </div>
                                    <div class="input-wrapper"><?php echo $i_cal; ?>
                                        <input type="date" id="lateMemberDate" name="lateMemberDate">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="event-section">
                        <span class="event-section-label"><span class="section-num">05</span> Event Required</span>
                        <div class="radio-group">
                            <label class="radio-option">
                                <input type="radio" name="eventRequiresTicket" value="no" checked>
                                <span>No</span>
                            </label>
                            <label class="radio-option">
                                <input type="radio" name="eventRequiresTicket" value="yes">
                                <span>Yes</span>
                            </label>
                        </div>
                        <div class="form-group" id="ticketRequirementField" style="display:none;">
                            <label for="eventRequiresWhich">Which event's ticket?</label>
                            <div class="input-wrapper">
                                <select id="eventRequiresWhich" name="eventRequiresWhich">
                                    <option value="">Select event...</option>
                                    <?php foreach ($ticketEvents as $te): ?>
                                    <option value="<?php echo $te['id']; ?>"><?php echo $te['title']; ?> &mdash; <?php echo $te['date']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="event-section last">
                        <span class="event-section-label"><span class="section-num">06</span> About</span>
                        <div class="form-group">
                            <label for="eventDescription">Description</label>
                            <div class="input-wrapper">
                                <textarea id="eventDescription" name="eventDescription" rows="3" placeholder="Describe your event..."></textarea>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn-login" id="btnAddEvent">
                        <span class="btn-text">Add Event</span>
                        <span class="btn-loader" style="display:none;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="spinner">
                                <path d="M21 12a9 9 0 1 1-6.219-8.56"></path>
                            </svg>
                        </span>
                    </button>
                </form>
            </div>
        </div>
    </main>

    <footer class="home-footer">
        <p>&copy; 2026 EventBook. All rights reserved.</p>
    </footer>

    <div class="sidebar-backdrop" id="sidebarBackdrop"></div>

    <script>var BASE_URL = "<?php echo base_url(); ?>";</script>
    <script src="<?php echo base_url('assets/js/bootstrap/bootstrap.bundle.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/scrips.js'); ?>"></script>
</body>
</html>