<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!-- ===== PAYMENT / QR MODAL ===== -->
<div class="booking-overlay" id="payOverlay" style="display:none;">
    <div class="booking-modal pay-modal">
        <button type="button" class="booking-close" id="payClose" aria-label="Close">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
        </button>
        <h2 id="payTitle">Scan to Pay</h2>
        <p class="booking-sub" id="payMeta"></p>

        <div class="pay-qr">
            <div class="pay-qr-card">
                <div class="pay-qr-frame">
                    <canvas id="payQrCanvas" width="200" height="200"></canvas>
                </div>
                <div class="pay-qr-hint">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3h7v7H3z"></path><path d="M14 3h7v7h-7z"></path><path d="M14 14h7v7h-7z"></path><path d="M3 14h7v7H3z"></path></svg>
                    Scan with your payment app
                </div>
            </div>
        </div>

        <div class="pay-receipt">
            <label>Receipt</label>
            <label class="receipt-upload" id="receiptUpload">
                <input type="file" id="receipt" name="receipt" accept="image/*" hidden>
                <span class="receipt-empty" id="receiptEmpty">
                    <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                    Upload receipt picture
                </span>
                <img id="receiptPreview" class="receipt-preview" alt="Receipt preview" style="display:none;">
            </label>
            <span class="error-message" id="receiptError"></span>
        </div>

        <button type="button" class="btn-login" id="confirmPay">
            <span class="btn-text">Confirm Payment</span>
            <span class="btn-loader" style="display:none;">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="spinner">
                    <path d="M21 12a9 9 0 1 1-6.219-8.56"></path>
                </svg>
            </span>
        </button>
    </div>
</div>