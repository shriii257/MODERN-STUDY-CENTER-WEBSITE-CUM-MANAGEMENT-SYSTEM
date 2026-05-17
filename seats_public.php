<?php
ob_start();
require_once 'includes/db.php';

// Only reserved students lock physical seats — fetch occupied reserved seats
$pubSeats = $pdo->query("SELECT seat_number, seat_type, status FROM seats WHERE status='occupied' ORDER BY seat_number")->fetchAll();
$seatMap  = [];
foreach ($pubSeats as $ps) $seatMap[$ps['seat_number']] = $ps;

// Counts from students table (source of truth)
$reservedTotal    = 75;  // guideline capacity (~70%)
$unreservedTotal  = 32;  // guideline capacity (~30%)
$reservedTaken    = $pdo->query("SELECT COUNT(*) FROM students WHERE seat_type='reserved' AND status='active'")->fetchColumn();
$unreservedActive = $pdo->query("SELECT COUNT(*) FROM students WHERE seat_type='unreserved' AND status='active'")->fetchColumn();
$reservedFree     = $reservedTotal - $reservedTaken;
$totalStudents    = $reservedTaken + $unreservedActive;

function pubSeat($seatMap, $n) {
    if ($n === null) {
        // Blank physical space — not a seat
        return "<div class='pub-seat pub-blank'></div>";
    }
    $occ = isset($seatMap[$n]) && $seatMap[$n]['status'] === 'occupied';
    $cls  = $occ ? 'res-occ' : 'res-avail';
    $icon = $occ ? 'fa-user' : 'fa-chair';
    if ($occ) {
        // Occupied reserved seat — no clickable action
        return "<div class='pub-seat $cls' title='Seat $n — Reserved'>
                  <i class='fas $icon'></i>$n
                </div>";
    }
    // Free seat — clickable to enquire
    $data = htmlspecialchars(json_encode(['seat_number' => $n]));
    return "<div class='pub-seat $cls' data-seat='$data' onclick='openModal(this)' title='Seat $n — Available'>
              <i class='fas $icon'></i>$n
            </div>";
}

// Physical layout: exact match to room diagram
// null = blank physical space (not a seat, not counted)
$rows = [
    [[1,2,3,4],           [5,6,7,8]],
    [[9,10,11,12],        [13,14,15,16,17]],
    [[18,19,20,21],       [22,23,24,25,26]],
    [[27,28,29,30],       [31,32,33,34,35]],
    [[36,37,38,39],       [40,41,42,43,44]],
    [[null,52,51,50],     [49,48,47,46,45]],  // null = blank, not a seat
    [[53,54,55,56],       [57,58,59,60,61]],
    [[70,69,68,67],       [66,65,64,63,62]],
    [[71,72,73,74],       [75,76,77,78,79]],
    [[88,87,86,85],       [84,83,82,81,80]],
    [[89,90,91,92],       [93,94,95,96,97]],
    [[107,106,105,104],   [103,102,101,100,99,98]],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Seat Availability — Ekagra Abhyasika</title>
  <meta name="description" content="Live seat availability at Ekagra Abhyasika Study Library, Undri Pune.">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@400;600;700;800&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/style.css">
  <style>
    body { background: var(--bg-page, #f4f6fb); }
    .seat-hero { background:linear-gradient(135deg,#1a7a2e 0%,#27a13e 100%); color:#fff; padding:48px 0 32px; text-align:center; }
    .seat-hero h1 { font-family:'Rajdhani',sans-serif; font-size:clamp(26px,6vw,42px); font-weight:800; margin:0 0 8px; }
    .seat-hero p  { font-size:14px; opacity:.75; margin:0; }

    .live-badge { display:inline-flex; align-items:center; gap:6px; background:rgba(26,183,89,0.18); border:1px solid rgba(26,183,89,0.35); border-radius:50px; padding:4px 14px; font-size:11px; font-weight:700; color:#4ade80; margin-bottom:16px; }
    .live-dot   { width:7px; height:7px; border-radius:50%; background:#4ade80; animation:blink 1.4s infinite; }
    @keyframes blink { 0%,100%{opacity:1;transform:scale(1);} 50%{opacity:.3;transform:scale(1.5);} }

    .pub-stats-row { display:flex; gap:8px; flex-wrap:wrap; justify-content:center; margin-bottom:20px; }
    .pub-stat-pill { display:flex; align-items:center; gap:7px; background:#fff; border-radius:50px; padding:8px 16px; box-shadow:0 2px 8px rgba(0,0,0,.07); font-size:13px; font-weight:700; border:1.5px solid transparent; }
    .pub-stat-pill.total { border-color:#1a7a2e; color:#1a7a2e; }
    .pub-stat-pill.res   { border-color:#66bb6a; color:#2e7d32; }
    .pub-stat-pill.unres { border-color:#bdbdbd; color:#555; }
    .pub-stat-pill.occ   { border-color:#ef9a9a; color:#c62828; }
    .pub-stat-pill .pill-num { font-size:18px; font-weight:900; line-height:1; }

    .pub-legend { display:flex; flex-wrap:wrap; gap:10px; justify-content:center; margin-bottom:16px; }
    .pub-legend-item { display:flex; align-items:center; gap:5px; font-size:11px; font-weight:600; color:#555; }
    .pub-legend-dot  { width:12px; height:12px; border-radius:3px; border:1.5px solid transparent; }
    .pub-legend-dot.g { background:#e8f5e9; border-color:#66bb6a; }
    .pub-legend-dot.r { background:#ffebee; border-color:#ef5350; }

    .room-card   { background:#fff; border-radius:16px; box-shadow:0 2px 20px rgba(0,0,0,.08); padding:16px; overflow-x:auto; max-width:700px; margin:0 auto; }
    .room-inner  { min-width:280px; }

    .door-label  { text-align:center; font-size:11px; font-weight:700; letter-spacing:1px; color:#aaa; text-transform:uppercase; margin-bottom:10px; display:flex; align-items:center; justify-content:center; gap:6px; }
    .door-label::before,.door-label::after { content:''; flex:1; height:1px; background:#e0e0e0; }

    .cabin-row   { display:grid; grid-template-columns:1fr 6px 1fr; gap:0 8px; margin-bottom:4px; align-items:center; }
    .aisle-div   { background:rgba(0,0,0,0.07); border-radius:4px; align-self:stretch; }
    .side-left   { display:flex; gap:3px; justify-content:flex-end; }
    .side-right  { display:flex; gap:3px; justify-content:flex-start; }

    .window-label { text-align:center; font-size:10px; font-weight:700; color:#aaa; letter-spacing:1px; text-transform:uppercase; padding:6px 0; border-top:1px dashed #e0e0e0; border-bottom:1px dashed #e0e0e0; margin:8px 0; }

    .pub-seat {
      width:36px; height:36px; border-radius:6px; display:flex; flex-direction:column;
      align-items:center; justify-content:center; font-size:9px; font-weight:800;
      line-height:1; gap:1px; border:1.5px solid transparent;
      transition:transform .12s, box-shadow .12s;
    }
    .pub-seat:hover:not(.pub-blank):not(.res-occ) { transform:scale(1.18); box-shadow:0 4px 14px rgba(0,0,0,.22); z-index:5; position:relative; cursor:pointer; }
    .pub-seat i { font-size:11px; }
    .pub-seat.res-avail { background:#e8f5e9; border-color:#66bb6a; color:#2e7d32; cursor:pointer; }
    .pub-seat.res-occ   { background:#ffebee; border-color:#ef5350; color:#c62828; cursor:not-allowed; }
    .pub-seat.pub-blank { background:transparent; border:none; cursor:default; pointer-events:none; }

    .ac-row   { display:grid; grid-template-columns:1fr 6px 1fr; gap:0 8px; margin-top:12px; }
    .ac-label { background:#e8f5e9; border-radius:8px; text-align:center; padding:6px; font-size:10px; font-weight:800; color:#1a7a2e; }

    .back-link { font-size:13px; color:#6b7fa3; text-decoration:none; display:inline-flex; align-items:center; gap:5px; }
    .back-link:hover { color:#1a7a2e; }

    /* Unreserved info box */
    .unres-box { background:linear-gradient(135deg,#f0f7ff,#e8f5e9); border:1.5px solid #b3d9f7; border-radius:14px; padding:16px 20px; max-width:700px; margin:0 auto 20px; font-size:13px; }

    @media(max-width:420px) {
      .pub-seat { width:28px; height:28px; font-size:8px; }
      .pub-seat i { font-size:9px; }
      .room-card { padding:10px; }
    }
  </style>
</head>
<body>

<!-- Announcement bar -->
<div class="announcement-bar">
  <div class="announcement-track">
    📢 Admissions Open at Ekagra Abhyasika &bull; Reserved &amp; Unreserved Seats Available &bull; Premium AC Study Library in Undri Pune &bull; Open Daily 6:00 AM – 10:00 PM
  </div>
</div>
<style>
.announcement-bar{width:100%;overflow:hidden;background:linear-gradient(90deg,#1a7a2e,#0f5520);color:#fff;padding:12px 0;position:sticky;top:0;z-index:99999;border-bottom:1px solid rgba(255,255,255,0.08);box-shadow:0 4px 14px rgba(0,0,0,0.18);}
.announcement-track{white-space:nowrap;display:inline-block;padding-left:100%;font-size:14px;font-weight:700;letter-spacing:0.5px;animation:scrollA 22s linear infinite;}
@keyframes scrollA{0%{transform:translateX(0);}100%{transform:translateX(-100%);}}
</style>

<nav class="public-nav">
  <a href="index.php" class="public-nav-brand">
    <div class="brand-box">EA</div>
    <h1>Ekagra Abhyasika<span>Study Library · Undri, Pune</span></h1>
  </a>
  <div class="public-nav-links">
    <a href="index.php#about">About</a>
    <a href="index.php#facilities">Facilities</a>
    <a href="index.php#fees">Fees</a>
    <a href="index.php#seats">Check Seats</a>
    <a href="index.php#contact">Contact</a>
    <a href="student/login.php" class="btn-login"><i class="fas fa-sign-in-alt me-1"></i>Student Login</a>
  </div>
</nav>

<div class="seat-hero">
  <div class="container">
    <div class="live-badge"><span class="live-dot"></span> Live Availability</div>
    <h1><i class="fas fa-chair me-2"></i>Seat Availability</h1>
    <p>107 seats total &bull; Tap any free seat to enquire &bull; Reserved or Unreserved — your choice</p>
  </div>
</div>

<div class="container py-4">
  <a href="index.php" class="back-link mb-3 d-inline-flex"><i class="fas fa-arrow-left"></i> Back to Home</a>

  <!-- Stats -->
  <div class="pub-stats-row">
    <div class="pub-stat-pill total"><span class="pill-num"><?php echo $reservedFree; ?></span>&nbsp;Reserved Slots Free</div>
    <div class="pub-stat-pill res"><span class="pill-num"><?php echo $reservedTaken; ?></span>&nbsp;Reserved Taken</div>
    <div class="pub-stat-pill unres"><span class="pill-num"><?php echo $unreservedActive; ?></span>&nbsp;Unreserved Students</div>
    <div class="pub-stat-pill occ"><span class="pill-num"><?php echo $totalStudents; ?></span>&nbsp;Total Enrolled</div>
  </div>

  <!-- Unreserved info box -->
  <div class="unres-box">
    <div style="display:flex;align-items:flex-start;gap:12px;">
      <div style="font-size:24px;">🪑</div>
      <div>
        <div style="font-weight:700;font-size:14px;margin-bottom:4px;">Unreserved / General Access</div>
        <div style="color:#555;">If you don't need a fixed seat, choose <strong>Unreserved</strong> admission at a lower fee.
        You can sit on any free seat each day. Currently <strong><?php echo $unreservedActive; ?></strong> student(s) have unreserved access.
        <a href="#" onclick="openEnquiryModal('unreserved')" style="color:#1a7a2e;font-weight:700;margin-left:4px;">Enquire for Unreserved →</a></div>
      </div>
    </div>
  </div>

  <!-- Legend -->
  <div class="pub-legend">
    <div class="pub-legend-item"><div class="pub-legend-dot g"></div> Seat Available — tap to enquire</div>
    <div class="pub-legend-item"><div class="pub-legend-dot r"></div> Seat Reserved — taken by a student</div>
  </div>

  <!-- Room Map -->
  <div class="room-card">
    <div class="room-inner">
      <div class="door-label"><i class="fas fa-door-open"></i>&nbsp;DOOR / ENTRANCE</div>

      <?php foreach($rows as $i => $pair):
        if ($i === 8): // window divider between block 4 and 5
      ?>
        <div class="window-label"><i class="fas fa-wind me-1"></i> WINDOW / AISLE</div>
      <?php endif; ?>
        <div class="cabin-row">
          <div class="side-left">
            <?php foreach($pair[0] as $sn) echo pubSeat($seatMap,$sn); ?>
          </div>
          <div class="aisle-div"></div>
          <div class="side-right">
            <?php foreach($pair[1] as $sn) echo pubSeat($seatMap,$sn); ?>
          </div>
        </div>
      <?php endforeach; ?>

      <div class="ac-row">
        <div class="ac-label"><i class="fas fa-snowflake me-1"></i> AC</div>
        <div></div>
        <div class="ac-label"><i class="fas fa-snowflake me-1"></i> Air Conditioner</div>
      </div>
    </div>
  </div>

  <p class="text-center mt-3" style="font-size:12px;color:#aaa;">
    <i class="fas fa-sync-alt me-1"></i> Availability updates on every page refresh
  </p>
</div>

<!-- Seat Enquiry Modal -->
<div class="modal fade" id="seatModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="border-radius:16px;border:none;box-shadow:0 20px 60px rgba(0,0,0,.18);">
      <div class="modal-header" style="border-bottom:1px solid #f0f0f0;padding:18px 20px;">
        <h5 class="modal-title" style="font-size:15px;"><i class="fas fa-chair me-2" style="color:#1a7a2e;"></i>Reserve This Seat</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body" style="padding:20px;">
        <p style="font-size:13px;color:#555;margin-bottom:10px;">You selected:</p>
        <div style="display:flex;align-items:center;gap:12px;margin-bottom:16px;">
          <span id="mSeatNo" style="background:#1a7a2e;color:#fff;border-radius:8px;padding:4px 14px;font-weight:900;font-family:'Rajdhani',sans-serif;font-size:22px;">–</span>
          <div>
            <div style="font-weight:700;font-size:14px;">Available Seat</div>
            <div style="font-size:12px;color:#1ab759;font-weight:600;"><i class="fas fa-circle" style="font-size:7px;"></i> Free — you can reserve this</div>
          </div>
        </div>
        <p style="font-size:13px;color:#666;margin-bottom:16px;">
          Contact the admin to <strong>reserve this specific seat</strong> permanently, or ask about <strong>unreserved</strong> (general access) admission at a lower fee.
        </p>
        <a id="mWaLink" href="#" target="_blank"
           style="background:#25d366;color:#fff;border-radius:10px;padding:11px 20px;font-weight:700;font-size:14px;display:flex;align-items:center;justify-content:center;gap:8px;text-decoration:none;margin-bottom:8px;">
          <i class="fab fa-whatsapp fa-lg"></i> Enquire via WhatsApp
        </a>
        <a href="tel:+917709497762"
           style="background:#1a7a2e;color:#fff;border-radius:10px;padding:11px 20px;font-weight:700;font-size:14px;display:flex;align-items:center;justify-content:center;gap:8px;text-decoration:none;">
          <i class="fas fa-phone"></i> Call Admin Directly
        </a>
      </div>
    </div>
  </div>
</div>

<!-- Unreserved Enquiry Modal -->
<div class="modal fade" id="unreservedModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="border-radius:16px;border:none;box-shadow:0 20px 60px rgba(0,0,0,.18);">
      <div class="modal-header" style="border-bottom:1px solid #f0f0f0;padding:18px 20px;">
        <h5 class="modal-title" style="font-size:15px;"><i class="fas fa-door-open me-2" style="color:#1a7a2e;"></i>Unreserved / General Access</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body" style="padding:20px;">
        <p style="font-size:13px;color:#555;margin-bottom:16px;">
          With <strong>Unreserved admission</strong>, you don't get a fixed seat number — you can sit on any free seat each day when you arrive. This is ideal if you don't need a permanent spot and want a lower fee.
        </p>
        <a href="https://wa.me/917709497762?text=<?php echo urlencode('Hello! I am interested in Unreserved (general access) admission at Ekagra Abhyasika. Please guide me.'); ?>" target="_blank"
           style="background:#25d366;color:#fff;border-radius:10px;padding:11px 20px;font-weight:700;font-size:14px;display:flex;align-items:center;justify-content:center;gap:8px;text-decoration:none;margin-bottom:8px;">
          <i class="fab fa-whatsapp fa-lg"></i> Enquire via WhatsApp
        </a>
        <a href="tel:+917709497762"
           style="background:#1a7a2e;color:#fff;border-radius:10px;padding:11px 20px;font-weight:700;font-size:14px;display:flex;align-items:center;justify-content:center;gap:8px;text-decoration:none;">
          <i class="fas fa-phone"></i> Call Admin Directly
        </a>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
(function(){
  var ADMIN = '917709497762';

  window.openModal = function(el) {
    var d = JSON.parse(el.dataset.seat);
    var msg = encodeURIComponent('Hello! I want to enquire about Seat No. ' + d.seat_number + ' at Ekagra Abhyasika. Is it available for reserved admission? Please guide me.');
    document.getElementById('mSeatNo').textContent = 'Seat ' + d.seat_number;
    document.getElementById('mWaLink').href = 'https://wa.me/' + ADMIN + '?text=' + msg;
    new bootstrap.Modal(document.getElementById('seatModal')).show();
  };

  window.openEnquiryModal = function(type) {
    new bootstrap.Modal(document.getElementById('unreservedModal')).show();
    return false;
  };
})();
</script>
</body>
</html>
