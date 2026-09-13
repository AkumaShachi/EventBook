<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!-- ===== BOOKING MODAL ===== -->
<div class="booking-overlay" id="bookingOverlay" style="display:none;">
    <div class="booking-modal">
        <button type="button" class="booking-close" id="bookingClose" aria-label="Close">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
        </button>
        <h2 id="bookingTitle">Buy Ticket</h2>
        <p class="booking-sub" id="bookingMeta"></p>

        <div class="booking-tickets" id="ticketOptions"></div>

        <div class="input-wrapper booking-qty">
            <label for="ticketQty">Number of tickets</label>
            <input type="number" id="ticketQty" min="1" max="10" value="1">
            <span class="error-message" id="qtyError"></span>
        </div>

        <div class="booking-total">
            <span>Total</span>
            <strong id="bookingTotal">$0.00</strong>
        </div>

        <button type="button" class="btn-login" id="confirmBooking">
            <span class="btn-text">Confirm Buy</span>
            <span class="btn-loader" style="display:none;">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="spinner">
                    <path d="M21 12a9 9 0 1 1-6.219-8.56"></path>
                </svg>
            </span>
        </button>
    </div>
</div>