<?php


namespace App\Service;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ResponseService
{
    public function getStatus(Request $request) {
        $response = new Response();
        $statusCode = $response->getStatusCode();
        $status = '';
        if ($request->server->get('REQUEST_METHOD') == 'POST') {
            $status = ($statusCode == 200) ? 'success' : 'error';
        }
        return $status;
    }
}
