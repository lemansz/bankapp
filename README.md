# Silver Bank

Silver Bank is a simulated banking application built using HTML, CSS, JavaScript, PHP, and MySQL. It is designed to mimic the core features of a real-world banking system, providing both user and administrative functionalities.

## Features

### User Side
 - **Landing Page, About Us, Contact Us:** Includes a working contact form. Messages sent by users are visible to staff in the administrative dashboard.
 - **Account Management:** Users can create accounts, sign up, and log in.
 - **Transactions:** Users can transfer money to other users by entering the recipient's account number (which is their name).
 - **Airtime & Data Purchase:** Users can buy airtime and data for various ISPs.
 - **Transaction History:** Users can view their transaction history.
 - **Dynamic Greetings:** Users are greeted based on the time of day.
 - **Forgot Password:** Users can reset their password if forgotten.
 - **Session Management:** Ensures secure user sessions.
 - **Form Validation:** Both client-side and server-side validation for all forms.

### Administrative Side
- **Dashboard:** Admins can view total transactions, airtime purchases, data purchases, and breakdowns by ISP.
- **Staff Management:** Admins can add staff members such as cashiers and customer service representatives.
- **Cashier Role:** Cashiers can simulate depositing money for customers.
- **Customer Service:** Staff can view customer complaints and respond via email.
- **User & Transaction Search:** Admins can find users and inspect transactions.
- **Forgot Passkey:** Staff members have a forgot passkey feature for account recovery.
- **Session Management:** Secure sessions for staff and admins.
- 
## Technologies Used
- **Frontend:** HTML, CSS, JavaScript
- **Backend:** PHP
- **Database:** MySQL

## Getting Started
1. Clone the repository.
2. Set up a local server (e.g., XAMPP) and import the MySQL database.
3. Configure database credentials in `db.php`.
4. Access the application via your local server.

---

## Security & Validation
 - All user and staff inputs are validated both client-side (JavaScript) and server-side (PHP).
 - Passwords and staff passkeys are securely hashed before storage.
 - Session management is implemented for both users and staff to prevent unauthorized access and session fixation.

## Database
 - MySQL is used for storing user, staff, transaction, and contact message data.
 - Database credentials are configured in `db.php`.
 - User accounts and staff roles are managed in separate tables.

## Transaction Features
 - Users can transfer funds to other users by entering the recipient's account number.
 - Minimum deposit and validation checks are enforced for transactions.
 - Airtime and data purchases are available for multiple ISPs, with validation for phone numbers and sufficient balance.

## Administrative Features
 - Admin dashboard displays statistics: total users, transactions, airtime/data purchases, and breakdowns by ISP.
 - Admins can add staff (cashiers, customer service reps) and assign roles.
 - Cashiers can deposit money for customers.
 - Customer service reps can view and respond to user complaints via email.
 - Admins can search for users and inspect transaction details.

## Forgot Password/Passkey
 - Both users and staff have password/passkey reset features, with secure token-based recovery.

## Contact & Support
 - Users can submit messages via the contact form; staff can view and respond to these in the admin dashboard.

## Usage Notes
 - The project is intended for educational and simulation purposes only.
 - The purpose of this proeject was to demonstrate my knowledge in a bootcamp. It is not a real Bank App*
 - For local development, use XAMPP or similar to run PHP and MySQL.
