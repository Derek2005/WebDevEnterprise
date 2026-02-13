<?php
namespace Application;

class Page
{
    private function headerJson(): void
    {
        header('Content-Type: application/json; charset=utf-8');
    }

    public function list(array $items): void
    {
        $this->headerJson();
        http_response_code(200);
        echo json_encode($items);
    }

    public function item($item = false): void
    {
        $this->headerJson();
        http_response_code(200);
        if ($item !== false) {
            echo json_encode($item);
        }
    }

    public function created($payload): void
    {
        $this->headerJson();
        http_response_code(201);
        echo json_encode($payload);
    }

    public function notFound(): void
    {
        $this->headerJson();
        http_response_code(404);
        echo json_encode(["error" => "Not found"]);
    }

    public function badRequest(string $msg = "Bad request"): void
    {
        $this->headerJson();
        http_response_code(400);
        echo json_encode(["error" => $msg]);
    }
}
