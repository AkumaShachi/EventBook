<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$i_tag = '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="input-icon"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>';
$i_pin = '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="input-icon"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>';
?>
    <!-- ===== ADD EVENT FORM ===== -->
    <main class="home-main">
        <div class="home-main-inner add-event-body">
            <div class="section-head">
                <h2>Add New Event</h2>
            </div>

            <div class="login-card add-event-card">
                <form id="addEventForm" class="login-form" novalidate action="<?php echo site_url('event/do_add_event'); ?>" method="post" data-add-url="<?php echo site_url('event/do_add_event'); ?>">
                    <?php if ($this->session->flashdata('event_error')): ?>
                        <div class="alert alert-danger"><?php echo $this->session->flashdata('event_error'); ?></div>
                    <?php endif; ?>

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
                        <div id="whenFields"></div>
                    </div>

                    <div class="event-section">
                        <span class="event-section-label"><span class="section-num">03</span> Capacity</span>
                        <div id="capacityFields"></div>
                    </div>

                    <div class="event-section">
                        <span class="event-section-label"><span class="section-num">04</span> Pricing</span>
                        <div id="pricingFields"></div>
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