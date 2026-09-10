<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$events = array(
	array('id' => 1, 'title' => 'Summer Music Festival', 'date' => 'Jun 20, 2026', 'time' => '5:00 PM', 'location' => 'Central Park', 'price' => 89.99, 'capacity' => '1,200', 'category' => 'Music', 'color' => '#ff6b6b'),
	array('id' => 2, 'title' => 'Tech Conference 2026', 'date' => 'Jul 08, 2026', 'time' => '9:00 AM', 'location' => 'Convention Center', 'price' => 149.00, 'capacity' => '800', 'category' => 'Tech', 'color' => '#4ecdc4'),
	array('id' => 3, 'title' => 'Food & Wine Expo', 'date' => 'Aug 02, 2026', 'time' => '11:00 AM', 'location' => 'Waterfront Hall', 'price' => 45.50, 'capacity' => '600', 'category' => 'Food', 'color' => '#f9ca24'),
	array('id' => 4, 'title' => 'Jazz Night Gala', 'date' => 'Aug 15, 2026', 'time' => '8:00 PM', 'location' => 'Blue Note Club', 'price' => 65.00, 'capacity' => '350', 'category' => 'Music', 'color' => '#6c5ce7'),
	array('id' => 5, 'title' => 'Startup Pitch Day', 'date' => 'Sep 05, 2026', 'time' => '10:00 AM', 'location' => 'Innovation Hub', 'price' => 25.00, 'capacity' => '250', 'category' => 'Tech', 'color' => '#00b894'),
	array('id' => 6, 'title' => 'Art & Design Fair', 'date' => 'Oct 12, 2026', 'time' => '12:00 PM', 'location' => 'Metro Gallery', 'price' => 35.00, 'capacity' => '500', 'category' => 'Arts', 'color' => '#fd79a8'),
);
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>EventBook - Home</title>
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
                <a href="#events" class="nav-link active">
                    <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
                    Browse Events
                </a>
                <a href="<?php echo base_url('home/add_event'); ?>" class="nav-link">
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

    <!-- ===== HERO ===== -->
    <header class="home-hero">
        <div class="home-hero-inner">
            <h1>Discover & book your next event</h1>
            <p>Browse upcoming events, pick your seats and book tickets in seconds.</p>
            <form id="searchForm" class="home-search" onsubmit="return false;">
                <div class="search-field">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="search-icon">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                    <input type="text" id="searchInput" placeholder="Search events, location, category...">
                </div>
                <button type="button" class="btn-login home-btn-search" id="btnSearch">Search</button>
            </form>
        </div>
    </header>

    <!-- ===== EVENTS GRID ===== -->
    <main class="home-main" id="events">
        <div class="home-main-inner">
            <div class="section-head">
                <h2>Upcoming Events</h2>
                <span id="eventCount"></span>
            </div>
            <div class="events-grid" id="eventsGrid">
                <?php foreach ($events as $event): ?>
                    <article class="event-card" data-category="<?php echo $event['category']; ?>"
                             data-title="<?php echo $event['title']; ?>"
                             data-location="<?php echo $event['location']; ?>">
                        <div class="event-card-banner" style="background: <?php echo $event['color']; ?>;">
                            <span class="event-category"><?php echo $event['category']; ?></span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="event-banner-icon">
                                <path d="M8 2v4M16 2v4M3 10h18"></path>
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                            </svg>
                        </div>
                        <div class="event-card-body">
                            <h3 class="event-title"><?php echo $event['title']; ?></h3>
                            <ul class="event-meta">
                                <li>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                                    <?php echo $event['date'] . ' &middot; ' . $event['time']; ?>
                                </li>
                                <li>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                                    <?php echo $event['location']; ?>
                                </li>
                                <li>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                                    <?php echo $event['capacity']; ?> seats
                                </li>
                            </ul>
                            <div class="event-card-footer">
                                <span class="event-price">$<?php echo number_format($event['price'], 2); ?></span>
                                <button type="button" class="btn-login event-book-btn"
                                        data-id="<?php echo $event['id']; ?>"
                                        data-title="<?php echo $event['title']; ?>"
                                        data-date="<?php echo $event['date']; ?>"
                                        data-time="<?php echo $event['time']; ?>"
                                        data-location="<?php echo $event['location']; ?>"
                                        data-price="<?php echo $event['price']; ?>">Book Now</button>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>

            <!-- Empty state (hidden by default) -->
            <div class="no-results" id="noResults" style="display:none;">
                <p>No events found. Try a different search.</p>
            </div>
        </div>
    </main>

    <!-- ===== BOOKING MODAL ===== -->
    <div class="booking-overlay" id="bookingOverlay" style="display:none;">
        <div class="booking-modal">
            <button type="button" class="booking-close" id="bookingClose" aria-label="Close">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </button>
            <h2 id="bookingTitle">Book Ticket</h2>
            <p class="booking-sub" id="bookingMeta"></p>

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
                <span class="btn-text">Confirm Booking</span>
                <span class="btn-loader" style="display:none;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="spinner">
                        <path d="M21 12a9 9 0 1 1-6.219-8.56"></path>
                    </svg>
                </span>
            </button>
        </div>
    </div>

    <footer class="home-footer">
        <p>&copy; 2026 EventBook. All rights reserved.</p>
    </footer>

    <div class="sidebar-backdrop" id="sidebarBackdrop"></div>

    <script>var BASE_URL = "<?php echo base_url(); ?>";</script>
    <script src="<?php echo base_url('assets/js/bootstrap/bootstrap.bundle.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/scrips.js'); ?>"></script>
</body>
</html>