<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "it_department";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $input_username = $_POST['username'];
    $input_password = $_POST['password'];

    $sql = "SELECT username, password, role FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $input_username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 1) {
        $stmt->bind_result($username, $hashed_password, $role);
        $stmt->fetch();

        if (password_verify($input_password, $hashed_password)) {
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $role;
            
            if ($role == 'admin') {
                echo "<script>
                alert('เข้าสู่ระบบสำเร็จ');
                window.location.href = 'admin.php';
                </script>";
                exit;
            } elseif ($role == 'member') {
                echo "<script>
                alert('เข้าสู่ระบบสำเร็จ');
                window.location.href = 'report.php';
                </script>";
                exit;
            } else {
                echo "<script>
                alert('ไม่สามารถเข้าสู่ระบบได้');
                window.location.href = 'login.html';
                </script>";
            }
        } else {
            echo "<script>
            alert('รหัสผ่านไม่ถูกต้อง');
            window.location.href = 'login.html';
            </script>";
        }
    } else {
        echo "<script>
        alert('ชื่อผู้ใช้ไม่ถูกต้อง');
        window.location.href = 'login.html';
        </script>";
    }

    $stmt->close();
} else {
    echo "<script>
    alert('กรุณาล็อคอินเข้าสู่ระบบก่อน');
    window.location.href = 'login.html';
    </script>";
}

$conn->close();
?>
