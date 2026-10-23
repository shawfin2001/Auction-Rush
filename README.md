# Auction Rush

Auction Rush is a web-based auction management system built with PHP and MySQL. It lets registered users browse auction items, place bids on live auctions, and manage items they have posted.

## Features

- **User accounts:** Register, log in, and log out.
- **Auction dashboard:** View auction items and their current status.
- **Item management:** Post items with a title, description, starting price, and image; edit or delete your own listings.
- **Bidding:** Place bids on live auctions, with checks to prevent bidding on your own item or submitting a bid that does not exceed the current price.
- **Search:** Search auction listings by title or description.
- **Bid history:** Review your previous bids.
- **Notifications:** Check the status of auctions on which you have bid.
- **Feedback:** Submit a rating and comment for an item.

## Technologies Used

- PHP
- MySQL / MariaDB
- HTML and CSS
- Bootstrap 5
- Font Awesome
- JavaScript and browser-side styling libraries where included

## Requirements

Install a local PHP/MySQL environment, such as **XAMPP**, that includes:
- Apache
- PHP
- MySQL or MariaDB
- phpMyAdmin (recommended for importing the database)

## Installation and Setup

1. **Download or clone the repository.**

   ```bash
   git clone https://github.com/YOUR-USERNAME/Auction-Rush.git
   ```

   Replace `YOUR-USERNAME` with your GitHub username. You can also download the repository as a ZIP file from GitHub.

2. **Move the project into your web server directory.**

   For XAMPP, place the `Auctionrush` project folder inside:

   ```text
   C:\xampp\htdocs\
   ```

   The main PHP files should be accessible under `C:\xampp\htdocs\Auctionrush\`.

3. **Start Apache and MySQL.**

   Open the XAMPP Control Panel and start both services.

4. **Create and import the database.**

   - Open `http://localhost/phpmyadmin/`.
   - Create a database named `auctionrush`.
   - Select that database and choose **Import**.
   - Import the project's `auctionrush.sql` file.

5. **Check the database connection.**

   Open `connect.php`. The default local configuration in this project is:

   ```php
   $host = "localhost";
   $user = "root";
   $pass = "";
   $db   = "auctionrush";
   ```

   Change these values if your local MySQL setup uses a different username, password, or database name. Do not commit real credentials to a public repository.

6. **Open the application.**

   Visit:

   ```text
   http://localhost/Auctionrush/
   ```

   If the project redirects to the login page, register a new account and then sign in.

## Project Structure

```text
Auctionrush/
├── add_item.php
├── auctionrush.sql
├── bid.php
├── connect.php
├── dashboard.php
├── delete.php
├── edit_item.php
├── feedback.php
├── header.php
├── history.php
├── index.php
├── item_details.php
├── login.php
├── logout.php
├── manage_items.php
├── notifications.php
├── organizer_dashboard.php
├── post_item.php
├── register.php
├── search.php
└── view_item.php
```

Uploaded item images are stored in an `uploads/` directory created by the application when needed.

## Database

The SQL export defines tables for:

- `users` — registered accounts
- `items` — auction listings and their prices/statuses
- `bids` — bids placed by users
- `feedback` — item ratings and comments
- `comments` — comments associated with users and items

Import `auctionrush.sql` before running the application.

## Notes

- This project is intended as an educational/local development project. Review and strengthen its security before deploying it publicly.
- The database connection currently uses local-development settings in `connect.php`.
- Do not publish real passwords, personal data, production database dumps, or private uploaded files.
- For public repositories, use a clean database export containing the schema but no personal or sensitive sample records.
- The project uses PHP's password hashing and verification functions for newly registered accounts.

## Future Improvements

Possible enhancements include stronger server-side validation, prepared statements for database queries, CSRF protection, more robust upload validation, and a production-ready configuration for database credentials.

## License

No license has been specified yet. Add a license file if you want to define how others may use, modify, or distribute this project.
