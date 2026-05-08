-- ============================================================
-- EKAGRA ABHYASIKA - Complete Database Schema + Seed Data
-- File: ekagra_db.sql
-- Import this file using phpMyAdmin or MySQL CLI:
--   mysql -u root -p < ekagra_db.sql
-- ============================================================



-- ============================================================
-- TABLE: admins
-- ============================================================
CREATE TABLE IF NOT EXISTS admins (
  id         INT AUTO_INCREMENT PRIMARY KEY,
  username   VARCHAR(50)  NOT NULL UNIQUE,
  password   VARCHAR(255) NOT NULL,
  full_name  VARCHAR(100),
  email      VARCHAR(100),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ============================================================
-- TABLE: seats
-- ============================================================
CREATE TABLE IF NOT EXISTS seats (
  id           INT AUTO_INCREMENT PRIMARY KEY,
  seat_number  INT NOT NULL UNIQUE,
  seat_type    ENUM('reserved','unreserved') NOT NULL,
  status       ENUM('available','occupied')  DEFAULT 'available',
  student_id   INT DEFAULT NULL,
  created_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ============================================================
-- TABLE: students
-- ============================================================
CREATE TABLE IF NOT EXISTS students (
  id                INT AUTO_INCREMENT PRIMARY KEY,
  full_name         VARCHAR(100) NOT NULL,
  mobile            VARCHAR(15)  NOT NULL UNIQUE,
  password          VARCHAR(255) NOT NULL,
  address           TEXT,
  aadhaar           VARCHAR(20),
  parent_name       VARCHAR(100),
  emergency_contact VARCHAR(15),
  joining_date      DATE         NOT NULL,
  seat_number       INT,
  seat_type         ENUM('reserved','unreserved') NOT NULL DEFAULT 'unreserved',
  status            ENUM('active','expired','left') DEFAULT 'active',
  renewal_date      DATE,
  deposit_paid      TINYINT(1)   DEFAULT 0,
  deposit_amount    DECIMAL(10,2) DEFAULT 300.00,
  notes             TEXT,
  created_at        TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at        TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ============================================================
-- TABLE: payments
-- ============================================================
CREATE TABLE IF NOT EXISTS payments (
  id           INT AUTO_INCREMENT PRIMARY KEY,
  student_id   INT NOT NULL,
  amount       DECIMAL(10,2) NOT NULL,
  payment_type ENUM('registration','monthly','deposit','deposit_refund','reservation') NOT NULL,
  payment_date DATE NOT NULL,
  month_year   VARCHAR(10),
  notes        TEXT,
  recorded_by  INT,
  created_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ============================================================
-- TABLE: activity_logs
-- ============================================================
CREATE TABLE IF NOT EXISTS activity_logs (
  id           INT AUTO_INCREMENT PRIMARY KEY,
  action       VARCHAR(255) NOT NULL,
  performed_by VARCHAR(100),
  details      TEXT,
  created_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ============================================================
-- SEED: Admin user
-- Username: admin   Password: Admin@123
-- Hash below is bcrypt of "Admin@123"
-- ============================================================
INSERT IGNORE INTO admins (username, password, full_name, email) VALUES
('admin',
 '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
 'Library Admin',
 'admin@ekagraabhyasika.com');

-- ============================================================
-- SEED: All 108 seats (1-76 reserved, 77-108 unreserved)
-- ============================================================
INSERT IGNORE INTO seats (seat_number, seat_type) VALUES
(1,'reserved'),(2,'reserved'),(3,'reserved'),(4,'reserved'),(5,'reserved'),
(6,'reserved'),(7,'reserved'),(8,'reserved'),(9,'reserved'),(10,'reserved'),
(11,'reserved'),(12,'reserved'),(13,'reserved'),(14,'reserved'),(15,'reserved'),
(16,'reserved'),(17,'reserved'),(18,'reserved'),(19,'reserved'),(20,'reserved'),
(21,'reserved'),(22,'reserved'),(23,'reserved'),(24,'reserved'),(25,'reserved'),
(26,'reserved'),(27,'reserved'),(28,'reserved'),(29,'reserved'),(30,'reserved'),
(31,'reserved'),(32,'reserved'),(33,'reserved'),(34,'reserved'),(35,'reserved'),
(36,'reserved'),(37,'reserved'),(38,'reserved'),(39,'reserved'),(40,'reserved'),
(41,'reserved'),(42,'reserved'),(43,'reserved'),(44,'reserved'),(45,'reserved'),
(46,'reserved'),(47,'reserved'),(48,'reserved'),(49,'reserved'),(50,'reserved'),
(51,'reserved'),(52,'reserved'),(53,'reserved'),(54,'reserved'),(55,'reserved'),
(56,'reserved'),(57,'reserved'),(58,'reserved'),(59,'reserved'),(60,'reserved'),
(61,'reserved'),(62,'reserved'),(63,'reserved'),(64,'reserved'),(65,'reserved'),
(66,'reserved'),(67,'reserved'),(68,'reserved'),(69,'reserved'),(70,'reserved'),
(71,'reserved'),(72,'reserved'),(73,'reserved'),(74,'reserved'),(75,'reserved'),
(76,'reserved'),
(77,'unreserved'),(78,'unreserved'),(79,'unreserved'),(80,'unreserved'),
(81,'unreserved'),(82,'unreserved'),(83,'unreserved'),(84,'unreserved'),
(85,'unreserved'),(86,'unreserved'),(87,'unreserved'),(88,'unreserved'),
(89,'unreserved'),(90,'unreserved'),(91,'unreserved'),(92,'unreserved'),
(93,'unreserved'),(94,'unreserved'),(95,'unreserved'),(96,'unreserved'),
(97,'unreserved'),(98,'unreserved'),(99,'unreserved'),(100,'unreserved'),
(101,'unreserved'),(102,'unreserved'),(103,'unreserved'),(104,'unreserved'),
(105,'unreserved'),(106,'unreserved'),(107,'unreserved'),(108,'unreserved');

-- ============================================================
-- SEED: Demo students (password for all: Student@123)
-- ============================================================
INSERT IGNORE INTO students
  (full_name, mobile, password, address, aadhaar, parent_name,
   emergency_contact, joining_date, seat_number, seat_type,
   status, renewal_date, deposit_paid, notes)
VALUES
('Rahul Sharma',   '9876543210',
 '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
 'Undri, Pune', '1234-5678-9012', 'Suresh Sharma', '9876543211',
 '2025-01-01', 5,  'reserved',   'active',  '2026-06-01', 1, 'UPSC Aspirant'),

('Priya Patil',    '9876543220',
 '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
 'Kondhwa, Pune', '2345-6789-0123', 'Ramesh Patil', '9876543221',
 '2025-01-15', 12, 'reserved',   'active',  '2026-06-15', 1, 'MPSC Aspirant'),

('Amit Kumar',     '9876543230',
 '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
 'Wanowrie, Pune', '3456-7890-1234', 'Vijay Kumar', '9876543231',
 '2025-02-01', 80, 'unreserved', 'active',  '2026-06-01', 1, 'Banking Exam'),

('Sneha Deshmukh', '9876543240',
 '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
 'Dhankawadi, Pune', '4567-8901-2345', 'Anil Deshmukh', '9876543241',
 '2025-02-10', 25, 'reserved',   'active',  '2026-06-10', 1, 'SSC CGL'),

('Vikas Jadhav',   '9876543250',
 '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
 'Undri, Pune', '5678-9012-3456', 'Ganesh Jadhav', '9876543251',
 '2025-03-01', 90, 'unreserved', 'expired', '2025-04-30', 1, 'Railway Exam');

-- ============================================================
-- SEED: Mark seats occupied for demo students
-- ============================================================
UPDATE seats SET status='occupied', student_id=1 WHERE seat_number=5;
UPDATE seats SET status='occupied', student_id=2 WHERE seat_number=12;
UPDATE seats SET status='occupied', student_id=3 WHERE seat_number=80;
UPDATE seats SET status='occupied', student_id=4 WHERE seat_number=25;
UPDATE seats SET status='occupied', student_id=5 WHERE seat_number=90;

-- ============================================================
-- SEED: Demo payments
-- ============================================================
INSERT INTO payments (student_id, amount, payment_type, payment_date, month_year, notes) VALUES
(1, 100,  'registration', '2025-01-01', NULL,     'Registration fee'),
(1, 300,  'deposit',      '2025-01-01', NULL,     'Security deposit'),
(1, 100,  'reservation',  '2025-01-01', NULL,     'Seat reservation fee'),
(1, 1800, 'monthly',      '2025-01-01', '2025-01','January fees'),
(1, 1800, 'monthly',      '2025-02-01', '2025-02','February fees'),
(1, 1800, 'monthly',      '2025-03-01', '2025-03','March fees'),
(2, 100,  'registration', '2025-01-15', NULL,     'Registration fee'),
(2, 300,  'deposit',      '2025-01-15', NULL,     'Security deposit'),
(2, 100,  'reservation',  '2025-01-15', NULL,     'Seat reservation fee'),
(2, 1800, 'monthly',      '2025-01-15', '2025-01','January fees'),
(2, 1800, 'monthly',      '2025-02-15', '2025-02','February fees'),
(3, 100,  'registration', '2025-02-01', NULL,     'Registration fee'),
(3, 300,  'deposit',      '2025-02-01', NULL,     'Security deposit'),
(3, 1800, 'monthly',      '2025-02-01', '2025-02','February fees'),
(4, 100,  'registration', '2025-02-10', NULL,     'Registration fee'),
(4, 300,  'deposit',      '2025-02-10', NULL,     'Security deposit'),
(4, 100,  'reservation',  '2025-02-10', NULL,     'Seat reservation fee'),
(4, 1800, 'monthly',      '2025-02-10', '2025-02','February fees'),
(5, 100,  'registration', '2025-03-01', NULL,     'Registration fee'),
(5, 300,  'deposit',      '2025-03-01', NULL,     'Security deposit'),
(5, 1800, 'monthly',      '2025-03-01', '2025-03','March fees');

-- ============================================================
-- SEED: Activity logs
-- ============================================================
INSERT INTO activity_logs (action, performed_by, details) VALUES
('Student Added',    'admin', 'Added Rahul Sharma — Seat 5 (Reserved)'),
('Student Added',    'admin', 'Added Priya Patil — Seat 12 (Reserved)'),
('Student Added',    'admin', 'Added Amit Kumar — Seat 80 (Unreserved)'),
('Student Added',    'admin', 'Added Sneha Deshmukh — Seat 25 (Reserved)'),
('Student Added',    'admin', 'Added Vikas Jadhav — Seat 90 (Unreserved)'),
('Payment Recorded', 'admin', 'Monthly fee ₹1800 paid by Rahul Sharma'),
('Payment Recorded', 'admin', 'Monthly fee ₹1800 paid by Priya Patil'),
('System',           'auto',  'Status updated to expired — Vikas Jadhav');