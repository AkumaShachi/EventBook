<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
    <!-- ===== HERO ===== -->
    <header class="home-hero">
        <div class="home-hero-inner">
            <h1>Discover & buy your next event</h1>
            <p>Browse upcoming events, buy tickets in seconds.</p>
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
                    <article class="event-card"
                         data-title="<?php echo $event['title']; ?>"
                         data-location="<?php echo $event['location']; ?>">
                        <div class="event-card-banner" style="background: <?php echo $event['color']; ?>;">
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
                                    <?php echo $event['capacity']; ?><?php echo ($event['capacity'] !== 'Unlimited') ? ' seats' : ''; ?>
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
                                        data-price="<?php echo $event['price']; ?>">Buy Now</button>
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