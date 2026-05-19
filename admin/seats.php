<?php
ob_start();
require_once '../includes/db.php';
if (!isset($_SESSION['admin_id'])) { header('Location: login.php'); exit; }

$msg = $err = '';

// Free a reserved seat
if (isset($_GET['free'])) {
    $seatNo = (int)$_GET['free'];
    $seat = $pdo->prepare("SELECT * FROM seats WHERE seat_number=?");
    $seat->execute([$seatNo]);
    $s = $seat->fetch();
    if ($s && $s['status']==='occupied') {
        $pdo->prepare("UPDATE seats SET status='available', seat_type=NULL, student_id=NULL WHERE seat_number=?")->execute([$seatNo]);
        if ($s['student_id']) {
            $pdo->prepare("UPDATE students SET status='left' WHERE id=?")->execute([$s['student_id']]);
        }
        logActivity($pdo,'Seat Freed',$_SESSION['admin_user'],"Seat $seatNo freed");
        $msg = "Seat $seatNo is now available.";
    }
}

// All reserved seats with student info
$reservedSeats = $pdo->query("
    SELECT s.*, st.full_name as student_name, st.mobile, st.renewal_date, st.status as student_status, st.id as sid
    FROM seats s
    LEFT JOIN students st ON s.student_id = st.id
    WHERE s.status='occupied'
    ORDER BY s.seat_number
")->fetchAll();

// Stats
$totalSeats        = 107;
$reservedCapacity  = 76;  // max reserved admissions
$reservedTaken     = $pdo->query("SELECT COUNT(*) FROM students WHERE seat_type='reserved' AND status='active'")->fetchColumn();
$unreservedActive  = $pdo->query("SELECT COUNT(*) FROM students WHERE seat_type='unreserved' AND status='active'")->fetchColumn();
$reservedFree      = $reservedCapacity - $reservedTaken;
$totalAvailableSeats = $totalSeats - ($reservedTaken + $unreservedActive);

$page_title = 'Seat Management';
$base = '../';
include '../includes/header.php';
?>
<div class="admin-wrapper">
<?php include '_sidebar.php'; ?>
<div class="main-content">
  <div class="topbar">
    <div class="topbar-left">
      <button class="sidebar-toggle" id="sidebarToggle"><i class="fas fa-bars"></i></button>
      <span class="page-title">Seat Management</span>
    </div>
  </div>

  <div class="page-content">
    <?php if($msg): ?><div class="alert alert-success alert-dismissible fade show"><?php echo htmlspecialchars($msg); ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div><?php endif; ?>

    <!-- Stats -->
    <div class="row g-3 mb-4">
      <div class="col-6 col-md-3">
        <div class="stat-card">
          <div class="stat-icon blue"><i class="fas fa-chair"></i></div>
          <div class="stat-info"><div class="stat-value"><?php echo $totalSeats; ?></div><div class="stat-label">Total Seats</div></div>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="stat-card">
          <div class="stat-icon yellow"><i class="fas fa-lock"></i></div>
          <div class="stat-info"><div class="stat-value"><?php echo $reservedTaken; ?>/<?php echo $reservedCapacity; ?></div><div class="stat-label">Reserved Taken</div></div>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="stat-card">
          <div class="stat-icon green"><i class="fas fa-check"></i></div>
          <div class="stat-info"><div class="stat-value"><?php echo $reservedFree; ?></div><div class="stat-label">Reserved Slots Free</div></div>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="stat-card">
          <div class="stat-icon info"><i class="fas fa-door-open"></i></div>
          <div class="stat-info"><div class="stat-value"><?php echo $unreservedActive; ?></div><div class="stat-label">Unreserved Students</div></div>
        </div>
      </div>
    </div>

    <!-- Info box -->
    <div class="alert" style="background:#e8f4fd;border:1px solid #b3d9f7;border-radius:10px;font-size:13px;color:#1a7a2e;margin-bottom:20px;">
      <i class="fas fa-info-circle me-2"></i>
      <strong>How seats work:</strong> Any of the 107 seats can be reserved or unreserved — decided by the student at admission.
      <strong>Reserved</strong> = student picks a specific seat, it is locked permanently for them.
      <strong>Unreserved</strong> = student gets general access, sits on any free seat each day (no fixed seat, not shown on map).
      Guideline: up to <strong>64 reserved</strong> slots (60%) and <strong>43 unreserved</strong> students (40%). Reserved seats include lockers.
    </div>

    <!-- Legend -->
    <div class="seat-legend mb-3">
      <div class="legend-item"><div class="legend-dot green"></div>Reserved – Available (no one assigned yet)</div>
      <div class="legend-item"><div class="legend-dot red"></div>Reserved – Occupied (locked to a student)</div>
      <div class="legend-item"><div class="legend-dot gray"></div>Free seat (available for anyone)</div>
    </div>

    <!-- Visual Seat Map (107 seats, exact physical layout) -->
    <div class="card-panel mb-4">
      <div class="card-panel-header">
        <span class="card-panel-title"><i class="fas fa-th me-2"></i>Visual Seat Map — Click occupied seat for details</span>
      </div>
      <div class="card-panel-body">
        <?php
        // Build seat map from DB
        $allSeats = $pdo->query("
            SELECT s.seat_number, s.status, s.seat_type, st.full_name as student_name, st.id as sid, st.renewal_date
            FROM seats s LEFT JOIN students st ON s.student_id=st.id
            ORDER BY s.seat_number
        ")->fetchAll();
        $seatMap = [];
        foreach($allSeats as $row) $seatMap[$row['seat_number']] = $row;

        function adminSeat($seatMap, $n) {
            if ($n === null) {
                return "<div class='seat-item blank-seat'></div>";
            }
            if (!isset($seatMap[$n])) return '';
            $s = $seatMap[$n];
            $occ = $s['status'] === 'occupied';
            if ($occ && $s['seat_type'] === 'reserved') {
                $cls = 'reserved-occupied';
                $icon = 'fa-user';
            } else {
                $cls = 'unreserved-available'; // free seat
                $icon = 'fa-chair';
            }
            $data = json_encode([
                'seat_number'  => $n,
                'status'       => ucfirst($s['status']),
                'seat_type'    => $occ ? ucfirst($s['seat_type']) : 'Free',
                'student_name' => $s['student_name'] ?? 'Vacant',
                'renewal_date' => $s['renewal_date'] ? date('d M Y', strtotime($s['renewal_date'])) : '-',
                'student_id'   => $s['sid'] ?? '',
            ]);
            $encoded = urlencode($data);
            return "<div class='seat-item $cls' onclick=\"openSeatModal('$encoded')\" title='Seat $n" . ($s['student_name'] ? ": {$s['student_name']}" : '') . "'>
                      <i class='fas $icon'></i> $n
                    </div>";
        }

        // Physical layout rows — exact match to handwritten diagram
        // LEFT side (4 seats) | aisle | RIGHT side (4-5 seats)
        // null = blank space (not a seat)
        $rows = [
            // [left seats],       [right seats]
            [[1,2,3,4],            [5,6,7,8]],
            [[9,10,11,12],         [13,14,15,16,17]],
            [[18,19,20,21],        [22,23,24,25,26]],
            [[27,28,29,30],        [31,32,33,34,35]],
            [[36,37,38,39],        [40,41,42,43,44]],
            [[null,52,51,50],      [49,48,47,46,45]],  // null = blank space
            [[53,54,55,56],        [57,58,59,60,61]],
            [[70,69,68,67],        [66,65,64,63,62]],
            [[71,72,73,74],        [75,76,77,78,79]],
            [[88,87,86,85],        [84,83,82,81,80]],
            [[89,90,91,92],        [93,94,95,96,97]],
            [[107,106,105,104],    [103,102,101,100,99,98]],
        ];
        ?>
        <div style="overflow-x:auto;">
          <div style="min-width:340px;max-width:680px;margin:0 auto;">
            <div style="text-align:center;font-size:11px;font-weight:700;letter-spacing:1px;color:#aaa;text-transform:uppercase;margin-bottom:10px;display:flex;align-items:center;gap:6px;justify-content:center;">
              <i class="fas fa-door-open"></i>&nbsp;DOOR / ENTRANCE
            </div>
            <?php foreach($rows as $i => $pair):
              // Add window divider between row 7 and 8 (after seat block 4)
              if ($i === 8): ?>
              <div style="text-align:center;font-size:10px;font-weight:700;color:#aaa;letter-spacing:1px;text-transform:uppercase;padding:6px 0;border-top:1px dashed #e0e0e0;border-bottom:1px dashed #e0e0e0;margin:8px 0;">
                <i class="fas fa-wind me-1"></i> WINDOW / AISLE
              </div>
              <?php endif; ?>
              <div style="display:grid;grid-template-columns:1fr 6px 1fr;gap:0 8px;margin-bottom:4px;align-items:center;">
                <div style="display:flex;gap:3px;justify-content:flex-end;">
                  <?php foreach($pair[0] as $sn) echo adminSeat($seatMap,$sn); ?>
                </div>
                <div style="background:rgba(0,0,0,0.07);border-radius:4px;align-self:stretch;"></div>
                <div style="display:flex;gap:3px;justify-content:flex-start;">
                  <?php foreach($pair[1] as $sn) echo adminSeat($seatMap,$sn); ?>
                </div>
              </div>
            <?php endforeach; ?>
            <div style="display:grid;grid-template-columns:1fr 6px 1fr;gap:0 8px;margin-top:12px;">
              <div style="background:#e3f0ff;border-radius:8px;text-align:center;padding:6px;font-size:10px;font-weight:800;color:#1a7a2e;"><i class="fas fa-snowflake me-1"></i>AC</div>
              <div></div>
              <div style="background:#e3f0ff;border-radius:8px;text-align:center;padding:6px;font-size:10px;font-weight:800;color:#1a7a2e;"><i class="fas fa-snowflake me-1"></i>Air Conditioner</div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Occupied Reserved Seats Table -->
    <div class="card-panel">
      <div class="card-panel-header">
        <span class="card-panel-title"><i class="fas fa-table me-2"></i>Reserved Seats — Occupied</span>
        <input type="text" data-search-table="seatTable" class="form-control form-control-sm" placeholder="Search..." style="width:200px;">
      </div>
      <div class="card-panel-body p-0">
        <div class="table-responsive">
          <table class="table" id="seatTable">
            <thead><tr><th>Seat</th><th>Student</th><th>Mobile</th><th>Renewal</th><th>Actions</th></tr></thead>
            <tbody>
            <?php if(empty($reservedSeats)): ?>
            <tr><td colspan="5" class="text-center py-4" style="color:var(--text-muted);">No reserved seats occupied.</td></tr>
            <?php endif; ?>
            <?php foreach($reservedSeats as $s): ?>
            <tr>
              <td><span class="badge bg-primary"><?php echo $s['seat_number']; ?></span></td>
              <td><?php echo $s['student_name'] ? htmlspecialchars($s['student_name']) : '<span style="color:var(--text-muted);">–</span>'; ?></td>
              <td style="font-size:13px;"><?php echo htmlspecialchars($s['mobile'] ?? '–'); ?></td>
              <td style="font-size:13px;"><?php echo $s['renewal_date'] ? date('d M Y', strtotime($s['renewal_date'])) : '–'; ?></td>
              <td>
                <?php if($s['sid']): ?>
                <a href="students.php?view=<?php echo $s['sid']; ?>" class="btn btn-outline-primary btn-sm"><i class="fas fa-eye"></i></a>
                <a href="?free=<?php echo $s['seat_number']; ?>" class="btn btn-danger btn-sm" data-confirm="Free seat <?php echo $s['seat_number']; ?>?"><i class="fas fa-unlock"></i></a>
                <?php endif; ?>
              </td>
            </tr>
            <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
</div>

<!-- Seat Detail Modal -->
<div class="modal fade" id="seatDetailModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="fas fa-chair me-2"></i>Seat <span id="modalSeatNumber"></span></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <table class="table table-sm">
          <tr><th>Seat Number</th><td><strong id="modalSeatNumber2"></strong></td></tr>
          <tr><th>Type</th><td id="modalSeatType"></td></tr>
          <tr><th>Status</th><td id="modalSeatStatus"></td></tr>
          <tr><th>Student</th><td><strong id="modalStudentName"></strong></td></tr>
          <tr><th>Renewal Date</th><td id="modalRenewalDate"></td></tr>
        </table>
      </div>
      <div class="modal-footer" id="modalSeatActions"></div>
    </div>
  </div>
</div>

<style>
.seat-item.blank-seat { background:transparent!important; border:none!important; cursor:default!important; pointer-events:none; }
</style>

<?php
$extra_js = "<script>
function openSeatModal(encoded) {
  const d = JSON.parse(decodeURIComponent(encoded));
  document.getElementById('modalSeatNumber').textContent  = d.seat_number;
  document.getElementById('modalSeatNumber2').textContent = d.seat_number;
  document.getElementById('modalSeatType').textContent    = d.seat_type;
  document.getElementById('modalSeatStatus').textContent  = d.status;
  document.getElementById('modalStudentName').textContent = d.student_name || 'Vacant';
  document.getElementById('modalRenewalDate').textContent = d.renewal_date || '–';
  const a = document.getElementById('modalSeatActions');
  a.innerHTML = '';
  if (d.status === 'Occupied' && d.student_id) {
    a.innerHTML = '<a href=\"students.php?view='+d.student_id+'\" class=\"btn btn-primary btn-sm\"><i class=\"fas fa-eye me-1\"></i>View Student</a>' +
      '<a href=\"seats.php?free='+d.seat_number+'\" class=\"btn btn-danger btn-sm\" onclick=\"return confirm(\\\"Free seat '+d.seat_number+'?\\\")\"><i class=\"fas fa-unlock me-1\"></i>Free Seat</a>';
  } else {
    a.innerHTML = '<a href=\"students.php?add=1&seat='+d.seat_number+'\" class=\"btn btn-success btn-sm\"><i class=\"fas fa-user-plus me-1\"></i>Assign Student</a>';
  }
  new bootstrap.Modal(document.getElementById('seatDetailModal')).show();
}
</script>";
include '../includes/footer.php';
?>
