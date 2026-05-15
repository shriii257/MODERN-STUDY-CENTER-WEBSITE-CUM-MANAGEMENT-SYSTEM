<?php
ob_start();
require_once 'includes/db.php';

// Get live seat stats for homepage
$totalSeats = 108;
$occupied   = $pdo->query("SELECT COUNT(*) FROM seats WHERE status='occupied'")->fetchColumn();
$available  = $totalSeats - $occupied;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ekagra Abhyasika — Premium Study Library, Undri,Pune</title>
  <meta name="description" content="Ekagra Abhyasika — Premium private study library at Undri, Pune. AC study hall, individual cabins, 108 seats. Open 6AM–10PM, 7 days a week.">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@400;500;600;700;800&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
  <!-- Running Announcement Bar -->
<div class="announcement-bar">
  <div class="announcement-track">
    📢 Admissions Open at Ekagra Abhyasika • Reserved & Unreserved Seats Available • Premium AC Study Library in Undri Pune • Open Daily 6:00 AM – 10:00 PM
  </div>
</div>

<style>
.announcement-bar{
    width:100%;
    overflow:hidden;
    background:linear-gradient(90deg,#0d2b6e,#081631);
    color:#fff;
    padding:12px 0;
    position:sticky;
    top:0;
    z-index:99999;
    border-bottom:1px solid rgba(255,255,255,0.08);
    box-shadow:0 4px 14px rgba(0,0,0,0.18);
}

.announcement-track{
    white-space:nowrap;
    display:inline-block;
    padding-left:100%;
    font-size:14px;
    font-weight:700;
    letter-spacing:0.5px;
    animation:scrollAnnouncement 22s linear infinite;
}

@keyframes scrollAnnouncement{
    0%{
        transform:translateX(0);
    }
    100%{
        transform:translateX(-100%);
    }
}
</style>

<!-- Navigation -->
<nav class="public-nav">
  <a href="index.php" class="public-nav-brand">
    <div class="brand-box">EA</div>
    <h1>Ekagra Abhyasika<span>Study Library · Undri, Pune</span></h1>
  </a>
  <div class="public-nav-links">
    <a href="#about">About</a>
    <a href="#facilities">Facilities</a>
    <a href="#fees">Fees</a>
    <a href="#seats"><i class="fas fa-chair me-1"></i>Check Seats</a>
    <a href="#contact">Contact</a>
    <a href="student/login.php" class="btn-login"><i class="fas fa-sign-in-alt me-1"></i>Student Login</a>
  </div>
</nav>

<!-- ============================================================
     HERO SECTION
     ============================================================ -->
<section class="hero" id="home">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-lg-7 hero-content">
        <div class="hero-badge">
          <i class="fas fa-star"></i>Premium Study Environment
        </div>
        <h1>Your Path to<br><span>Success</span> Starts Here</h1>
        <p>A focused, peaceful study environment designed for serious aspirants. Individual cabins, AC hall, and world-class facilities — all under one roof in the heart of Undri, Pune.</p>
        <div class="hero-cta">
          <a href="#fees" class="btn-hero-primary">
            <i class="fas fa-chair"></i>Check Seat Plans
          </a>
          <a href="#contact" class="btn-hero-outline">
            <i class="fas fa-phone"></i>Contact Us
          </a>
        </div>
        <div class="hero-stats">
          <div class="hero-stat-item">
            <div class="hero-stat-value">108</div>
            <div class="hero-stat-label">Total Seats</div>
          </div>
          <div class="hero-stat-item">
            <div class="hero-stat-value"><?php echo $available; ?></div>
            <div class="hero-stat-label">Available Now</div>
          </div>
          <div class="hero-stat-item">
            <div class="hero-stat-value">16</div>
            <div class="hero-stat-label">Hours Open</div>
          </div>
          <div class="hero-stat-item">
            <div class="hero-stat-value">7</div>
            <div class="hero-stat-label">Days a Week</div>
          </div>
        </div>
      </div>

      <!-- Live seat availability card -->
      <div class="col-lg-5 d-none d-lg-block">
        <div style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.12);border-radius:20px;padding:32px;backdrop-filter:blur(12px);">
          <div class="text-center mb-4">
            <div style="font-family:var(--font-display);font-size:14px;color:var(--accent);letter-spacing:2px;text-transform:uppercase;margin-bottom:8px;">Live Availability</div>
            <div style="font-family:var(--font-display);font-size:52px;font-weight:800;color:#fff;line-height:1;">
              <?php echo $available; ?><span style="font-size:22px;color:rgba(255,255,255,0.5);">/108</span>
            </div>
            <div style="color:rgba(255,255,255,0.5);font-size:13px;margin-top:4px;">Seats Available Today</div>
          </div>
          <div style="background:rgba(255,255,255,0.06);border-radius:10px;padding:14px;margin-bottom:10px;">
            <div class="d-flex justify-content-between align-items-center">
              <span style="color:rgba(255,255,255,0.7);font-size:13px;">
                <i class="fas fa-lock me-2" style="color:var(--accent);"></i>Reserved Seats (1–76)
              </span>
              <span style="color:var(--accent);font-weight:700;font-size:15px;">76</span>
            </div>
          </div>
          <div style="background:rgba(255,255,255,0.06);border-radius:10px;padding:14px;margin-bottom:20px;">
            <div class="d-flex justify-content-between align-items-center">
              <span style="color:rgba(255,255,255,0.7);font-size:13px;">
                <i class="fas fa-door-open me-2" style="color:#66bb6a;"></i>Unreserved Seats (77–108)
              </span>
              <span style="color:#66bb6a;font-weight:700;font-size:15px;">32</span>
            </div>
          </div>
          <div style="background:rgba(26,183,89,0.15);border:1px solid rgba(26,183,89,0.3);border-radius:10px;padding:14px;text-align:center;">
            <div style="color:#66bb6a;font-size:13px;font-weight:700;">
              <i class="fas fa-clock me-2"></i>Open Today: 6:00 AM – 10:00 PM
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ============================================================
     ABOUT SECTION
     ============================================================ -->
<section id="about" style="background:#fff;">
  <div class="container">
    <div class="row align-items-center g-5">
      <div class="col-lg-6">
        <div class="section-tag"><i class="fas fa-book-open"></i> About Us</div>
        <h2 class="section-title">Ekagra Abhyasika —<br>Where Focus Meets Ambition</h2>
        <p class="section-sub">Designed for competitive exam aspirants, students, and professionals who need a dedicated, distraction-free environment to achieve their goals.</p>
        <div class="row g-3">
          <?php
          $highlights = [
            ['fas fa-map-marker-alt', 'Prime Location',    'City Center Complex, 3rd Floor, Office 304, Undri, Pune'],
            ['fas fa-calendar-check', 'Open All Week',     '365 days a year — including holidays & weekends'],
            ['fas fa-bolt',           '24hr Power Backup', 'Uninterrupted power supply — never lose study time'],
            ['fas fa-shield-alt',     'CCTV Security',     'Round-the-clock CCTV for your safety & peace of mind'],
          ];
          foreach ($highlights as $h): ?>
          <div class="col-md-6">
            <div style="display:flex;align-items:flex-start;gap:14px;padding:16px;background:var(--bg-page);border-radius:var(--radius);border:1px solid var(--border);">
              <div style="width:40px;height:40px;background:rgba(13,43,110,0.08);border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:16px;color:var(--primary);flex-shrink:0;">
                <i class="<?php echo $h[0]; ?>"></i>
              </div>
              <div>
                <div style="font-weight:700;font-size:14px;color:var(--primary);margin-bottom:2px;"><?php echo $h[1]; ?></div>
                <div style="font-size:12px;color:var(--text-muted);line-height:1.5;"><?php echo $h[2]; ?></div>
              </div>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
      <div class="col-lg-6">
        <div style="background:linear-gradient(135deg,var(--primary-dark),var(--primary));border-radius:var(--radius-lg);padding:40px;color:#fff;position:relative;overflow:hidden;">
          <div style="position:absolute;right:-40px;bottom:-40px;width:200px;height:200px;background:rgba(255,255,255,0.04);border-radius:50%;"></div>
          <h3 style="font-family:var(--font-display);font-size:22px;font-weight:800;color:var(--accent);margin-bottom:24px;">
            <i class="fas fa-clock me-2"></i>Library Timings
          </h3>
          <div style="display:flex;align-items:center;gap:16px;margin-bottom:16px;padding:16px;background:rgba(255,255,255,0.06);border-radius:10px;">
            <i class="fas fa-sun" style="font-size:24px;color:#ffd700;flex-shrink:0;"></i>
            <div><div style="font-weight:700;">Morning Opening</div><div style="color:rgba(255,255,255,0.6);font-size:13px;">6:00 AM every day</div></div>
          </div>
          <div style="display:flex;align-items:center;gap:16px;margin-bottom:28px;padding:16px;background:rgba(255,255,255,0.06);border-radius:10px;">
            <i class="fas fa-moon" style="font-size:24px;color:#a78bfa;flex-shrink:0;"></i>
            <div><div style="font-weight:700;">Evening Closing</div><div style="color:rgba(255,255,255,0.6);font-size:13px;">10:00 PM every day</div></div>
          </div>
          <div style="background:rgba(240,165,0,0.15);border:1px solid rgba(240,165,0,0.3);border-radius:10px;padding:14px;text-align:center;">
            <div style="color:var(--accent);font-weight:700;font-size:15px;"><i class="fas fa-infinity me-2"></i>Open 365 Days a Year</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ============================================================
     FACILITIES SECTION
     ============================================================ -->
<section id="facilities" style="background:var(--bg-page);">
  <div class="container">
    <div class="text-center mb-5">
      <div class="section-tag"><i class="fas fa-star"></i> Facilities</div>
      <h2 class="section-title">World-Class Amenities</h2>
      <p class="section-sub mx-auto">Everything you need to study effectively — all included in your membership.</p>
    </div>
    <div class="row g-4">
      <?php
      $facilities = [
        ['fas fa-tint',       'RO Drinking Water',    'Pure RO-filtered drinking water available throughout the day at no extra cost.'],
        ['fas fa-snowflake',  'AC Reading Hall',      'Fully air-conditioned hall maintains perfect temperature for focused studying.'],
        ['fas fa-video',      'CCTV Security',        '24/7 surveillance cameras ensure the safety of students and their belongings.'],
        ['fas fa-bolt',       '24hr Power Backup',    'Never lose your study session — uninterrupted power supply is always on.'],
        ['fas fa-user-shield','Individual Cabins',    'Private study cabins for maximum focus and personal space away from distractions.'],
        ['fas fa-volume-mute','Silence Zone',         'Strict silence maintained — ideal for deep concentration and exam preparation.'],
      ];
      foreach ($facilities as $f): ?>
      <div class="col-md-6 col-lg-4">
        <div class="facility-card">
          <div class="facility-icon"><i class="<?php echo $f[0]; ?>"></i></div>
          <h5><?php echo $f[1]; ?></h5>
          <p><?php echo $f[2]; ?></p>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ============================================================
     FEE STRUCTURE SECTION
     ============================================================ -->
<section id="fees" style="background:#fff;">
  <div class="container">
    <div class="text-center mb-5">
      <div class="section-tag"><i class="fas fa-tags"></i> Fee Structure</div>
      <h2 class="section-title">Simple, Transparent Pricing</h2>
      <p class="section-sub mx-auto">No hidden charges. All payments are made directly at the library. No online payment required.</p>
    </div>
    <div class="row g-4 justify-content-center">

      <!-- Unreserved Plan -->
      <div class="col-md-6 col-lg-4">
        <div class="fee-card h-100">
          <div class="fee-tag">Monthly — Unreserved</div>
          <div class="fee-price"><sup>₹</sup>1800<small>/month</small></div>
          <hr style="margin:20px 0;border-color:var(--border);">
          <ul style="list-style:none;padding:0;margin:0;">
            <?php foreach (['Access to full library','AC reading hall','RO water included','CCTV security','24hr power backup','Any available seat (unreserved)'] as $item): ?>
            <li style="padding:7px 0;font-size:14px;color:var(--text-muted);display:flex;align-items:center;gap:10px;">
              <i class="fas fa-check-circle" style="color:var(--success);"></i><?php echo $item; ?>
            </li>
            <?php endforeach; ?>
          </ul>
        </div>
      </div>

      <!-- Reserved Plan (featured) -->
      <div class="col-md-6 col-lg-4">
        <div class="fee-card featured h-100" style="position:relative;">
          <div style="position:absolute;top:-12px;right:20px;background:var(--accent);color:var(--primary-dark);padding:4px 14px;border-radius:20px;font-size:11px;font-weight:800;text-transform:uppercase;letter-spacing:1px;"></div>
          <div class="fee-tag">Monthly — Reserved</div>
          <div class="fee-price"><sup>₹</sup>1900<small>/month</small></div>
          <p style="font-size:12px;color:rgba(255,255,255,0.5);margin-top:4px;">₹1800 monthly + ₹100 seat reservation</p>
          <hr style="margin:20px 0;border-color:rgba(255,255,255,0.15);">
          <ul style="list-style:none;padding:0;margin:0;">
            <?php foreach (['Your own fixed seat number','No one else can use your seat','All unreserved plan benefits','₹100 extra for seat reservation','Guaranteed seat every visit','Peace of mind'] as $item): ?>
            <li style="padding:7px 0;font-size:14px;color:rgba(255,255,255,0.75);display:flex;align-items:center;gap:10px;">
              <i class="fas fa-check-circle" style="color:var(--accent);"></i><?php echo $item; ?>
            </li>
            <?php endforeach; ?>
          </ul>
        </div>
      </div>

      <!-- One-time fees -->
      <div class="col-md-6 col-lg-4">
        <div class="fee-card h-100">
          <div class="fee-tag">One-time Charges</div>
          <div style="margin-bottom:20px;">
            <div style="display:flex;justify-content:space-between;align-items:center;padding:12px 0;border-bottom:1px solid var(--border);">
              <span style="font-size:14px;font-weight:600;">Registration Fee</span>
              <span style="font-weight:800;color:var(--primary);font-size:18px;">₹100</span>
            </div>
            <div style="font-size:12px;color:var(--text-muted);padding:6px 0 14px;border-bottom:1px solid var(--border);">Non-refundable · paid once at joining</div>
            <div style="display:flex;justify-content:space-between;align-items:center;padding:12px 0;border-bottom:1px solid var(--border);">
              <span style="font-size:14px;font-weight:600;">Security Deposit</span>
              <span style="font-weight:800;color:var(--primary);font-size:18px;">₹300</span>
            </div>
            <div style="font-size:12px;color:var(--text-muted);padding:6px 0 14px;">Fully refundable when you leave</div>
          </div>
          <div style="background:rgba(26,183,89,0.08);border:1px solid rgba(26,183,89,0.2);border-radius:10px;padding:14px;text-align:center;">
            <div style="font-size:13px;font-weight:700;color:var(--success);">
              <i class="fas fa-shield-alt me-2"></i>Deposit Fully Refundable
            </div>
          </div>
        </div>
      </div>

    </div>
    <div class="text-center mt-4">
      <p style="color:var(--text-muted);font-size:13px;">
        <i class="fas fa-info-circle me-2 text-primary"></i>
        All payments are collected directly at the library. No online payment required.
      </p>
    </div>
  </div>
</section>


<!-- ============================================================
     SEAT AVAILABILITY SECTION
     ============================================================ -->
<section id="seats" style="background:var(--bg-page);padding:60px 0;">
<style>
/* ── Stat pills ── */
.pub-stats-row{display:flex;gap:8px;flex-wrap:wrap;justify-content:center;margin-bottom:24px;}
.pub-stat-pill{display:flex;align-items:center;gap:8px;background:#fff;border-radius:50px;padding:8px 16px;box-shadow:0 2px 8px rgba(0,0,0,.07);font-size:13px;font-weight:700;border:1.5px solid transparent;}
.pub-stat-pill.total {border-color:#0d2b6e;color:#0d2b6e;}
.pub-stat-pill.res   {border-color:#66bb6a;color:#2e7d32;}
.pub-stat-pill.unres {border-color:#bdbdbd;color:#555;}
.pub-stat-pill.occ   {border-color:#ef9a9a;color:#c62828;}
.pub-stat-pill .pill-num{font-size:18px;font-weight:900;line-height:1;}

/* ── Room wrapper ── */
.room-wrap{
  background:#fff;
  border-radius:16px;
  box-shadow:0 2px 20px rgba(0,0,0,.08);
  padding:16px;
  overflow-x:auto;
  max-width:700px;
  margin:0 auto;
}
.room-inner{
  min-width:280px;
  position:relative;
}

/* ── Door label ── */
.door-label{
  text-align:center;
  font-size:11px;font-weight:700;letter-spacing:1px;
  color:#aaa;text-transform:uppercase;
  margin-bottom:6px;
  display:flex;align-items:center;justify-content:center;gap:6px;
}
.door-label::before,.door-label::after{content:'';flex:1;height:1px;background:#e0e0e0;}

/* ── Cabin block (pair of facing rows) ── */
.cabin-block{
  display:grid;
  grid-template-columns:1fr 4px 1fr;
  gap:0 6px;
  margin-bottom:3px;
}
.cabin-block.has-aisle{margin-bottom:14px;}

/* aisle divider */
.aisle-div{width:4px;background:rgba(0,0,0,0.06);border-radius:4px;margin:2px 0;}

/* ── Seat row ── */
.seat-row{display:flex;gap:3px;justify-content:flex-end;}
.seat-row.right{justify-content:flex-start;}

/* ── Single seat ── */
.pub-seat{
  width:36px;height:36px;
  border-radius:6px;
  display:flex;flex-direction:column;
  align-items:center;justify-content:center;
  font-size:9px;font-weight:800;
  cursor:pointer;
  border:1.5px solid transparent;
  transition:transform .12s,box-shadow .12s;
  line-height:1;
  gap:1px;
  position:relative;
}
.pub-seat:hover{transform:scale(1.15);box-shadow:0 4px 12px rgba(0,0,0,.22);z-index:5;}
.pub-seat i{font-size:11px;}

.pub-seat.res-avail   {background:#e8f5e9;border-color:#66bb6a;color:#2e7d32;}
.pub-seat.res-occ     {background:#ffebee;border-color:#ef5350;color:#c62828;cursor:not-allowed;}
.pub-seat.unres-avail {background:#f5f5f5;border-color:#bdbdbd;color:#555;}
.pub-seat.unres-occ   {background:#fff8e1;border-color:#ffca28;color:#e65100;cursor:not-allowed;}

/* ── Window label ── */
.window-label{
  font-size:10px;font-weight:700;color:#aaa;letter-spacing:1px;
  text-transform:uppercase;text-align:center;
  padding:8px 0 4px;
  border-top:1px dashed #e0e0e0;
  border-bottom:1px dashed #e0e0e0;
  margin:6px 0;
}

/* ── AC labels ── */
.ac-row{display:grid;grid-template-columns:1fr 4px 1fr;gap:0 6px;margin-top:10px;}
.ac-label{background:#e3f0ff;border-radius:8px;text-align:center;padding:6px;font-size:10px;font-weight:800;color:#0d2b6e;letter-spacing:.5px;}

/* ── Legend ── */
.pub-legend{display:flex;flex-wrap:wrap;gap:10px;justify-content:center;margin-bottom:16px;}
.pub-legend-item{display:flex;align-items:center;gap:5px;font-size:11px;font-weight:600;color:#555;}
.pub-legend-dot{width:12px;height:12px;border-radius:3px;border:1.5px solid transparent;}
.pub-legend-dot.g{background:#e8f5e9;border-color:#66bb6a;}
.pub-legend-dot.r{background:#ffebee;border-color:#ef5350;}
.pub-legend-dot.gy{background:#f5f5f5;border-color:#bdbdbd;}
.pub-legend-dot.y{background:#fff8e1;border-color:#ffca28;}

@media(max-width:420px){
  .pub-seat{width:30px;height:30px;font-size:8px;}
  .pub-seat i{font-size:9px;}
  .room-wrap{padding:10px;}
}
</style>

  <div class="container">
    <div class="text-center mb-4">
      <div class="section-tag"><i class="fas fa-chair"></i> Live Seat Status</div>
      <h2 class="section-title">Check Available Seats</h2>
      <p class="section-sub mx-auto" style="font-size:14px;">Tap any seat to request it from the admin</p>
    </div>

    <?php
    $pubSeats = $pdo->query("SELECT seat_number, seat_type, status FROM seats ORDER BY seat_number")->fetchAll();
    $seatMap = [];
    foreach ($pubSeats as $ps) $seatMap[$ps['seat_number']] = $ps;

    $resOcc = $unresOcc = 0;
    foreach ($pubSeats as $ps) {
        if ($ps['seat_type']==='reserved'   && $ps['status']==='occupied') $resOcc++;
        if ($ps['seat_type']==='unreserved' && $ps['status']==='occupied') $unresOcc++;
    }
    $resAvail   = 76 - $resOcc;
    $unresAvail = 32 - $unresOcc;
    $totalAvail = $resAvail + $unresAvail;
    $totalOcc   = $resOcc + $unresOcc;

    // Helper: render a single seat
    function pubSeat($seatMap, $n) {
        if (!isset($seatMap[$n])) return "<div class='pub-seat unres-avail'><i class='fas fa-chair'></i>$n</div>";
        $s    = $seatMap[$n];
        $occ  = $s['status'] === 'occupied';
        $res  = $s['seat_type'] === 'reserved';
        $cls  = $res ? ($occ ? 'res-occ' : 'res-avail') : ($occ ? 'unres-occ' : 'unres-avail');
        $icon = $occ ? 'fa-user' : 'fa-chair';
        $data = htmlspecialchars(json_encode(['seat_number'=>$n,'seat_type'=>ucfirst($s['seat_type']),'status'=>$s['status']]));
        return "<div class='pub-seat $cls' data-seat='$data' onclick='openPubSeatModal(this)' title='Seat $n'>
                  <i class='fas $icon'></i>$n
                </div>";
    }

    // Helper: render a row of seats
    function pubRow($seatMap, $nums, $dir='left') {
        $cls = $dir==='right' ? 'seat-row right' : 'seat-row';
        $html = "<div class='$cls'>";
        foreach ($nums as $n) $html .= pubSeat($seatMap, $n);
        $html .= "</div>";
        return $html;
    }
    ?>

    <!-- Stats pills -->
    <div class="pub-stats-row">
      <div class="pub-stat-pill total"><span class="pill-num"><?php echo $totalAvail; ?></span> Available</div>
      <div class="pub-stat-pill res"><span class="pill-num"><?php echo $resAvail; ?></span> Reserved Free</div>
      <div class="pub-stat-pill unres"><span class="pill-num"><?php echo $unresAvail; ?></span> Unreserved Free</div>
      <div class="pub-stat-pill occ"><span class="pill-num"><?php echo $totalOcc; ?></span> Occupied</div>
    </div>

    <!-- Legend -->
    <div class="pub-legend">
      <div class="pub-legend-item"><div class="pub-legend-dot g"></div>Reserved – Free</div>
      <div class="pub-legend-item"><div class="pub-legend-dot r"></div>Reserved – Taken</div>
      <div class="pub-legend-item"><div class="pub-legend-dot gy"></div>Unreserved – Free</div>
      <div class="pub-legend-item"><div class="pub-legend-dot y"></div>Unreserved – Taken</div>
    </div>

    <!-- Room map -->
    <div class="room-wrap">
      <div class="room-inner">

        <!-- Door -->
        <div class="door-label"><i class="fas fa-door-open"></i> DOOR / ENTRANCE</div>

        <!-- Block 1: Seats 1–18 & 5–14 -->
        <div class="cabin-block">
          <div>
            <?php echo pubRow($seatMap,[1,2,3,4],'left'); ?>
            <?php echo pubRow($seatMap,[18,17,16,15],'left'); ?>
          </div>
          <div class="aisle-div"></div>
          <div>
            <?php echo pubRow($seatMap,[5,6,7,8,9],'right'); ?>
            <?php echo pubRow($seatMap,[14,13,12,11,10],'right'); ?>
          </div>
        </div>

        <!-- Block 2: Seats 19–34 & 23–32 -->
        <div class="cabin-block">
          <div>
            <?php echo pubRow($seatMap,[19,20,21,22],'left'); ?>
            <?php echo pubRow($seatMap,[33,34,35,36],'left'); ?>
          </div>
          <div class="aisle-div"></div>
          <div>
            <?php echo pubRow($seatMap,[23,24,25,26,27],'right'); ?>
            <?php echo pubRow($seatMap,[32,31,30,29,28],'right'); ?>
          </div>
        </div>

        <!-- Block 3: Seats 37–54 & 41–50 -->
        <div class="cabin-block">
          <div>
            <?php echo pubRow($seatMap,[37,38,39,40],'left'); ?>
            <?php echo pubRow($seatMap,[54,53,52,51],'left'); ?>
          </div>
          <div class="aisle-div"></div>
          <div>
            <?php echo pubRow($seatMap,[41,42,43,44,45],'right'); ?>
            <?php echo pubRow($seatMap,[50,49,48,47,46],'right'); ?>
          </div>
        </div>

        <!-- Block 4: Seats 55–72 & 59–68 -->
        <div class="cabin-block has-aisle">
          <div>
            <?php echo pubRow($seatMap,[55,56,57,58],'left'); ?>
            <?php echo pubRow($seatMap,[72,71,70,69],'left'); ?>
          </div>
          <div class="aisle-div"></div>
          <div>
            <?php echo pubRow($seatMap,[59,60,61,62,63],'right'); ?>
            <?php echo pubRow($seatMap,[68,67,66,65,64],'right'); ?>
          </div>
        </div>

        <!-- Window divider -->
        <div class="window-label"><i class="fas fa-wind me-1"></i> WINDOW / AISLE</div>

        <!-- Block 5: Seats 73–90 & 77–86 -->
        <div class="cabin-block">
          <div>
            <?php echo pubRow($seatMap,[73,74,75,76],'left'); ?>
            <?php echo pubRow($seatMap,[90,89,88,87],'left'); ?>
          </div>
          <div class="aisle-div"></div>
          <div>
            <?php echo pubRow($seatMap,[77,78,79,80,81],'right'); ?>
            <?php echo pubRow($seatMap,[86,85,84,83,82],'right'); ?>
          </div>
        </div>

        <!-- Block 6: Seats 91–108 & 95–104 -->
        <div class="cabin-block">
          <div>
            <?php echo pubRow($seatMap,[91,92,93,94],'left'); ?>
            <?php echo pubRow($seatMap,[108,107,106,105],'left'); ?>
          </div>
          <div class="aisle-div"></div>
          <div>
            <?php echo pubRow($seatMap,[95,96,97,98,99],'right'); ?>
            <?php echo pubRow($seatMap,[104,103,102,101,100],'right'); ?>
          </div>
        </div>

        <!-- AC labels -->
        <div class="ac-row">
          <div class="ac-label"><i class="fas fa-snowflake me-1"></i>AC</div>
          <div></div>
          <div class="ac-label"><i class="fas fa-snowflake me-1"></i>Air Conditioner</div>
        </div>

      </div>
    </div><!-- /room-wrap -->

    <p class="text-center mt-3" style="font-size:12px;color:#aaa;">
      <i class="fas fa-sync-alt me-1"></i>Refreshes on page load · Tap an available seat to contact admin
    </p>

  </div>
</section>

<!-- ── Seat Request Modal ── -->
<div class="modal fade" id="pubSeatModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="border-radius:16px;border:none;box-shadow:0 20px 60px rgba(0,0,0,.18);">
      <div class="modal-header" style="border-bottom:1px solid #f0f0f0;padding:18px 20px;">
        <h5 class="modal-title" style="font-size:15px;"><i class="fas fa-chair me-2" style="color:#0d2b6e;"></i>Seat Request</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body" style="padding:20px;">
        <div id="pubModalAvailable">
          <p style="font-size:13px;color:#555;margin-bottom:10px;">You selected:</p>
          <div style="display:flex;align-items:center;gap:12px;margin-bottom:16px;">
            <span id="pubModalSeatNo" style="background:#0d2b6e;color:#fff;border-radius:8px;padding:4px 14px;font-weight:900;font-family:'Rajdhani',sans-serif;font-size:22px;">–</span>
            <div>
              <div id="pubModalSeatType" style="font-weight:700;font-size:14px;"></div>
              <div style="font-size:12px;color:#1ab759;font-weight:600;"><i class="fas fa-circle" style="font-size:7px;"></i> Available</div>
            </div>
          </div>
          <p style="font-size:13px;color:#666;margin-bottom:16px;">Contact admin with your name and seat number to confirm admission.</p>
          <a id="pubWaLink" href="#" target="_blank"
             style="background:#25d366;color:#fff;border-radius:10px;padding:11px 20px;font-weight:700;font-size:14px;display:flex;align-items:center;justify-content:center;gap:8px;text-decoration:none;margin-bottom:8px;">
            <i class="fab fa-whatsapp fa-lg"></i> Request via WhatsApp
          </a>
          <a href="tel:+919579089287"
             style="background:#0d2b6e;color:#fff;border-radius:10px;padding:11px 20px;font-weight:700;font-size:14px;display:flex;align-items:center;justify-content:center;gap:8px;text-decoration:none;">
            <i class="fas fa-phone"></i> Call Admin
          </a>
        </div>
        <div id="pubModalOccupied" style="display:none;text-align:center;padding:8px 0;">
          <i class="fas fa-user-times" style="font-size:36px;color:#ef5350;margin-bottom:12px;"></i>
          <div style="font-weight:700;font-size:16px;margin-bottom:6px;">Seat <span id="pubModalOccNo"></span> is Occupied</div>
          <p style="font-size:13px;color:#888;margin-bottom:16px;">Choose another available seat from the map.</p>
          <a id="pubWaAnyLink" href="#" target="_blank"
             style="background:#25d366;color:#fff;border-radius:10px;padding:11px 20px;font-weight:700;font-size:14px;display:flex;align-items:center;justify-content:center;gap:8px;text-decoration:none;">
            <i class="fab fa-whatsapp fa-lg"></i> Ask Admin for Any Seat
          </a>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
(function(){
  var ADMIN = '919579089287';
  window.openPubSeatModal = function(el){
    var d = JSON.parse(el.dataset.seat);
    var m = new bootstrap.Modal(document.getElementById('pubSeatModal'));
    if(d.status==='available'){
      document.getElementById('pubModalAvailable').style.display='';
      document.getElementById('pubModalOccupied').style.display='none';
      document.getElementById('pubModalSeatNo').textContent='Seat '+d.seat_number;
      document.getElementById('pubModalSeatType').textContent=d.seat_type+' Seat';
      var msg=encodeURIComponent('Hello! I want to book Seat No. '+d.seat_number+' ('+d.seat_type+') at Ekagra Abhyasika. Please guide me on the admission process.');
      document.getElementById('pubWaLink').href='https://wa.me/'+ADMIN+'?text='+msg;
    } else {
      document.getElementById('pubModalAvailable').style.display='none';
      document.getElementById('pubModalOccupied').style.display='';
      document.getElementById('pubModalOccNo').textContent=d.seat_number;
      var msg2=encodeURIComponent('Hello! I am looking for an available seat at Ekagra Abhyasika. Can you help?');
      document.getElementById('pubWaAnyLink').href='https://wa.me/'+ADMIN+'?text='+msg2;
    }
    m.show();
  };
})();
</script>

<!-- ============================================================
     CONTACT SECTION
     ============================================================ -->
<section class="contact-section" id="contact">
  <div class="container">
    <div class="row align-items-start g-5">
      <div class="col-lg-5">
        <div class="section-tag" style="background:rgba(255,255,255,0.1);color:var(--accent);">
          <i class="fas fa-phone"></i> Contact Us
        </div>
        <h2 class="section-title">Visit Us Today</h2>
        <p class="section-sub">We'd love to show you around. Come visit, see the facilities, and enroll today.</p>

        <div class="contact-info-item">
          <div class="contact-info-icon"><i class="fas fa-map-marker-alt"></i></div>
          <div>
            <h6>Address</h6>
            <p>City Center Complex, 3rd Floor, Office No 304,<br>Undri, Pune, Maharashtra, India</p>
          </div>
        </div>
        <div class="contact-info-item">
          <div class="contact-info-icon"><i class="fas fa-clock"></i></div>
          <div>
            <h6>Working Hours</h6>
            <p>Monday to Sunday<br>6:00 AM – 10:00 PM</p>
          </div>
        </div>
        <div class="contact-info-item">
          <div class="contact-info-icon"><i class="fab fa-whatsapp"></i></div>
          <div>
            <h6>WhatsApp / Call</h6>
            <p><a href="tel:+919579089287" style="color:var(--accent);text-decoration:none;font-weight:700;">+91 95790 89287</a></p>
          </div>
        </div>

        <div class="d-flex gap-3 mt-4">
          <a href="tel:+919579089287" class="btn btn-accent" style="flex:1;text-align:center;">
            <i class="fas fa-phone me-2"></i>Call Now
          </a>
          <a href="https://wa.me/919579089287?text=Hello%2C+I+want+to+know+about+Ekagra+Abhyasika" target="_blank"
             class="btn" style="flex:1;text-align:center;background:#25d366;color:#fff;">
            <i class="fab fa-whatsapp me-2"></i>WhatsApp
          </a>
        </div>
      </div>

      <div class="col-lg-7">
        <div style="border-radius:var(--radius-lg);overflow:hidden;box-shadow:0 8px 40px rgba(0,0,0,0.4);">
          <iframe

    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3424.8700149238466!2d73.91035957465067!3d18.456255071136766!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3bc2ebc27511fefd%3A0x4ef4effaff2598a5!2sEKAGRA%20ABHYASHIKA%20(Library)!5e1!3m2!1sen!2sin!4v1778398953077!5m2!1sen!2sin"
    width="100%"
    height="420"
    style="border:0;display:block;"
    allowfullscreen
    loading="lazy"
    referrerpolicy="no-referrer-when-downgrade">
          </iframe>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ============================================================
     LIBRARY RULES SECTION
     ============================================================ -->
<section style="background:#fff;">
  <div class="container">
    <div class="text-center mb-5">
      <div class="section-tag"><i class="fas fa-gavel"></i> Library Rules</div>
      <h2 class="section-title">Code of Conduct</h2>
      <p class="section-sub mx-auto">Please follow these rules to maintain a peaceful environment for all students.</p>
    </div>
    <div class="row g-3">
      <?php
      $rules = [
        'Maintain complete silence inside the library at all times',
        'Mobile phones must be kept on silent or vibrate mode',
        'Do not disturb or distract other students',
        'Keep your study area clean and tidy',
        'Personal belongings are solely your own responsibility',
        'Reserved seats cannot be used by other students',
        'Entry is permitted only during library hours (6 AM–10 PM)',
        'Food and beverages are not allowed inside the reading hall',
        'ID card must be carried at all times',
        'Violation of rules may result in membership cancellation',
      ];
      foreach ($rules as $i => $rule): ?>
      <div class="col-md-6">
        <div style="display:flex;align-items:center;gap:12px;padding:14px 16px;background:var(--bg-page);border-radius:var(--radius);border:1px solid var(--border);">
          <span style="width:28px;height:28px;background:var(--primary);color:#fff;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:800;flex-shrink:0;"><?php echo $i + 1; ?></span>
          <span style="font-size:14px;"><?php echo e($rule); ?></span>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Footer -->
<footer class="site-footer">
  <div class="container">
    <p>© <?php echo date('Y'); ?> Ekagra Abhyasika. All Rights Reserved.</p>
    <p style="margin-top:4px;font-size:12px;color:rgba(255,255,255,0.3);">
      City Center Complex, 3rd Floor, Office No 304, Undri, Pune, Maharashtra, India
      &nbsp;|&nbsp;
      <a href="admin/login.php" style="color:rgba(255,255,255,0.2);text-decoration:none;">Admin</a>
    </p>
  </div>
</footer>

<!-- Floating Buttons -->
<div class="floating-btns">
  <a href="https://wa.me/919579089287?text=Hello%2C+I+want+to+know+about+Ekagra+Abhyasika"
     target="_blank" class="float-btn wa" title="WhatsApp Us">
    <i class="fab fa-whatsapp"></i>
  </a>
  <a href="tel:+919579089287" class="float-btn call" title="Call Us">
    <i class="fas fa-phone"></i>
  </a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/main.js"></script>
</body>
</html>
