<?php

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../app/Config/Database.php';
require_once __DIR__ . '/../../app/Helpers/Utils.php';
require_once __DIR__ . '/../../app/Helpers/AdminAuth.php';
require_once __DIR__ . '/../../app/Helpers/Csrf.php';
require_once __DIR__ . '/../../app/Helpers/Layout.php';

new Database();
AdminAuth::requireAdmin();

$database = new Database();
$db = $database->getConnection();

// Handle update status lewat AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajax_action']) && $_POST['ajax_action'] === 'update_status') {
    header('Content-Type: application/json');
    if (!Csrf::validate($_POST['csrf_token'] ?? null)) {
        echo json_encode(['ok' => false, 'message' => 'Token tidak valid.']);
        exit;
    }
    $id = (int)($_POST['id'] ?? 0);
    $newStatus = $_POST['status'] ?? '';
    $allowed = ['Confirmed', 'Cancelled', 'Completed', 'Arrived', 'In Progress'];
    if ($id < 1 || !in_array($newStatus, $allowed)) {
        echo json_encode(['ok' => false, 'message' => 'Parameter tidak valid.']);
        exit;
    }
    try {
        $stmt = $db->prepare('UPDATE reservations SET status = :status WHERE id = :id');
        $stmt->execute([':status' => $newStatus, ':id' => $id]);
        echo json_encode(['ok' => true]);
    } catch (PDOException $e) {
        echo json_encode(['ok' => false, 'message' => 'Gagal mengupdate status.']);
    }
    exit;
}

// Ambil data statistik
$todayStr = (new DateTime('now', new DateTimeZone('Asia/Jakarta')))->format('Y-m-d');

$stmtTotal = $db->query("SELECT COUNT(*) FROM reservations");
$totalAll = (int)$stmtTotal->fetchColumn();

$stmtToday = $db->prepare("SELECT COUNT(*) FROM reservations WHERE reservation_date = :d");
$stmtToday->execute([':d' => $todayStr]);
$totalToday = (int)$stmtToday->fetchColumn();

$stmtPending = $db->query("SELECT COUNT(*) FROM reservations WHERE status = 'Pending'");
$totalPending = (int)$stmtPending->fetchColumn();

$stmtConfirmed = $db->query("SELECT COUNT(*) FROM reservations WHERE status = 'Confirmed'");
$totalConfirmed = (int)$stmtConfirmed->fetchColumn();

// Ambil data reservasi
$filter = $_GET['filter'] ?? 'all';
$whereClause = '';
if ($filter === 'today') {
    $whereClause = " WHERE r.reservation_date = '{$todayStr}'";
} elseif (in_array($filter, ['pending','confirmed','cancelled','completed'])) {
    $filterMap = ['pending'=>'Pending','confirmed'=>'Confirmed','cancelled'=>'Cancelled','completed'=>'Completed'];
    $dbStatus = $filterMap[$filter] ?? 'Pending';
    $whereClause = " WHERE r.status = '{$dbStatus}'";
}

$query = "SELECT r.*, s.name as service_name, s.price 
          FROM reservations r 
          JOIN services s ON r.service_id = s.id
          {$whereClause}
          ORDER BY r.reservation_date DESC, r.reservation_time ASC";
$stmt = $db->prepare($query);
$stmt->execute();
$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

$csrf = Csrf::token();

// Data grafik
$stmtChart = $db->query("SELECT reservation_date, COUNT(*) as count FROM reservations GROUP BY reservation_date ORDER BY reservation_date DESC LIMIT 7");
$chartDataRaw = $stmtChart->fetchAll(PDO::FETCH_ASSOC);
$chartLabels = [];
$chartCounts = [];
foreach(array_reverse($chartDataRaw) as $cd) {
    $chartLabels[] = $cd['reservation_date'];
    $chartCounts[] = $cd['count'];
}

// Event kalender
$calendarEvents = [];
foreach ($bookings as $b) {
    $color = '#ffca2c'; // pending
    if ($b['status'] === 'Confirmed') $color = '#75b798';
    if ($b['status'] === 'Arrived') $color = '#17a2b8';
    if ($b['status'] === 'In Progress') $color = '#d4af37';
    if ($b['status'] === 'Completed') $color = '#6ea8fe';
    if ($b['status'] === 'Cancelled') $color = '#ff6b6b';
    
    $calendarEvents[] = [
        'id' => $b['id'],
        'title' => substr($b['reservation_time'], 0, 5) . ' ' . $b['customer_name'],
        'start' => $b['reservation_date'] . 'T' . $b['reservation_time'],
        'color' => $color
    ];
}

layoutRenderHead([
    'title' => 'Admin Dashboard - CukurGo',
    'description' => 'Pantau daftar reservasi pelanggan CukurGo secara real-time.',
    'asset_prefix' => '../',
    'extra_head' => '<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script><script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .fc-theme-bootstrap5 .fc-scrollgrid { border-color: rgba(255,255,255,0.05); border-radius: 8px; overflow: hidden; }
        .fc-theme-bootstrap5 td, .fc-theme-bootstrap5 th { border-color: rgba(255,255,255,0.05); }
        .fc .fc-col-header-cell-cushion { color: var(--primary-accent, #d4af37); text-decoration: none; font-weight: 700; text-transform: uppercase; font-size: 0.8rem; letter-spacing: 1px; padding: 10px 0; }
        .fc .fc-timegrid-slot-label-cushion { color: #a0a0a0; font-size: 0.75rem; }
        .fc .fc-toolbar-title { font-size: 1.2rem; font-weight: 700; color: #fff; text-transform: uppercase; letter-spacing: 1px; }
        .fc .fc-button-primary { background-color: rgba(212,175,55,0.1); border: 1px solid rgba(212,175,55,0.3); color: var(--primary-accent, #d4af37); font-weight: 600; border-radius: 6px; text-transform: capitalize; }
        .fc .fc-button-primary:hover { background-color: rgba(212,175,55,0.2); border-color: var(--primary-accent, #d4af37); color: #fff; }
        .fc .fc-button-primary:not(:disabled).fc-button-active, .fc .fc-button-primary:not(:disabled):active { background-color: var(--primary-accent, #d4af37); border-color: var(--primary-accent, #d4af37); color: #000; }
        .fc-event { border: none !important; border-radius: 6px !important; padding: 2px 4px; font-size: 0.8rem; font-weight: 600; cursor: pointer; box-shadow: 0 2px 5px rgba(0,0,0,0.3); transition: transform 0.2s; }
        .fc-event:hover { transform: scale(1.02); z-index: 5 !important; }
        .fc-timegrid-event .fc-event-main { padding: 3px 5px; color: #000; font-family: "Inter", sans-serif; }
        .fc .fc-timegrid-now-indicator-line { border-color: #ff4d4d; }
        .fc .fc-timegrid-now-indicator-arrow { border-color: #ff4d4d; border-width: 5px 6px 5px 0; border-right-color: #ff4d4d; }
        .fc-day-today { background-color: rgba(255,255,255,0.02) !important; }
    </style>'
]);
?>
<body class="bg-dark text-white" style="background: #0a0a0a;">
    <!-- Admin Navbar -->
    <nav class="navbar navbar-dark sticky-top py-3 shadow-lg" style="background: linear-gradient(90deg, #0d0d0d 0%, #1a1a1a 100%); border-bottom: 1px solid rgba(212,175,55,0.15);">
        <div class="container-fluid px-4">
            <a class="navbar-brand text-gold fs-4 d-flex align-items-center gap-2" href="dashboard.php" style="font-family: 'Lobster', cursive;">
                <div class="d-flex align-items-center justify-content-center rounded-circle" style="width:36px;height:36px;background:rgba(212,175,55,0.15);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="var(--primary-accent)" viewBox="0 0 16 16"><path d="M8 4a.5.5 0 0 1 .5.5V6a.5.5 0 0 1-1 0V4.5A.5.5 0 0 1 8 4zM3.732 5.732a.5.5 0 0 1 .707 0l1.414 1.414a.5.5 0 1 1-.707.708L3.732 6.44a.5.5 0 0 1 0-.708zM14 8a.5.5 0 0 1-.5.5H12a.5.5 0 0 1 0-1h1.5A.5.5 0 0 1 14 8zM2 8a.5.5 0 0 1 .5-.5H4a.5.5 0 0 1 0 1H2.5A.5.5 0 0 1 2 8zm9.854-2.268a.5.5 0 0 1 0 .708l-1.414 1.414a.5.5 0 1 1-.708-.708l1.414-1.414a.5.5 0 0 1 .708 0zM8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/></svg>
                </div>
                CukurGo <span class="badge bg-gold text-dark ms-2 fw-bold" style="font-family:'Montserrat',sans-serif;font-size:0.6rem;letter-spacing:1px;">ADMIN</span>
            </a>
            <div class="d-flex align-items-center gap-3">
                <span class="text-gold small d-none d-sm-inline"><i class="bi bi-calendar3 me-1"></i><?= date('d M Y') ?></span>
                <a class="btn btn-sm btn-outline-gold rounded-pill px-3 fw-semibold" href="../Index.php" target="_blank"><i class="bi bi-box-arrow-up-right me-1"></i>Lihat Situs</a>
                <a class="btn btn-sm rounded-pill px-3 fw-bold" href="logout.php" style="background:rgba(255,77,77,0.15);color:#ff6b6b;border:1px solid rgba(255,77,77,0.3);"><i class="bi bi-box-arrow-right me-1"></i>Keluar</a>
            </div>
        </div>
    </nav>

    <div class="container-fluid px-4 py-4">
        <!-- Stats Cards -->
        <div class="row g-3 mb-4">
            <?php
            $stats = [
                ['label'=>'Total Reservasi','value'=>$totalAll,'icon'=>'bi-calendar2-check','color'=>'#d4af37','bg'=>'rgba(212,175,55,0.1)'],
                ['label'=>'Hari Ini','value'=>$totalToday,'icon'=>'bi-clock-history','color'=>'#6ea8fe','bg'=>'rgba(110,168,254,0.1)'],
                ['label'=>'Menunggu Konfirmasi','value'=>$totalPending,'icon'=>'bi-hourglass-split','color'=>'#ffca2c','bg'=>'rgba(255,202,44,0.1)'],
                ['label'=>'Dikonfirmasi','value'=>$totalConfirmed,'icon'=>'bi-check-circle','color'=>'#75b798','bg'=>'rgba(117,183,152,0.1)'],
            ];
            foreach ($stats as $s): ?>
            <div class="col-6 col-lg-3">
                <div class="rounded-4 p-3 p-md-4 h-100" style="background:linear-gradient(145deg,#1e1e1e,#161616);border:1px solid rgba(255,255,255,0.05);">
                    <div class="d-flex align-items-center gap-3">
                        <div class="d-flex align-items-center justify-content-center rounded-3" style="width:48px;height:48px;background:<?=$s['bg']?>;">
                            <i class="bi <?=$s['icon']?> fs-5" style="color:<?=$s['color']?>"></i>
                        </div>
                        <div>
                            <div class="fw-bold fs-4" style="color:<?=$s['color']?>"><?=$s['value']?></div>
                            <div class="text-white small"><?=$s['label']?></div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Dashboard Charts & Calendar -->
        <div class="row g-3 mb-4">
            <div class="col-12 col-lg-4">
                <div class="rounded-4 p-4 h-100" style="background:linear-gradient(145deg,#1e1e1e,#161616);border:1px solid rgba(255,255,255,0.05);">
                    <h6 class="fw-bold text-gold mb-3"><i class="bi bi-graph-up me-2"></i>Tren Reservasi</h6>
                    <canvas id="reservationsChart" width="400" height="300"></canvas>
                </div>
            </div>
            <div class="col-12 col-lg-8">
                <div class="rounded-4 p-4 h-100" style="background:linear-gradient(145deg,#1e1e1e,#161616);border:1px solid rgba(255,255,255,0.05);">
                    <h6 class="fw-bold text-gold mb-3"><i class="bi bi-calendar-week me-2"></i>Agenda</h6>
                    <div id="calendar"></div>
                </div>
            </div>
        </div>

        <!-- Filter Tabs + Table -->
        <div class="rounded-4 overflow-hidden" style="background:linear-gradient(145deg,#1e1e1e,#161616);border:1px solid rgba(255,255,255,0.05);">
            <!-- Header -->
            <div class="d-flex flex-wrap justify-content-between align-items-center p-3 px-4" style="border-bottom:1px solid rgba(212,175,55,0.1);">
                <h5 class="mb-0 text-gold fw-bold d-flex align-items-center gap-2" style="font-family:'Oswald',sans-serif;text-transform:uppercase;letter-spacing:1px;">
                    <i class="bi bi-journal-text"></i> Daftar Reservasi
                </h5>
                <div class="d-flex gap-2 mt-2 mt-md-0">
                    <a href="export-pdf.php?filter=<?= urlencode($filter) ?>" target="_blank" class="btn btn-sm rounded-pill px-3 fw-bold d-flex align-items-center gap-1" style="background:rgba(212,175,55,0.15);color:var(--primary-accent);border:1px solid rgba(212,175,55,0.3);text-decoration:none;">
                        <i class="bi bi-filetype-pdf"></i> Export PDF
                    </a>
                    <button class="btn btn-sm btn-outline-warning rounded-pill px-3" onclick="window.location.reload()"><i class="bi bi-arrow-clockwise me-1"></i>Refresh</button>
                </div>
            </div>

            <!-- Filter Pills -->
            <div class="px-4 pt-3 pb-2 d-flex flex-wrap gap-2">
                <?php
                $filters = [
                    'all'=>['Semua','bi-grid'],
                    'today'=>['Hari Ini','bi-calendar-day'],
                    'pending'=>['Pending','bi-hourglass'],
                    'confirmed'=>['Dikonfirmasi','bi-check-circle'],
                    'completed'=>['Selesai','bi-trophy'],
                    'cancelled'=>['Dibatalkan','bi-x-circle'],
                ];
                foreach ($filters as $key => $f):
                    $active = ($filter === $key) ? 'background:var(--primary-accent);color:#000;font-weight:700;' : 'background:rgba(255,255,255,0.05);color:#a0a0a0;';
                ?>
                <a href="?filter=<?=$key?>" class="btn btn-sm rounded-pill px-3 text-decoration-none d-flex align-items-center gap-1" style="<?=$active?>border:none;font-size:0.8rem;transition:all 0.3s;">
                    <i class="bi <?=$f[1]?>"></i> <?=$f[0]?>
                </a>
                <?php endforeach; ?>
            </div>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead>
                        <tr style="border-bottom:2px solid rgba(212,175,55,0.2);">
                            <th class="ps-4 small text-uppercase fw-bold py-3" style="color:var(--primary-accent);letter-spacing:1px;font-size:0.7rem;">Pelanggan</th>
                            <th class="small text-uppercase fw-bold py-3" style="color:var(--primary-accent);letter-spacing:1px;font-size:0.7rem;">Layanan</th>
                            <th class="small text-uppercase fw-bold py-3" style="color:var(--primary-accent);letter-spacing:1px;font-size:0.7rem;">Jadwal</th>
                            <th class="small text-uppercase fw-bold py-3" style="color:var(--primary-accent);letter-spacing:1px;font-size:0.7rem;">Harga</th>
                            <th class="small text-uppercase fw-bold py-3" style="color:var(--primary-accent);letter-spacing:1px;font-size:0.7rem;">Kontak</th>
                            <th class="small text-uppercase fw-bold py-3 text-center" style="color:var(--primary-accent);letter-spacing:1px;font-size:0.7rem;">Status</th>
                            <th class="small text-uppercase fw-bold py-3 text-center pe-4" style="color:var(--primary-accent);letter-spacing:1px;font-size:0.7rem;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($bookings) > 0): ?>
                            <?php foreach ($bookings as $row): ?>
                                <?php
                                $waDigits = preg_replace('/\D+/', '', (string) $row['phone_number']);
                                $statusMap = [
                                    'Pending' => ['bg'=>'rgba(255,202,44,0.15)','color'=>'#ffca2c','text'=>'Pending','icon'=>'bi-hourglass-split'],
                                    'Confirmed' => ['bg'=>'rgba(117,183,152,0.15)','color'=>'#75b798','text'=>'Dikonfirmasi','icon'=>'bi-check-circle-fill'],
                                    'Arrived' => ['bg'=>'rgba(23,162,184,0.15)','color'=>'#17a2b8','text'=>'Arrived','icon'=>'bi-person-check-fill'],
                                    'In Progress' => ['bg'=>'rgba(212,175,55,0.15)','color'=>'#d4af37','text'=>'In Progress','icon'=>'bi-scissors'],
                                    'Completed' => ['bg'=>'rgba(110,168,254,0.15)','color'=>'#6ea8fe','text'=>'Selesai','icon'=>'bi-trophy-fill'],
                                    'Cancelled' => ['bg'=>'rgba(255,77,77,0.15)','color'=>'#ff6b6b','text'=>'Dibatalkan','icon'=>'bi-x-circle-fill'],
                                ];
                                $st = $statusMap[$row['status']] ?? $statusMap['Pending'];
                                ?>
                            <tr id="row-<?=$row['id']?>" style="border-bottom:1px solid rgba(255,255,255,0.03);">
                                <td class="ps-4">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold flex-shrink-0" style="width:38px;height:38px;background:rgba(212,175,55,0.15);color:var(--primary-accent);font-size:0.85rem;">
                                            <?= strtoupper(substr($row['customer_name'], 0, 1)) ?>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-white" style="font-size:0.9rem;"><?= Utils::sanitize($row['customer_name']) ?></div>
                                            <div class="text-muted" style="font-size:0.7rem;">ID #<?= $row['id'] ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="fw-semibold" style="font-size:0.85rem;"><?= htmlspecialchars((string) $row['service_name'], ENT_QUOTES, 'UTF-8') ?></span></td>
                                <td>
                                    <div class="fw-semibold" style="font-size:0.85rem;"><?= Utils::formatDateIndo($row['reservation_date']) ?></div>
                                    <div class="d-flex align-items-center gap-1 text-muted" style="font-size:0.75rem;"><i class="bi bi-clock"></i><?= htmlspecialchars(substr((string) $row['reservation_time'], 0, 5), ENT_QUOTES, 'UTF-8') ?> WIB</div>
                                </td>
                                <td><span class="text-gold fw-bold" style="font-size:0.9rem;"><?= Utils::formatRupiah($row['price']) ?></span></td>
                                <td>
                                    <a href="https://wa.me/<?= htmlspecialchars($waDigits, ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener noreferrer" class="btn btn-sm rounded-pill px-3 d-inline-flex align-items-center gap-1" style="background:rgba(37,211,102,0.15);color:#25d366;border:1px solid rgba(37,211,102,0.3);font-size:0.75rem;">
                                        <i class="bi bi-whatsapp"></i> Chat
                                    </a>
                                </td>
                                <td class="text-center">
                                    <span class="badge rounded-pill px-3 py-2 d-inline-flex align-items-center gap-1" id="badge-<?=$row['id']?>" style="background:<?=$st['bg']?>;color:<?=$st['color']?>;font-size:0.7rem;font-weight:600;">
                                        <i class="bi <?=$st['icon']?>"></i> <?=$st['text']?>
                                    </span>
                                </td>
                                <td class="text-center pe-4">
                                    <div class="dropdown">
                                        <button class="btn btn-sm rounded-pill px-3 dropdown-toggle" type="button" data-bs-toggle="dropdown" style="background:rgba(255,255,255,0.05);color:#a0a0a0;border:1px solid rgba(255,255,255,0.1);font-size:0.75rem;">
                                            Ubah
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end shadow-lg border-0 rounded-3" style="background:#1e1e1e;min-width:180px;">
                                            <?php if ($row['status'] !== 'Confirmed'): ?>
                                            <li><a class="dropdown-item py-2 d-flex align-items-center gap-2" href="#" onclick="updateStatus(<?=$row['id']?>,'Confirmed');return false;"><i class="bi bi-check-circle text-success"></i> Konfirmasi</a></li>
                                            <?php endif; ?>
                                            <?php if ($row['status'] !== 'Arrived'): ?>
                                            <li><a class="dropdown-item py-2 d-flex align-items-center gap-2" href="#" onclick="updateStatus(<?=$row['id']?>,'Arrived');return false;"><i class="bi bi-person-check text-info"></i> Tiba di Lokasi</a></li>
                                            <?php endif; ?>
                                            <?php if ($row['status'] !== 'In Progress'): ?>
                                            <li><a class="dropdown-item py-2 d-flex align-items-center gap-2" href="#" onclick="updateStatus(<?=$row['id']?>,'In Progress');return false;"><i class="bi bi-scissors text-warning"></i> Sedang Cukur</a></li>
                                            <?php endif; ?>
                                            <?php if ($row['status'] !== 'Completed'): ?>
                                            <li><a class="dropdown-item py-2 d-flex align-items-center gap-2" href="#" onclick="updateStatus(<?=$row['id']?>,'Completed');return false;"><i class="bi bi-trophy" style="color:#6ea8fe;"></i> Tandai Selesai</a></li>
                                            <?php endif; ?>
                                            <?php if ($row['status'] !== 'Cancelled'): ?>
                                            <li><hr class="dropdown-divider" style="border-color:rgba(255,255,255,0.1);"></li>
                                            <li><a class="dropdown-item py-2 d-flex align-items-center gap-2" href="#" onclick="updateStatus(<?=$row['id']?>,'Cancelled');return false;"><i class="bi bi-x-circle text-danger"></i> Batalkan</a></li>
                                            <?php endif; ?>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div class="py-4">
                                        <i class="bi bi-inbox fs-1 text-muted d-block mb-3"></i>
                                        <h6 class="text-muted fw-semibold">Belum ada data reservasi</h6>
                                        <p class="text-muted small mb-0">Reservasi baru akan muncul di sini.</p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <!-- Count -->
            <div class="px-4 py-3 text-muted small d-flex justify-content-between" style="border-top:1px solid rgba(255,255,255,0.05);">
                <span>Menampilkan <strong class="text-white"><?= count($bookings) ?></strong> reservasi</span>
                <span>Filter: <strong class="text-gold"><?= $filters[$filter][0] ?? 'Semua' ?></strong></span>
            </div>
        </div>
    </div>

    <!-- Toast notification -->
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index:11">
        <div id="statusToast" class="toast align-items-center border-0 rounded-3 shadow-lg" role="alert" style="background:#1e1e1e;border:1px solid rgba(212,175,55,0.2) !important;">
            <div class="d-flex">
                <div class="toast-body d-flex align-items-center gap-2 text-white" id="toastBody">
                    <i class="bi bi-check-circle-fill text-success"></i> Status berhasil diperbarui.
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    </div>

    <script>
    const csrfToken = '<?= htmlspecialchars($csrf, ENT_QUOTES, "UTF-8") ?>';
    const statusConfig = {
        Confirmed: {bg:'rgba(117,183,152,0.15)',color:'#75b798',text:'Dikonfirmasi',icon:'bi-check-circle-fill'},
        Arrived: {bg:'rgba(23,162,184,0.15)',color:'#17a2b8',text:'Arrived',icon:'bi-person-check-fill'},
        'In Progress': {bg:'rgba(212,175,55,0.15)',color:'#d4af37',text:'In Progress',icon:'bi-scissors'},
        Completed: {bg:'rgba(110,168,254,0.15)',color:'#6ea8fe',text:'Selesai',icon:'bi-trophy-fill'},
        Cancelled: {bg:'rgba(255,77,77,0.15)',color:'#ff6b6b',text:'Dibatalkan',icon:'bi-x-circle-fill'},
        Pending: {bg:'rgba(255,202,44,0.15)',color:'#ffca2c',text:'Pending',icon:'bi-hourglass-split'}
    };

    function updateStatus(id, status) {
        const fd = new FormData();
        fd.append('ajax_action', 'update_status');
        fd.append('csrf_token', csrfToken);
        fd.append('id', id);
        fd.append('status', status);

        fetch('', {method:'POST', body: fd})
            .then(r => r.json())
            .then(data => {
                if (data.ok) {
                    const badge = document.getElementById('badge-' + id);
                    const cfg = statusConfig[status];
                    if (badge && cfg) {
                        badge.style.background = cfg.bg;
                        badge.style.color = cfg.color;
                        badge.innerHTML = '<i class="bi ' + cfg.icon + '"></i> ' + cfg.text;
                    }
                    showToast(true, 'Status berhasil diperbarui!');
                } else {
                    showToast(false, data.message || 'Gagal memperbarui status.');
                }
            })
            .catch(() => showToast(false, 'Terjadi kesalahan jaringan.'));
    }

    function showToast(success, msg) {
        const body = document.getElementById('toastBody');
        const icon = success ? 'bi-check-circle-fill text-success' : 'bi-exclamation-circle-fill text-danger';
        body.innerHTML = '<i class="bi ' + icon + '"></i> ' + msg;
        const toast = new bootstrap.Toast(document.getElementById('statusToast'));
        toast.show();
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Inisialisasi grafik
        const ctx = document.getElementById('reservationsChart');
        if (ctx) {
            const chartLabels = <?= json_encode($chartLabels) ?>;
            const chartCounts = <?= json_encode($chartCounts) ?>;
            
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: chartLabels,
                    datasets: [{
                        label: 'Reservasi',
                        data: chartCounts,
                        borderColor: '#d4af37',
                        backgroundColor: 'rgba(212,175,55,0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#d4af37',
                        pointBorderColor: '#1e1e1e'
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { stepSize: 1, color: '#a0a0a0' },
                            grid: { color: 'rgba(255,255,255,0.05)' }
                        },
                        x: {
                            ticks: { color: '#a0a0a0' },
                            grid: { color: 'rgba(255,255,255,0.05)' }
                        }
                    }
                }
            });
        }

        // Inisialisasi kalender
        const calendarEl = document.getElementById('calendar');
        if (calendarEl) {
            const events = <?= json_encode($calendarEvents) ?>;
            
            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'timeGridWeek',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'timeGridWeek,timeGridDay'
                },
                slotMinTime: '09:00:00',
                slotMaxTime: '21:00:00',
                allDaySlot: false,
                events: events,
                height: 400,
                eventClick: function(info) {
                    // Scroll ke baris
                    const rowId = 'row-' + info.event.id;
                    const row = document.getElementById(rowId);
                    if (row) {
                        row.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        row.style.backgroundColor = 'rgba(212,175,55,0.2)';
                        setTimeout(() => row.style.backgroundColor = '', 2000);
                    }
                }
            });
            calendar.render();
            
            // Perbaiki ukuran kalender di grid
            setTimeout(() => calendar.updateSize(), 200);
        }
    });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
