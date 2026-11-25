<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "kemangi_db";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

function authenticate_user($username, $password) {
    global $conn;
    
    $stmt = mysqli_prepare($conn, "SELECT username, password, nama_admin, kecamatan FROM users WHERE username = ? LIMIT 1");
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if ($row = mysqli_fetch_assoc($result)) {
        $input_hash = strtolower(hash('sha256', trim($password)));
        $db_hash    = strtolower(trim($row['password']));
        
        if ($input_hash === $db_hash) {
            return [
                'username'  => $row['username'],
                'nama'      => $row['nama_admin'],
                'kecamatan' => $row['kecamatan']
            ];
        }
    }
    return false;
}
?>