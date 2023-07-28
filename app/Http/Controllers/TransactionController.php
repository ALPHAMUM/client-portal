<?php

namespace App\Http\Controllers;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
// use GuzzleHttp\Client;
// use GuzzleHttp\Psr7\Request;
// use Illuminate\Http\Request;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Subscriber\Oauth\Oauth1;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use League\OAuth1\Client\Server\NetSuite;


class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function show($customer='')
    {

        // Your OAuth 1.0 credentials
        $oauthConsumerKey = config('app.netsuite_api_consumer_key');
        $oauthConsumerSecret =config('app.netsuite_api_consumer_secret');
        $oauthToken = config('app.netsuite_api_token_id');
        $oauthTokenSecret = config('app.netsuite_api_token_secret');
        $oauthRealm = config('app.netsuite_api_realm');

        
        // The base URL and endpoint for the API you want to access
        $baseUrl =  config('app.netsuite_api_base_uri');
        $customer_id = isset($customer)?'/'.$customer:'';
        $endpoint = '/services/rest/record/v1/customer'. $customer_id;
        // $endpoint = '/services/rest/record/v1/customerPayment/1174940';


        // Generate the OAuth signature and headers
        $timestamp = time();
        $nonce = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 11);

        $oauthSignatureMethod = 'HMAC-SHA256';
        $oauthVersion = '1.0';

        $signatureBaseString = rawurlencode('GET') . '&' . rawurlencode($baseUrl . $endpoint) . '&';
        $signatureBaseString .= rawurlencode('oauth_consumer_key=' . $oauthConsumerKey . '&');
        $signatureBaseString .= rawurlencode('oauth_nonce=' . $nonce . '&');
        $signatureBaseString .= rawurlencode('oauth_signature_method=' . $oauthSignatureMethod . '&');
        $signatureBaseString .= rawurlencode('oauth_timestamp=' . $timestamp . '&');
        $signatureBaseString .= rawurlencode('oauth_token=' . $oauthToken . '&');
        $signatureBaseString .= rawurlencode('oauth_version=' . $oauthVersion);

        $signatureKey = rawurlencode($oauthConsumerSecret) . '&' . rawurlencode($oauthTokenSecret);
        $oauthSignature = base64_encode(hash_hmac('sha256', $signatureBaseString, $signatureKey, true));

        // Assemble the Authorization header
        $authorizationHeader = 'OAuth ';
        $authorizationHeader .= 'realm="' . rawurlencode($oauthRealm) . '", ';
        $authorizationHeader .= 'oauth_consumer_key="' . rawurlencode($oauthConsumerKey) . '", ';
        $authorizationHeader .= 'oauth_token="' . rawurlencode($oauthToken) . '", ';
        $authorizationHeader .= 'oauth_signature_method="' . rawurlencode($oauthSignatureMethod) . '", ';
        $authorizationHeader .= 'oauth_timestamp="' . rawurlencode($timestamp) . '", ';
        $authorizationHeader .= 'oauth_nonce="' . rawurlencode($nonce) . '", ';
        $authorizationHeader .= 'oauth_signature="' . rawurlencode($oauthSignature) . '", ';
        // $authorizationHeader .= 'oauth_signature="", ';
        $authorizationHeader .= 'oauth_version="' . rawurlencode($oauthVersion) . '"';

        // Make the actual HTTP request with the custom headers
        $response = Http::withHeaders([
            'Authorization' => $authorizationHeader,
            'Accept' => '*/*',
            'Accept-Encoding' => 'gzip, deflat, br',
            'Connection' => 'keep-alive',
            "User-Agent" => '',

        ])->get($baseUrl . $endpoint);

        // Handle the response
        if ($response->successful()) {
            // Request was successful, handle the response data
            $data = $response->json();
            // dd($data)['items'][0]['links'];
            dd($data);
        } else {
            // Request failed, handle the error
            $statusCode = $response->status();
            $errorData = $response->json();
            echo $baseUrl . $endpoint;
            dd($errorData);
        }

    }

    public function apiRequest(){
        $baseUrl = config('app.netsuite_api_base_uri');
        $consumerKey = config('app.netsuite_api_consumer_key');
        $consumerSecret = config('app.netsuite_api_consumer_secret');
        $token = config('app.netsuite_api_token_id');
        $tokenSecret = config('app.netsuite_api_token_secret');
    
        $client = new Client([
            'base_uri' => $baseUrl,
        ]);
    
        // Prepare the OAuth 1.0a headers
        $oauth1 = new Oauth1([
            // 'consumer_key'    => $consumerKey,
            // 'consumer_secret' => $consumerSecret,
            // 'token'           => $token,
            // 'token_secret'    => $tokenSecret,
            // 'signature_method' => 'HMAC-SHA256', 
            'oauth_consumer_key' => $consumerKey,
            'oauth_consumer_secret' => $consumerSecret,
            'oauth_token' => $token,
            'oauth_token_secret' => $tokenSecret,
            'oauth_nonce' => uniqid(),
            'oauth_timestamp' => time(),
            'oauth_signature_method' => 'HMAC-SHA256', // Custom signature method
            'oauth_version' => '1.0',
        ]);

        // Create a handler stack and add the OAuth1 subscriber to it
        $handlerStack = HandlerStack::create();
        $handlerStack->push($oauth1);
            
        // Add the handler stack to the client
        $client = new Client(['base_uri' => $baseUrl, 'handler' => $handlerStack]);
    
        try {
            $response = $client->get('/services/rest/record/v1/customer');
            // Handle the response as needed
            $body = $response->getBody();
            $data = json_decode($body, true); // Assuming the response is in JSON format
            // Do something with the $data
        } catch (GuzzleHttp\Exception\ClientException $e) {
            $response = $e->getResponse();
            $statusCode = $response->getStatusCode();
            $reasonPhrase = $response->getReasonPhrase();
            $errorBody = $response->getBody()->getContents();
            
            // Output error details for debugging
            echo "Status Code: $statusCode\n";
            echo "Reason Phrase: $reasonPhrase\n";
            echo "Error Body: $errorBody\n";
        }
    }


    // Your NetSuite REST API endpoint
    // private $netsuiteEndpoint = config('app.netsuite_api_base_uri');

    public function makeNetSuiteRequest()
    {

        $netsuiteEndpoint = config('app.netsuite_api_base_uri'). '/services/rest/record/v1/customer';
        $consumerKey = config('app.netsuite_api_consumer_key');
        $consumerSecret = config('app.netsuite_api_consumer_secret');
        $accessToken = config('app.netsuite_api_token_id');
        $accessTokenSecret = config('app.netsuite_api_token_secret');

        // Set your custom headers here (if needed)
        $customHeaders = [
            'Custom-Header-1' => 'value1',
            'Custom-Header-2' => 'value2',
        ];


        // Create OAuth1 credentials
        $credentials = new Credentials(
            $consumerKey,
            $consumerSecret,
            $this->netsuiteEndpoint,
            new SignatureInterface\RsaSha1Signature(new SignatureInterface\HmacSha1Signature())
        );

        // Create a session storage (you can use any storage type depending on your use case)
        $storage = new Session();

        // Create the service factory
        $serviceFactory = new ServiceFactory();

        // Get the NetSuite service with the OAuth1 credentials
        $netsuiteService = $serviceFactory->createService('netsuite', $credentials, $storage);

            // Create a token with access token and secret
            $accessToken = new TokenInterface([
                'oauth_token' => $accessToken,
                'oauth_token_secret' => $accessTokenSecret,
            ]);
    
            // Set the access token in the service
            $netsuiteService->getStorage()->storeAccessToken('NetSuite', $accessToken);
    
            // Make the API request with OAuth1 authorization header and custom headers
            $response = Http::withHeaders($netsuiteService->requestHeaders($this->netsuiteEndpoint, $accessToken))->get($this->netsuiteEndpoint);
    
            // Handle the API response
            if ($response->successful()) {
                // API call was successful
                $data = $response->json();
                return $data;
            } else {
                // API call failed
                $statusCode = $response->status();
                $errorMessage = $response->json()['message'] ?? 'Unknown error occurred.';
                return response()->json(['error' => $errorMessage], $statusCode);
            }

    }

    public function makeRequest()
    {
        // Replace these with your actual Netsuite OAuth 1.0a credentials
        $netsuiteEndpoint = config('app.netsuite_api_base_uri'). '/services/rest/record/v1/customer';
        $consumerKey = config('app.netsuite_api_consumer_key');
        $consumerSecret = config('app.netsuite_api_consumer_secret');
        $accessToken = config('app.netsuite_api_token_id');
        $accessTokenSecret = config('app.netsuite_api_token_secret');

        // Prepare OAuth 1.0a credentials
        $credentials = [
            'identifier' => $consumerKey,
            'secret' => $consumerSecret,
            'callback_uri' => $netsuiteEndpoint,
            'token' => $accessToken,
            'token_secret' => $accessTokenSecret,
        ];

        // Create a Guzzle HTTP client with OAuth 1.0a middleware
        $client = new Client([
            'base_uri' => $netsuiteEndpoint,
            'auth' => 'oauth',
            'headers' => [
                'Accept' => 'application/json',
            ],
            'oauth' => $credentials,
        ]);

        // Make a request to the Netsuite API
        try {
            $response = $client->get('path-to-netsuite-endpoint');
            $data = json_decode($response->getBody(), true);
            // Request was successful, handle the response data
            dd($data);
        } catch (\Exception $e) {
                // Request failed, handle the error
                $statusCode = $e->status();
                $errorData = $e->json();
                echo $netsuiteEndpoint;
                dd($errorData);
        }
    }

    

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function edit(Transaction $transaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Transaction $transaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transaction $transaction)
    {
        //
    }
}
