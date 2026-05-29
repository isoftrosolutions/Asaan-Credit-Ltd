<?php
namespace App\Http\Controllers;

class Controller
{
    protected function render($view, $data = [])
    {
        return \view($view, $data);
    }

    protected function redirect($path)
    {
        \redirect($path);
    }

    protected function back()
    {
        \back();
    }

    protected function json($data, $code = 200)
    {
        http_response_code($code);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}
