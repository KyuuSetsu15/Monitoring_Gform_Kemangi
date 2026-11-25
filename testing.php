<?php

session_start();
require_once 'db.php';

// PENGAMAN SESSION: Jika belum login, lempar ke login.php
if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
    header("Location: login.php");
    exit();
}
$apiUrl = "https://script.google.com/macros/s/AKfycbw_Tcs-SJ8dV-f99jzj6ikEIOs9DqnUscRnzPqSnByqYnBNoNZ_h74UnrJB6ZqVeiOkKg/exec"; 

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_TIMEOUT, 30); // Naikkan jadi 30 detik
$response = curl_exec($ch);
curl_close($ch);

$raw_data = json_decode($response, true);

// =========================================================================
// TAMBAHAN: BACKUP OTOMATIS KE MARIADB & FALLBACK SAAT OFFLINE
// =========================================================================
if (is_array($raw_data) && count($raw_data) > 0) {
    // Jika Google Sheets berhasil ditarik -> Simpan snapshot ke MariaDB
    $data_peserta = $raw_data;
    mysqli_query($conn, "TRUNCATE TABLE peserta_backup");
    
    $stmt = mysqli_prepare($conn, "INSERT INTO peserta_backup (raw_json) VALUES (?)");
    foreach ($data_peserta as $row_item) {
        $json_str = json_encode($row_item, JSON_UNESCAPED_UNICODE);
        mysqli_stmt_bind_param($stmt, "s", $json_str);
        mysqli_stmt_execute($stmt);
    }
    mysqli_stmt_close($stmt);
} else {
    // Jika Google Sheets gagal / kosong -> Ambil dari backup MariaDB
    $data_peserta = [];
    $res_backup = mysqli_query($conn, "SELECT raw_json FROM peserta_backup");
    if ($res_backup) {
        while ($b_row = mysqli_fetch_assoc($res_backup)) {
            $decoded = json_decode($b_row['raw_json'], true);
            if (is_array($decoded)) {
                $data_peserta[] = $decoded;
            }
        }
    }
}
// =========================================================================

// HELPER AKURAT BERSAHAJA (KODE ASLIMU - UTUH)
function getValData($row, $targetKey) {
    if (!is_array($row)) {
        return '';
    }

    // Ubah menjadi huruf kecil terlebih dahulu,
    // kemudian hapus karakter selain a-z dan 0-9.
    $cleanTarget = preg_replace(
        '/[^a-z0-9]/',
        '',
        strtolower(trim((string)$targetKey))
    );

    foreach ($row as $key => $val) {

        $cleanKey = preg_replace(
            '/[^a-z0-9]/',
            '',
            strtolower(trim((string)$key))
        );

        if ($cleanKey === $cleanTarget) {
            return is_string($val) ? trim($val) : $val;
        }
    }

    return '';
}

function getVal($row, $key = '') {
    return getValData($row, $key);
}

function getValStrict($row, $key = '') {
    return getValData($row, $key);
}

// Statistik (KODE ASLIMU - UTUH)
$total_peserta = count($data_peserta);
$total_skor = 0;
$peserta_lulus = 0;

foreach ($data_peserta as $p) {
    $skor_raw = getValData($p, 'Score');
    if ($skor_raw === '') $skor_raw = getValData($p, 'SKOR');
    
    $skor_val = (int)preg_replace('/[^0-9]/', '', explode('/', (string)$skor_raw)[0] ?? 0);
    $total_skor += $skor_val;
    if ($skor_val >= 20) $peserta_lulus++;
}
$rata_skor = $total_peserta > 0 ? round($total_skor / $total_peserta, 1) : 0;

// Dropdown Unik (KODE ASLIMU - UTUH)
$list_kecamatan = array_values(array_filter(array_unique(array_map(function($r) { return getValData($r, 'KECAMATAN'); }, $data_peserta))));
$list_kelurahan = array_values(array_filter(array_unique(array_map(function($r) { return getValData($r, 'KELURAHAN'); }, $data_peserta))));
$list_pertemuan = array_values(array_filter(array_unique(array_map(function($r) { 
    $v = getValData($r, 'PERTEMUAN KE-');
    return $v !== '' ? $v : getValData($r, 'PERTEMUAN KE');
}, $data_peserta))));

sort($list_pertemuan);
sort($list_kecamatan);
sort($list_kelurahan);

// Filter (KODE ASLIMU - UTUH)
$filter_kec  = $_GET['kecamatan'] ?? '';
$filter_kel  = $_GET['kelurahan'] ?? '';
$filter_pert = $_GET['pertemuan'] ?? '';
$search_nama = $_GET['nama'] ?? '';

$filtered_data = array_filter($data_peserta, function ($row) use ($filter_kec, $filter_kel, $filter_pert, $search_nama) {
    $val_kec  = getValData($row, 'KECAMATAN');
    $val_kel  = getValData($row, 'KELURAHAN');
    $val_pert = getValData($row, 'PERTEMUAN KE-');
    if ($val_pert === '') $val_pert = getValData($row, 'PERTEMUAN KE');
    $val_nama = getValData($row, 'NAMA LENGKAP PESERTA');

    $match_kec  = empty($filter_kec)  || strtolower($val_kec)  === strtolower($filter_kec);
    $match_kel  = empty($filter_kel)  || strtolower($val_kel)  === strtolower($filter_kel);
    $match_pert = empty($filter_pert) || (string)$val_pert     === (string)$filter_pert;
    $match_nama = empty($search_nama) || stripos($val_nama, $search_nama) !== false;

    return $match_kec && $match_kel && $match_pert && $match_nama;
});
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Monitoring Kemangi</title>
    <link rel="stylesheet" href="testing.css">
</head>
<body>

<div class="app-shell">
    <aside class="sidebar">
    <div class="brand">
        <div class="brand-mark"><span>R</span></div>
        <div><strong>RekapTes</strong><small>Monitoring Peserta</small></div>
    </div>
    <nav>
        <button class="nav-item active">Dashboard Peserta</button>
        
        <!-- 1. UBAH TOMBOL KELUAR DI SINI (Arahkan ke logout.php) -->
        <a href="logout.php" class="nav-item logout">Keluar</a>
    </nav>
    <div class="sidebar-note">
        <span>RT</span>
        <div>
            <!-- 2. UBAH NAMA ADMIN DI SINI (Gunakan Session) -->
            <b><?= htmlspecialchars($_SESSION['admin_name'] ?? 'Admin') ?></b>
            <small>Administrator</small>
        </div>
    </div>
</aside>

    <main>
        <header>
            <div>
                <p class="eyebrow">DASHBOARD MONITORING</p>
                <h1>Hasil Tes & Kehadiran</h1>
                <p class="subtitle">Data peserta dari integrasi Google Form secara real-time.</p>
            </div>
            <div class="location-badge">📍 <span>Surabaya, Jawa Timur</span></div>
        </header>

        <section class="stats">
            <article>
                <div class="stat-icon purple">👥</div>
                <div><span>Total Peserta</span><strong><?= $total_peserta ?></strong></div>
            </article>
            <article>
                <div class="stat-icon blue">⭐</div>
                <div><span>Rata-Rata Skor</span><strong><?= $rata_skor ?></strong></div>
            </article>
            <article>
                <div class="stat-icon green">🎯</div>
                <div><span>Peserta Lulus (≥20)</span><strong><?= $peserta_lulus ?></strong></div>
            </article>
        </section>

        <div class="content-card">
            <form method="GET" class="filters">
                <div>
                    <label>Kecamatan</label>
                    <select name="kecamatan" onchange="this.form.submit()">
                        <option value="">Semua Kecamatan</option>
                        <?php foreach ($list_kecamatan as $kec): ?>
                            <option value="<?= htmlspecialchars($kec) ?>" <?= $filter_kec == $kec ? 'selected' : '' ?>><?= htmlspecialchars($kec) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label>Kelurahan</label>
                    <select name="kelurahan" onchange="this.form.submit()">
                        <option value="">Semua Kelurahan</option>
                        <?php foreach ($list_kelurahan as $kel): ?>
                            <option value="<?= htmlspecialchars($kel) ?>" <?= $filter_kel == $kel ? 'selected' : '' ?>><?= htmlspecialchars($kel) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label>Pertemuan</label>
                    <select name="pertemuan" onchange="this.form.submit()">
                        <option value="">Semua Pertemuan</option>
                        <?php foreach ($list_pertemuan as $pert): ?>
                            <option value="<?= htmlspecialchars($pert) ?>" <?= $filter_pert == $pert ? 'selected' : '' ?>>Pertemuan <?= htmlspecialchars($pert) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label>&nbsp;</label>
                    <a href="?" class="nav-item" style="border: 1px solid var(--border); text-align: center; justify-content: center; background: #fff; color: #333; text-decoration:none;">Reset Filter</a>
                </div>
            </form>

            <div class="table-heading">
                <div>
                    <h2>Daftar Hasil Tes</h2>
                    <p>Menampilkan <?= count($filtered_data) ?> data peserta</p>
                </div>
                <form method="GET" class="search">
                    <input type="text" name="nama" placeholder="Cari nama peserta..." value="<?= htmlspecialchars($search_nama) ?>">
                </form>
            </div>

            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Peserta</th>
                            <th>Kecamatan / Kelurahan</th>
                            <th>RW</th>
                            <th>Tgl Kegiatan</th>
                            <th>Pertemuan</th>
                            <th>Jenis Soal</th>
                            <th>Skor</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($filtered_data)): ?>
                            <?php foreach ($filtered_data as $row): ?>
                                <?php 
                                    $nama       = getValData($row, 'NAMA LENGKAP PESERTA') ?: 'Anonim';
                                    $timestamp  = getValData($row, 'Timestamp');
                                    $kecamatan  = getValData($row, 'KECAMATAN');
                                    $kelurahan  = getValData($row, 'KELURAHAN');
                                    $rw         = getValData($row, 'RW');
                                    $tgl_keg    = getValData($row, 'TANGGAL KEGIATAN');
                                    
                                    $pertemuan  = getValData($row, 'PERTEMUAN KE-');
                                    if ($pertemuan === '') $pertemuan = getValData($row, 'PERTEMUAN KE');
                                    
                                    $jenis_soal = getValData($row, 'PILIHAN SOAL');
                                    $skor_raw   = getValData($row, 'Score');
                                    if ($skor_raw === '') $skor_raw = getValData($row, 'SKOR');

                                    $inisiatif  = strtoupper(substr($nama, 0, 1));
                                    $is_post    = (strtoupper($jenis_soal) == 'POST TEST');
                                    $skor_num   = (int)preg_replace('/[^0-9]/', '', explode('/', (string)$skor_raw)[0] ?? 0);
                                ?>
                                <tr>
                                    <td>
                                        <div class="avatar"><?= $inisiatif ?></div>
                                        <div>
                                            <b><?= htmlspecialchars($nama) ?></b>
                                            <small><?= htmlspecialchars($timestamp ?: '-') ?></small>
                                        </div>
                                    </td>
                                    <td>
                                        <b><?= htmlspecialchars($kecamatan ?: '-') ?></b>
                                        <small><?= htmlspecialchars($kelurahan ?: '-') ?></small>
                                    </td>
                                    <td><?= htmlspecialchars($rw ?: '-') ?></td>
                                    <td><?= htmlspecialchars($tgl_keg ?: '-') ?></td>
                                    <td><b>P-<?= htmlspecialchars($pertemuan ?: '-') ?></b></td>
                                    <td>
                                        <span class="test-pill <?= $is_post ? 'post' : 'pre' ?>">
                                            <?= htmlspecialchars($jenis_soal ?: '-') ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="score <?= $skor_num >= 20 ? 'high' : '' ?>">
                                            <?= htmlspecialchars((string)$skor_raw ?: '0') ?>
                                        </div>
                                    </td>
                                    <td>
                                        <button class="eye-button" onclick="showModal(<?= htmlspecialchars(json_encode($row)) ?>)">👁️</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="empty">Data tidak ditemukan.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<div class="modal-backdrop" id="modalDetail" style="display: none;">
    <div class="modal">
        <button class="close" onclick="closeModal()">✕</button>
        <div class="modal-head">
            <div class="large-avatar" id="modalAvatar">A</div>
            <div>
                <p>DETAIL PESERTA</p>
                <h2 id="modalNama">Nama Peserta</h2>
                <span id="modalWaktu">🕒 -</span>
            </div>
        </div>
        <div class="detail-grid">
            <div><label>Kecamatan</label><strong id="modalKec">-</strong></div>
            <div><label>Kelurahan / RW</label><strong id="modalKelRw">-</strong></div>
            <div><label>Tanggal Kegiatan</label><strong id="modalTgl">-</strong></div>
            <div><label>Pertemuan Ke-</label><strong id="modalPert">-</strong></div>
            <div><label>Narasumber</label><strong id="modalNarasumber">-</strong></div>
            <div><label>Kelas Kemangi</label><strong id="modalKelas">-</strong></div>
            <div class="full"><label>Tahapan</label><strong id="modalTahapan">-</strong></div>
        </div>
        <div class="result-box">
            <div>
                <small>JENIS SOAL: <span id="modalJenisSoal" style="display:inline; font-weight:bold;">-</span></small>
                <b>SKOR: <span id="modalSkor">0</span></b>
            </div>
            <span>Kemangi Monitoring</span>
        </div>
    </div>
</div>

<script>
function getJSValStrict(obj, keyTarget) {
    if (!obj) return '-';
    let targetClean = keyTarget.toLowerCase().replace(/[^a-z0-9]/g, '');
    for (let k in obj) {
        let cleanK = k.toLowerCase().replace(/[^a-z0-9]/g, '');
        if (cleanK === targetClean && obj[k] !== '') {
            return obj[k];
        }
    }
    return '-';
}

function showModal(data) {
    let nama       = getJSValStrict(data, 'NAMA LENGKAP PESERTA');
    let timestamp  = getJSValStrict(data, 'Timestamp');
    let kecamatan  = getJSValStrict(data, 'KECAMATAN');
    let kelurahan  = getJSValStrict(data, 'KELURAHAN');
    let rw         = getJSValStrict(data, 'RW');
    let tgl        = getJSValStrict(data, 'TANGGAL KEGIATAN');
    let pertemuan  = getJSValStrict(data, 'PERTEMUAN KE-');
    if (pertemuan === '-') pertemuan = getJSValStrict(data, 'PERTEMUAN KE');
    let narasumber = getJSValStrict(data, 'NAMA NARASUMBER');
    let kelas      = getJSValStrict(data, 'NAMA KELAS KEMANGI');
    let tahapan    = getJSValStrict(data, 'Pilihan Tahapan');
    let jenisSoal  = getJSValStrict(data, 'PILIHAN SOAL');
    let skor       = getJSValStrict(data, 'Score');
    if (skor === '-') skor = getJSValStrict(data, 'SKOR');

    document.getElementById('modalAvatar').innerText = nama !== '-' ? nama.charAt(0).toUpperCase() : 'A';
    document.getElementById('modalNama').innerText = nama;
    document.getElementById('modalWaktu').innerText = '🕒 ' + timestamp;
    document.getElementById('modalKec').innerText = kecamatan;
    document.getElementById('modalKelRw').innerText = kelurahan + ' / RW ' + rw;
    document.getElementById('modalTgl').innerText = tgl;
    document.getElementById('modalPert').innerText = 'Pertemuan ' + pertemuan;
    document.getElementById('modalNarasumber').innerText = narasumber;
    document.getElementById('modalKelas').innerText = kelas;
    document.getElementById('modalTahapan').innerText = tahapan;
    document.getElementById('modalJenisSoal').innerText = jenisSoal;
    document.getElementById('modalSkor').innerText = skor;
    
    document.getElementById('modalDetail').style.display = 'grid';
}

function closeModal() {
    document.getElementById('modalDetail').style.display = 'none';
}
</script>

</body>
</html>