<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo $pageTitle; ?></title>
    <link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap/bootstrap.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/styles.css'); ?>">
</head>
<body class="<?php echo $bodyClass; ?>">
    <?php if ($isNavbar): ?>
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
                <a href="<?php echo ($activeNav === 'browse') ? '#events' : base_url('events'); ?>" class="nav-link<?php echo ($activeNav === 'browse') ? ' active' : ''; ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
                    Browse Events
                </a>
                <?php if ($userRole === 0 || $userRole === 4): ?>
                <a href="<?php echo base_url('event/add_event'); ?>" class="nav-link<?php echo ($activeNav === 'add') ? ' active' : ''; ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg>
                    Add Event
                </a>
                <?php endif; ?>
                <a href="<?php echo base_url('my_tickets'); ?>" class="nav-link<?php echo ($activeNav === 'tickets') ? ' active' : ''; ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z"></path><path d="M13 5v2"></path><path d="M13 17v2"></path><path d="M13 11v2"></path></svg>
                    <?php echo ($userRole === 4) ? 'All Tickets' : 'My Tickets'; ?>
                </a>
            </div>
            <div class="user-menu">
                <button type="button" class="user-avatar" id="userAvatarBtn"><?php echo $initial; ?></button>
                <div class="user-dropdown" id="userDropdown">
                    <div class="user-dropdown-header">
                        <span class="user-avatar-lg"><?php echo $initial; ?></span>
                        <div>
                            <div class="user-name"><?php echo htmlspecialchars($userName); ?></div>
                            <div class="user-email"><?php echo htmlspecialchars($userEmail); ?></div>
                        </div>
                    </div>
                    <hr>
                    <a href="#" class="user-dropdown-item edit-profile-link">Edit Profile</a>
                    <a href="<?php echo base_url('logout'); ?>" class="user-dropdown-item">Logout</a>
                </div>
            </div>
        </div>
    </nav>
    <?php endif; ?>