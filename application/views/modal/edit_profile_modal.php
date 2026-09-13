<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!-- ===== EDIT PROFILE MODAL ===== -->
<div class="user-modal" id="editProfileModal">
    <div class="user-modal-backdrop" data-close-modal></div>
    <div class="user-modal-card">
        <button type="button" class="user-modal-close" data-close-modal aria-label="Close">&times;</button>
        <div class="login-header">
            <div class="login-logo">
                <svg xmlns="http://www.w3.org/2000/svg" width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                    <circle cx="12" cy="7" r="4"></circle>
                </svg>
            </div>
            <h1>Edit Profile</h1>
            <p>Update your account details</p>
        </div>

        <form id="editProfileForm" class="login-form" novalidate data-role="<?php echo $userRole; ?>" data-institution="<?php echo htmlspecialchars($userInstitution); ?>">
            <div class="form-row">
                <div class="form-group">
                    <label for="firstName">First Name</label>
                    <div class="input-wrapper">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="input-icon">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                        <input type="text" id="firstName" name="firstName" placeholder="First name" value="<?php echo htmlspecialchars($firstName); ?>">
                    </div>
                    <span id="firstNameError" class="error-message"></span>
                </div>

                <div class="form-group">
                    <label for="lastName">Last Name</label>
                    <div class="input-wrapper">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="input-icon">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                        <input type="text" id="lastName" name="lastName" placeholder="Last name" value="<?php echo htmlspecialchars($lastName); ?>">
                    </div>
                    <span id="lastNameError" class="error-message"></span>
                </div>
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <div class="input-wrapper">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="input-icon">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                        <polyline points="22,6 12,13 2,6"></polyline>
                    </svg>
                    <input type="email" id="email" name="email" placeholder="Enter your email" value="<?php echo htmlspecialchars($userEmail); ?>">
                    <button type="button" class="btn-verify" data-verify="email">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        <span>Verify</span>
                    </button>
                </div>
                <span id="emailError" class="error-message"></span>
            </div>

            <div class="form-group">
                <label for="phone">Phone Number</label>
                <div class="input-wrapper">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="input-icon">
                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                    </svg>
                    <input type="tel" id="phone" name="phone" placeholder="Enter your phone number" value="<?php echo htmlspecialchars($userPhone); ?>">
                    <button type="button" class="btn-verify" data-verify="phone">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        <span>Verify</span>
                    </button>
                </div>
                <span id="phoneError" class="error-message"></span>
            </div>

            <div class="form-group">
                <label for="newPassword">New Password (optional)</label>
                <div class="input-wrapper">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="input-icon">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                    </svg>
                    <input type="password" id="newPassword" name="newPassword" placeholder="Leave blank to keep current" autocomplete="new-password">
                    <button type="button" class="toggle-password" data-target="newPassword">Show</button>
                </div>
                <span id="newPasswordError" class="error-message"></span>
            </div>

            <div id="editRolePhotoFields"></div>

            <input type="hidden" id="comfirmedEmail" name="comfirmed_email" value="">
            <input type="hidden" id="comfirmedPhone" name="comfirmed_phone" value="">

            <button type="submit" class="btn-login" id="btnSaveProfile">
                <span class="btn-text">Save Changes</span>
                <span class="btn-loader" style="display:none;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="spinner">
                        <path d="M21 12a9 9 0 1 1-6.219-8.56"></path>
                    </svg>
                </span>
            </button>
        </form>
    </div>
</div>