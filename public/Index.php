<?php 

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../app/Controller/BookingController.php';
require_once __DIR__ . '/../app/Config/Database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $booking = new BookingController();
    $result = $booking->createBooking($_POST);
    if ($result == "SUCCESS") {
        header("Location: success.php");
        exit();
    } elseif ($result == "CLASH") {
        $error_message = "Maaf, slot waktu sudah dibooking orang lain. Silakan pilih jam atau tanggal lain.";
    } else {
        $error_message = "Terjadi kesalahan sistem. Silakan coba lagi.";
    }
}

$db = (new Database())->getConnection();
$stmt = $db->query("SELECT * FROM services");
$services = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php if ($error_message): ?>
    <div class="alert alert-danger border-0 shadow-sm mb-4" style="background-color: #ff4d4d; color: white;">
        <?= $error_message ?>
    </div>
<?php endif; ?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CukurGo - Booking Barbershop Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="bg-light">

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-lg border-0">
                    <div class="card-header bg-dark text-white p-3">
                        <h4 class="mb-0 text-center">CukurGo Booking</h4>
                    </div>
                    <div class="card-body p-4">
                        <form action="" method="POST">
                            <div class="mb-3">
                                <label class="form-label">Nama Lengkap</label>
                                <input type="text" name="name" class="form-control" placeholder="Masukkan nama Anda" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Nomor WhatsApp</label>
                                <input type="text" name="phone" class="form-control" placeholder="Contoh: 0812345678" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Pilih Layanan</label>
                                <select name="service_id" class="form-select" required>
                                    <option value="">-- Pilih Layanan --</option>
                                    <?php foreach($services as $s): ?>
                                        <option value="<?= $s['id'] ?>"><?= $s['name'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="row">
                                <div class="col-6 mb-3">
                                    <label class="form-label">Tanggal</label>
                                    <input type="date" name="date" class="form-control" required min="<?= date('Y-m-d') ?>">
                                </div>
                                <div class="col-6 mb-3">
                                    <label class="form-label">Jam</label>
                                    <input type="time" name="time" class="form-control" required>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-dark w-100 py-2">Booking Sekarang</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>