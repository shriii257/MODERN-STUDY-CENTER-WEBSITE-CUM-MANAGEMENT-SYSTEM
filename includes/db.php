<?php
// ============================================================
// EKAGRA ABHYASIKA - Database Connection (includes/db.php)
// ============================================================

// ---------------- DATABASE CONFIG ----------------
define('DB_HOST',    'sql201.infinityfree.com');
define('DB_NAME',    'if0_41864563_ekagra_db');
define('DB_USER',    'if0_41864563');
define('DB_PASS',    'oKomeUDESQyC2lq');
define('DB_CHARSET', 'utf8mb4');

// ---------------- WEBSITE CONFIG ----------------
define('SITE_NAME', 'Ekagra Abhyasika');

// Replace with your actual InfinityFree domain
define('SITE_URL', 'https://ekagraabhyasika.great-site.net');

// Replace with your real phone number
define('ADMIN_PHONE', '917000000000');

// Default credentials (for reference)
// Admin: username = admin
// Password: Admin@123

try {

    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET,
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]
    );

} catch (PDOException $e) {

    die('
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <title>Database Error</title>

        <style>

            body{
                font-family: Arial, sans-serif;
                background:#0a1628;
                color:#fff;
                display:flex;
                align-items:center;
                justify-content:center;
                min-height:100vh;
                margin:0;
                text-align:center;
            }

            .box{
                background:#0d2b6e;
                border-radius:16px;
                padding:48px 40px;
                max-width:500px;
                box-shadow:0 10px 30px rgba(0,0,0,0.3);
            }

            h2{
                color:#f0a500;
                margin-bottom:15px;
            }

            code{
                background:rgba(255,255,255,0.1);
                padding:4px 10px;
                border-radius:6px;
                font-size:13px;
            }

        </style>

    </head>

    <body>

        <div class="box">

            <h2>⚠️ Database Connection Failed</h2>

            <p>
                Please check your database settings in
                <code>includes/db.php</code>
            </p>

            <p>
                <small>' . htmlspecialchars($e->getMessage()) . '</small>
            </p>

        </div>

    </body>
    </html>
    ');
}

// ============================================================
// AUTO EXPIRE STUDENTS
// ============================================================

try {

    $pdo->exec("
        UPDATE students
        SET status='expired'
        WHERE renewal_date < CURDATE()
        AND status='active'
    ");

} catch (Exception $e) {

    // Silent fail during initial setup

}

// ============================================================
// HELPER FUNCTIONS
// ============================================================

// ---- Log Admin Activity ----
function logActivity(PDO $pdo, string $action, string $by, string $details = ''): void
{
    $stmt = $pdo->prepare("
        INSERT INTO activity_logs
        (action, performed_by, details)
        VALUES (?, ?, ?)
    ");

    $stmt->execute([$action, $by, $details]);
}

// ---- Escape Output ----
function e(string $str): string
{
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

// ---- Format Date ----
function fdate(?string $date, string $format = 'd M Y'): string
{
    if (!$date) {
        return '—';
    }

    return date($format, strtotime($date));
}

// ---- Redirect ----
function redirect(string $url): void
{
    header("Location: $url");
    exit;
}
?>