# EKAGRA ABHYASIKA — Setup & Deployment Guide
## Version 1.0 | PHP + MySQL | InfinityFree Compatible

---

## 📁 PROJECT STRUCTURE

```
ekagra_abhyasika/
│
├── index.php                   ← Public homepage
├── ekagra_db.sql               ← Database schema + seed data
├── README.md                   ← This file
│
├── admin/
│   ├── _sidebar.php            ← Sidebar partial (included by all admin pages)
│   ├── login.php               ← Admin login
│   ├── logout.php              ← Admin logout
│   ├── dashboard.php           ← Main dashboard with stats & charts
│   ├── students.php            ← Student CRUD + search + view
│   ├── seats.php               ← Visual seat map + seat management
│   ├── payments.php            ← Fee recording + renewal alerts
│   ├── reports.php             ← Reports (active, expired, revenue, etc.)
│   ├── search.php              ← Global search page
│   └── change_password.php     ← Admin password change
│
├── student/
│   ├── login.php               ← Student login (mobile + password)
│   ├── dashboard.php           ← Student self-service portal
│   └── logout.php              ← Student logout
│
├── includes/
│   ├── db.php                  ← PDO connection + helper functions
│   ├── header.php              ← Shared HTML <head> + opening <body>
│   └── footer.php              ← Shared scripts + closing tags
│
└── assets/
    ├── css/
    │   └── style.css           ← Complete stylesheet (dark blue theme)
    └── js/
        └── main.js             ← Sidebar, search, CSV export, modals
```

---

## 🗄️ DATABASE SETUP

### Step 1 — Create the database
1. Open **phpMyAdmin** (via your hosting control panel)
2. Click **New** → type database name: `ekagra_db` → click **Create**
3. Select `ekagra_db` from the left panel
4. Click **Import** tab → choose file `ekagra_db.sql` → click **Go**

### Step 2 — Note your credentials
- **Host:** Usually `localhost` (InfinityFree may give a specific hostname)
- **Database:** `ekagra_db`
- **Username:** *(from your hosting panel)*
- **Password:** *(from your hosting panel)*

---

## ⚙️ CONFIGURATION

Open `includes/db.php` and update the four constants:

```php
define('DB_HOST', 'localhost');       // Your MySQL host
define('DB_NAME', 'ekagra_db');      // Database name
define('DB_USER', 'your_username');  // MySQL username
define('DB_PASS', 'your_password');  // MySQL password
define('SITE_URL', 'https://yourdomain.com');
define('ADMIN_PHONE', '9999999999'); // Your phone number
```

Also update the phone numbers in `index.php`:
- Search for `+919999999999` and replace with your actual number

---

## 🚀 INFINITYFREE DEPLOYMENT STEPS

### Step 1 — Create hosting account
1. Go to **infinityfree.net** → Sign up free
2. Create a new hosting account → choose a subdomain or add your domain

### Step 2 — Upload files
**Option A: File Manager (recommended for beginners)**
1. Log in to InfinityFree control panel
2. Go to **File Manager** → open `htdocs` folder
3. Upload all project files maintaining the folder structure
4. Make sure `index.php` is directly inside `htdocs/`

**Option B: FTP**
1. Use FileZilla or similar FTP client
2. Credentials from InfinityFree control panel → FTP Accounts
3. Upload to `/htdocs/` directory

### Step 3 — Database on InfinityFree
1. InfinityFree control panel → **MySQL Databases**
2. Create a new database (name will be auto-prefixed, e.g. `epiz_12345_ekagra_db`)
3. Create a MySQL user and set a password
4. Assign the user to the database (All Privileges)
5. Import `ekagra_db.sql` via phpMyAdmin
6. Update `includes/db.php` with the exact credentials shown

### Step 4 — Set file permissions
- All PHP files: **644**
- All folders: **755**
- InfinityFree File Manager lets you right-click → Permissions

### Step 5 — Test
- Visit `https://yourdomain.com` — homepage should load
- Visit `https://yourdomain.com/admin/login.php` — admin panel

---

## 🔑 DEFAULT CREDENTIALS

| Role    | Username / Mobile | Password    |
|---------|-------------------|-------------|
| Admin   | `admin`           | `Admin@123` |
| Student | `9876543210`      | `Student@123` |
| Student | `9876543220`      | `Student@123` |
| Student | `9876543230`      | `Student@123` |

> ⚠️ **Change the admin password immediately after first login!**
> Admin Panel → Change Password (top right or sidebar)

---

## 🏗️ SEAT LAYOUT LOGIC

| Seats      | Type        | Count | Extra Fee |
|------------|-------------|-------|-----------|
| 1 – 76     | Reserved    | 76    | ₹100/month extra |
| 77 – 108   | Unreserved  | 32    | No extra  |

**Seat Grid:** 12 rows × 9 seats = 108 total

**Color coding on seat map:**
- 🟢 Green = Reserved seat, currently available
- 🔴 Red = Reserved seat, occupied
- ⬜ Gray = Unreserved seat, available
- 🟡 Yellow = Unreserved seat, occupied

---

## 💰 FEE STRUCTURE

| Fee Type         | Amount | Notes                    |
|------------------|--------|--------------------------|
| Monthly Fee      | ₹1,800 | Per month                |
| Reservation Fee  | ₹100   | Extra for reserved seat  |
| Registration Fee | ₹100   | One-time, non-refundable |
| Security Deposit | ₹300   | Refundable on leaving    |

All payments are **manual** — admin marks as paid in the system.

---

## 🔐 SECURITY FEATURES

- ✅ PDO prepared statements (SQL injection prevention)
- ✅ `password_hash()` / `password_verify()` for all passwords
- ✅ `htmlspecialchars()` on all output
- ✅ Session-based authentication
- ✅ Admin and student sessions are separate
- ✅ Auto-redirect to login if session missing
- ✅ Activity logging for all admin actions

---

## 📊 ADMIN DASHBOARD FEATURES

- **Stats:** Total seats, active students, reserved/unreserved counts, expiring soon, this month's revenue
- **Charts:** Bar chart (monthly revenue), Doughnut chart (seat status)
- **Alerts:** Banner if any renewals due within 7 days
- **Activity Log:** Last 8 admin actions

---

## 🔄 AUTOMATIC FEATURES

- **Auto-expire:** Students whose `renewal_date < today` are automatically marked `expired` on every page load (handled in `db.php`)
- **Seat freeing:** When admin marks student as "Left", their seat is automatically freed
- **Renewal date update:** Recording a monthly payment automatically extends `renewal_date` by 1 month

---

## 📱 STUDENT PORTAL FEATURES

Students log in with their **mobile number** + **password** (set by admin).

Students can see:
- Their seat number and type
- Membership expiry date and days remaining
- Payment history
- Library rules and timings
- Contact information

---

## 🛠️ COMMON ISSUES

| Issue | Solution |
|-------|----------|
| Blank page | Enable PHP error display — add `ini_set('display_errors', 1);` to `db.php` temporarily |
| DB connection error | Check credentials in `db.php`; InfinityFree uses a custom DB host |
| 500 error | Check `.htaccess` isn't blocking; InfinityFree has restrictions |
| Session not working | Some free hosts disable sessions — try a paid host for production |
| CSS not loading | Verify paths — make sure `assets/css/style.css` exists |

---

## 📞 CUSTOMIZATION CHECKLIST

Before going live, update these:
- [ ] Phone number in `index.php` (WhatsApp + Call buttons)
- [ ] Phone number in `student/dashboard.php`
- [ ] Google Maps embed URL in `index.php` (update location query)
- [ ] DB credentials in `includes/db.php`
- [ ] `SITE_URL` constant in `includes/db.php`
- [ ] Admin password (via Change Password page)
- [ ] Email in `admins` table (optional)

---

*Ekagra Abhyasika — City Center Complex, 3rd Floor, Office 304, Undri, Pune*