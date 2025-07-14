# PHP Email Reminder and Subscription System

This project is a **lightweight PHP-based email reminder and subscription platform**. It allows users to subscribe with their email address, receive periodic reminders, and manage their subscription status easily.

## ✨ Features

- ✅ User subscription and verification
- ✅ Automatic email reminders via cron job
- ✅ Unsubscribe functionality
- ✅ Clean, responsive frontend
- ✅ Simple setup with minimal dependencies

## 📂 Project Structure

.
├── cron.php # Cron job setup script
├── functions.php # Core helper functions
├── index.php # Main subscription form
├── send_reminders.php # Script to send email reminders
├── setup_cron.sh # Shell script to configure the cron job
├── style.css # Frontend styling
├── subscribe.php # Handle subscriptions
├── unsubscribe.php # Handle unsubscriptions
├── utils.php # Utility functions
├── verify.php # Email verification handler

markdown
Copy
Edit

## ⚙️ Requirements

- PHP 7.0+  
- MySQL (if using a database; adjust accordingly)  
- A mail server or SMTP configuration  
- Unix-based OS (for cron jobs)

## 🚀 Installation

1. **Clone the repository:**
   ```bash
   git clone https://github.com/yourusername/your-repo-name.git
   cd your-repo-name
Configure PHP:

Update any credentials (SMTP, database) in functions.php or utils.php as needed.

Set up the database:

Create your database and tables if required.

Example:

sql
Copy
Edit
CREATE TABLE subscribers (
  id INT AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(255) NOT NULL,
  verified TINYINT(1) DEFAULT 0,
  verification_code VARCHAR(100),
  subscribed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
Configure the cron job:

Make setup_cron.sh executable:

bash
Copy
Edit
chmod +x setup_cron.sh
Run it to schedule the reminders:

bash
Copy
Edit
./setup_cron.sh
This sets up a cron job to run send_reminders.php every hour.

Deploy:

Place files on your web server.

Ensure permissions are correct.

🖥️ Usage
Subscribe:

Navigate to index.php in your browser.

Enter your email and submit the form.

A verification email will be sent.

Verify:

Click the link in the verification email to confirm your subscription.

Receive Reminders:

Once verified, you’ll receive reminders automatically at the configured schedule.

Unsubscribe:

Use the unsubscribe link in the email or navigate to unsubscribe.php.

🎨 Screenshots
(Add screenshots of your UI here)

🛡️ Security Notes
Always sanitize and validate user inputs.

Consider adding CAPTCHA to prevent abuse.

Use environment variables or a config file for sensitive credentials.

Ensure your SMTP server is properly secured.

🤝 Contributing
Contributions are welcome! Feel free to open issues or submit pull requests.


Contact me - priyanshu345kumar@gmail.com
