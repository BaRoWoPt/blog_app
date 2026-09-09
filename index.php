<?php
$host = 'mysql-local';
$dbname = 'thuctap';
$user = 'root';
$password = 'rootpassword';

try {
    $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
    $pdo = new PDO($dsn, $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);

    echo "<h2>Kết nối thành công đến database: {$dbname}</h2>";

    // Lấy dữ liệu từ bảng sinhvien của CSDL thuctap
    $stmt = $pdo->query("SELECT * FROM sinhvien");
    $sinhviens = $stmt->fetchAll();

    echo "<table border='1' cellpadding='8' cellspacing='0' style='border-collapse: collapse; font-family: sans-serif;'>";
    echo "<tr style='background-color: #f2f2f2;'>
            <th>Mã SV</th>
            <th>Họ Tên</th>
            <th>Khoa</th>
            <th>Năm sinh</th>
            <th>Quê quán</th>
          </tr>";

    foreach ($sinhviens as $sv) {
        echo "<tr>";
        echo "<td>{$sv['masv']}</td>";
        echo "<td>{$sv['hotensv']}</td>";
        echo "<td>{$sv['makhoa']}</td>";
        echo "<td>{$sv['namsinh']}</td>";
        echo "<td>{$sv['quequan']}</td>";
        echo "</tr>";
    }
    echo "</table>";

} catch (PDOException $e) {
    echo "<h2>Lỗi kết nối CSDL:</h2> " . $e->getMessage();
}