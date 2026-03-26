<?php
require __DIR__ . '/../../../autoload.php';

use Application\Mail;
use Application\Database;
use Application\Page;
use Application\Verifier;

$database = new Database('prod');
$page = new Page();
$mail = new Mail($database->getDb());

if (!isset($_SERVER['HTTP_AUTHORIZATION'])) {
    http_response_code(401);
    header('Content-Type: application/json');
    echo json_encode(["error" => "Missing Authorization header"]);
    exit;
}

try {
    $verifier = new Verifier();
    $verifier->decode($_SERVER['HTTP_AUTHORIZATION']);
} catch (\Throwable $e) {
    http_response_code(401);
    header('Content-Type: application/json');
    echo json_encode(["error" => "Invalid token"]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (is_array($data) && array_key_exists('name', $data) && array_key_exists('message', $data)) {
        $id = $mail->createMail($data['name'], $data['message'], $verifier->userId);
        $page->item(["id" => $id]);
    } else {
        $page->badRequest();
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $page->item($mail->listMail($verifier->userId, $verifier->role));
} else {
    $page->badRequest();
}