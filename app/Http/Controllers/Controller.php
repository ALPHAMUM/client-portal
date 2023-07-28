<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use League\OAuth1\Client\Server\Server;
use League\OAuth1\Client\Signature\Signature;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function getHttpClient()
    {
        $signature = new Signature(config('services.netsuite'));
        $stack = HandlerStack::create();
        $middleware = $this->createAuthMiddleware();
        $stack->push($middleware);

        return new Client(['handler' => $stack]);
    }

    public function request($uri, $method = 'GET', $body = [], array $headers = [])
    {
        $response = $this->getHttpClient()->request($method, $uri, [
            'headers' => $headers,
            'form_params' => $body,
        ]);

        return $response->getBody();
    }

    public function getNetsuiteData()
    {
        // $service = $this->request();

        $response = $this->request('https://5184893-sb1.suitetalk.api.netsuite.com/services/rest/record/v1/customer', 'GET', [
            // Add any query parameters or request body as needed
        ]);

        // Process the API response as per your requirement
        return $response;
    }
}
