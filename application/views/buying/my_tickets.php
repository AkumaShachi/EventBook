<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
    <!-- ===== MY TICKETS ===== -->
    <main class="home-main" id="tickets">
        <div class="home-main-inner">
            <div class="section-head">
                <h2><?php echo ($userRole === 4) ? 'All Tickets' : 'My Tickets'; ?></h2>
                <span id="ticketCount"><?php echo count($tickets); ?> ticket<?php echo count($tickets) === 1 ? '' : 's'; ?></span>
            </div>

            <?php if (empty($tickets)): ?>
            <div class="no-results">
                <p>You don't have any tickets yet. Buy one from the Browse Events page.</p>
            </div>
            <?php else: ?>
            <div class="events-grid" id="eventsGrid">
                <?php foreach ($tickets as $ticket): ?>
                    <article class="event-card">
                        <?php if (!empty($ticket['ticket_receipt'])): ?>
                        <img src="<?php echo base_url($ticket['ticket_receipt']); ?>" alt="Receipt" style="width:100%;height:170px;object-fit:cover;display:block;">
                        <?php endif; ?>
                        <div class="event-card-body">
                            <h3 class="event-title"><?php echo htmlspecialchars($ticket['event_name']); ?></h3>
                            <ul class="event-meta">
                                <li>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                                    <?php echo date('M j, Y g:i A', strtotime($ticket['event_start_date'])); ?>
                                </li>
                                <li>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                                    <?php echo htmlspecialchars($ticket['event_location']); ?>
                                </li>
                                <li>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                                    <?php echo ucfirst($ticket['ticket_type']); ?> &middot; $<?php echo number_format($ticket['ticket_price'], 2); ?>
                                </li>
                            </ul>
                            <div class="event-card-footer">
                                <span class="event-price">Purchased <?php echo date('M j, Y', strtotime($ticket['ticket_buy_date'])); ?></span>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </main>