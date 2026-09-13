#############
EventBook
#############

EventBook is a web-based event ticketing system built with the
`CodeIgniter <https://codeigniter.com>`_ PHP framework. It lets users browse
events, register accounts, book and pay for tickets, and manage their
purchases — all through a clean, modern interface.

===========
Features
===========

-  User registration and login with secure password hashing
-  Role-based access control (regular users and administrators)
-  Event browsing with pricing, capacity, date and location details
-  Ticket booking with quantity selection and multiple ticket types
-  Payment flow with payment confirmation
-  Personal "My Tickets" dashboard
-  Profile editing, including password updates
-  Responsive, mobile-friendly UI

===========
Requirements
===========

-  PHP 5.6 or newer (7.x recommended)
-  MySQL / MariaDB
-  A web server (Apache, Nginx, etc.)

===========
Installation
===========

1. Copy the project into your web root.
2. Create a MySQL database named ``booking_ticket``.
3. Import the schema using the provided ``booking_ticket.sql`` file::

     mysql -u root -p booking_ticket < booking_ticket.sql

4. Open ``application/config/database.php`` and adjust the database
   credentials if needed.
5. Point your browser to the project URL. You will be redirected to the
   login page, where you can register a new account and sign in.

===========
Project Structure
===========

-  ``application/controllers/`` — Auth, Event, Buying and User controllers
-  ``application/models/`` — Event_model and User_model
-  ``application/views/`` — HTML views including home, auth, booking and modal views
-  ``assets/`` — CSS, JavaScript and images
-  ``booking_ticket.sql`` — database schema and seed data