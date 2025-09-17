<?php
header('Content-Type: application/json');

$host = 'localhost';
$dbname = 'smart_laundry';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);

    // Join user + profile info for non-admins
    $stmt = $pdo->query("
    SELECT 
        s.user_id,
        s.first_name,
        s.last_name,
        s.email,
        s.phone_number,
        s.created_at,
        p.date_of_birth,
        p.address,
        p.education,
        p.occupation
    FROM sign_up s
    LEFT JOIN user_profile p ON s.user_id = p.user_id
    WHERE s.role = 'user'
    ORDER BY s.created_at DESC
");

    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($users);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
