<?php
header("Content-Type: application/json");
include "db.php";

$data = json_decode(file_get_contents("php://input"), true);
$email = $data["email"];
$password = $data["password"];

$sql = "SELECT * FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

$user = $result->fetch_assoc();

if ($user && password_verify($password, $user["password"])) {
  echo json_encode([
    "success" => true,
    "message" => "Login berhasil",
    "role" => $user["role"],
    "name" => $user["name"]
  ]);
} else {
  echo json_encode(["success" => false, "message" => "Email atau password salah"]);
}
session_start();
$_SESSION['user_id'] = $user['id'];
$_SESSION['user_name'] = $user['name'];
$_SESSION['role'] = $user['role'];

exit;
