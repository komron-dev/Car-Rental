# Car Rental – PHP Car Rental Application

iKarRental is a **PHP** web application that allows users to browse, filter, and book rental cars uploaded by an administrator. This was created as part of a PHP assignment, with an emphasis on server‐side logic, form validation, user authentication, and a polished UI/UX.

## Features

1. **Browsing Cars**  
   - Anyone can view all available cars on the homepage.
   - Filters: Users can refine results by:
     - Date range (availability),
     - Transmission type (Automatic / Manual),
     - Number of passengers,
     - Daily price range.

2. **Car Details Page**  
   - Shows full specs (brand, model, year, fuel type, etc.).
   - Displays car image.
   - Logged‐in users can book from here; guests must log in first.

3. **Booking**  
   - Logged‐in users can select a date range (start and end dates).
   - If the car is free, a success page summarizes reservation info and cost.
   - If unavailable, a failure page shows a warning and suggests returning.

4. **User Authentication**  
   - **Register** with a full name, email, password, and (optionally) upload a profile photo.  
   - **Login** to see your profile and manage bookings.
   - Validation checks for empty fields, invalid emails, weak passwords, etc.

5. **Profile**  
   - Displays the user’s past and current bookings with car images and daily cost.  
   - Users can edit their **full name** and **profile photo**.  
   - Logout button is accessible on every page.

6. **Administrator Functions**  
   - **Admin login** with `admin@ikarrental.hu` / `admin` (by default).
   - Admin sees:
     - All cars, with buttons to add, edit, delete.
     - All bookings in the system.
   - Deleting a car also deletes its related bookings.
   - When editing or adding cars, required fields are validated.

## Project Structure


### Highlights

- `index.php` – Homepage with filtering and list of cars.  
- `car_details.php` – Detailed car page with booking form.  
- `book_car.php` – Processes booking requests, checks availability, redirects on success/fail.  
- `booking_result.php` – Shows final booking result.  
- `register.php` / `login.php` – User authentication with server‐side validation.  
- `profile.php` – Displays user’s reservations, allows editing profile via `edit_profile.php`.  
- `admin_cars.php` / `admin_new_car.php` / `admin_edit_car.php` / `admin_delete_car.php` – Admin panels to manage cars and see all bookings.  
- `utils/` – Contains `storage.php` (for data persistence) and `util.php` (helper functions like `is_logged_in()`, `redirect()`, etc.).  
- `data/` – JSON files storing cars, users, bookings.  
- `css/style.css` – Global styling with a modern, user‐friendly design.

## Installation & Setup

1. **Clone or Download** this repository onto a local PHP‐enabled server environment (e.g., XAMPP, MAMP, WAMP).
2. Ensure the **`data/`** folder is writable (for `.json` files).  
3. Ensure the **`uploads/`** folder is writable for profile and car images.  
4. Adjust any folder permissions if needed (e.g., `chmod 777` on Linux for local dev).
5. Navigate to `http://localhost/.../index.php` in your browser.

## Usage

- **Registration**: Create an account. A default profile image (`uploads/default.png`) is used if none uploaded.  
- **Login**: Enter your email and password.  
- **Browse & Filter**: On the homepage, filter by date range, transmission, passenger capacity, price range, etc.  
- **Booking**: Choose a car, pick start and end dates. If free, booking is saved and displayed in profile. If not, you see a failure message.  
- **Profile**: See your reservations. Edit name/photo via “Edit Profile.” Logout any time.  
- **Admin**: Log in as `admin@ikarrental.hu` / `admin` (or create a user and set `"is_admin": true` in `users.json`). Manage cars, see all bookings, remove or edit them as needed.

### How to run the program
```bash
php -S localhost:8000
```
Port number can be any number
## Important Notes

- **No external PHP frameworks** are used, only basic PHP, JSON files, and a custom `Storage` class.  
- **Server‐side validation** is used for all forms.  
- **novalidate** is set on HTML forms to bypass default browser validation, ensuring server checks.  
- **CSS** is custom, mobile-friendly, with a modern, interesting design.
