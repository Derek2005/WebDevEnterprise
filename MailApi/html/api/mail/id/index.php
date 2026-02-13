<?php
require '../../../vendor/autoload.php';

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

$uri = $_SERVER['REQUEST_URI'];
$parts = explode('/', trim($uri, '/'));
$idRaw = end($parts);

if (!ctype_digit($idRaw)) {
    $page->badRequest("id must be a number");
    exit;
}

$id = (int)$idRaw;
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $row = $mail->getMail($id);
    if ($row === false) {
        $page->notFound();
        exit;
    }
    $page->item($row);
    exit;
}

if ($method === 'PUT') {
    if ($mail->getMail($id) === false) {
        $page->notFound();
        exit;
    }

    $json = file_get_contents("php://input");
    $data = json_decode($json, true);

    if (!is_array($data) || empty($data['subject']) || empty($data['body'])) {
        $page->badRequest("subject and body are required");
        exit;
    }

    $ok = $mail->updateMail($id, $data['subject'], $data['body']);
    if (!$ok) {
        $page->notFound();
        exit;
    }

    $page->item(["updated" => true]);
    exit;
}

if ($method === 'DELETE') {
    if ($mail->getMail($id) === false) {
        $page->notFound();
        exit;
    }

    $ok = $mail->deleteMail($id);
    if (!$ok) {
        $page->notFound();
        exit;
    }

    $page->item(["deleted" => true]);
    exit;
}

$page->badRequest();
