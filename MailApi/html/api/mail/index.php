<?php
require '../../vendor/autoload.php';

use Application\Mail;
use Application\Page;

$dsn = "pgsql:host=" . getenv('DB_PROD_HOST') . ";dbname=" . getenv('DB_PROD_NAME');

try {
    $pdo = new PDO($dsn, getenv('DB_USER'), getenv('DB_PASS'), [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(["error" => "Database connection failed"]);
    exit;
}

$mail = new Mail($pdo);
$page = new Page();

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $page->list($mail->getAllMail());
    exit;
}

if ($method === 'POST') {
    $json = file_get_contents("php://input");
    $data = json_decode($json, true);

    if (!is_array($data) || empty($data['subject']) || empty($data['body'])) {
        $page->badRequest("subject and body are required");
        exit;
    }

    $id = $mail->createMail($data['subject'], $data['body']);
    $page->created(["id" => $id]);
    exit;
}

$page->badRequest();
