<?php
namespace Application;

class Page
{
    public function badRequest($message = "Bad Request")
    {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(["error" => $message]);
    }

    public function unauthorized($message = "Unauthorized")
    {
        http_response_code(401);
        header('Content-Type: application/json');
        echo json_encode(["error" => $message]);
    }

    public function item($data)
    {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($data);
    }
}