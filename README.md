# Asaan Marketplace — Investment & Business Matching

Asaan Marketplace (operated by Asaan Export Import Pvt Ltd) is a premium, verified platform designed to connect Nepali entrepreneurs with qualified investors. Built with trust and diligence at its core, the platform ensures that every user and every business pitch undergoes identity verification before deep engagement occurs.

## 🚀 Core Features

- **Identity Verification:** A robust system where admins review KYC documents (ID, Company Registration) to ensure a safe environment for high-value transactions.
- **Advanced Pitching (v1.5):** Entrepreneurs can create detailed business profiles including:
  - Financial projections and pitch decks (PDF/Excel support).
  - Team member profiles with LinkedIn integration.
  - Automated completeness scoring to guide founders.
- **Privacy-First Matching:** Contact details and sensitive data are hidden by default. Information is only exchanged after an interest request is sent and accepted by both parties.
- **Dedicated Dashboards:** Tailored experiences for:
  - **Investors:** Browse verified pitches, manage connections, and track interest requests.
  - **Entrepreneurs:** Manage pitches, upload documents, and track investor engagement.
  - **Admins:** Manage the verification queue, monitor platform-wide interest, and moderate content.
- **Modular Design System:** A custom-built CSS framework featuring the Asaan Brand identity (Navy & Red palette).

## 🛠 Tech Stack

- **Backend:** Core PHP (PDO/MySQL)
- **Frontend:** Vanilla PHP, Vanilla JavaScript, Custom CSS
- **Database:** MySQL / MariaDB

## ⚙️ Installation & Setup

1. **Clone the repository:**
   ```bash
   git clone https://github.com/isoftrosolutions/Asaan-Credit-Ltd.git
   cd invest-match-laravel
   ```

2. **Configure Environment:**
   - Edit `.env` with your database credentials:
   ```
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=your_database
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   APP_URL=https://yourdomain.com
   ```

3. **Database Setup:**
   - Import the SQL schema from `database/schema.sql` (or run migrations)
   - The app will auto-connect using the `.env` credentials

4. **Web Server:**
   - Point DocumentRoot to the `public/` directory
   - Ensure `mod_rewrite` is enabled for clean URLs
   - No Composer or Node.js required

## 📄 License

This project is private and proprietary to Asaan Export Import Pvt Ltd.
