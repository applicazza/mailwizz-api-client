<?php

namespace Applicazza\MailwizzApiClient;

use Applicazza\MailwizzApiClient\Endpoints;
use Applicazza\MailwizzApiClient\Events;
use GuzzleHttp;
use Illuminate\Foundation\Application;
use Psr\Http\Message;

/**
 * Class MailwizzApiClient
 * @package Applicazza\MailwizzApiClient
 *
 * @property \Applicazza\MailwizzApiClient\Endpoints\Campaign $campaigns
 */
class MailwizzApiClient
{
    /**
     * @var \Applicazza\MailwizzApiClient\Endpoints\Campaign
     */
    public $campaigns;

    /**
     * @var string
     */
    protected $endpoint = '';

    /**
     * @var \GuzzleHttp\Client
     */
    protected $guzzle;

    /**
     * @var callable
     */
    protected $handler;

    /**
     * @var string
     */
    protected $lastRequestHttpResponse = '';

    /**
     * @var int
     */
    protected $lastRequestHttpStatusCode = 0;

    /**
     * @var string
     */
    protected $privateKey = '';

    /**
     * @var string
     */
    protected $proxy = '';

    /**
     * @var string
     */
    protected $publicKey = '';

    /**
     * @var \Applicazza\MailwizzApiClient\Endpoints\Template
     */
    public $templates;

    /**
     * @var int
     */
    protected $timeout = 30;

    /**
     * MailwizzApiClient constructor.
     * @param string|null $endpoint
     * @param string|null $publicKey
     * @param string|null $privateKey
     * @param callable|null $handler
     */
    function __construct(string $endpoint = '', string $publicKey = '', string $privateKey = '', callable $handler = null)
    {
        $this->campaigns = new Endpoints\Campaign($this);
        $this->templates = new Endpoints\Template($this);

        if (strlen($endpoint))
            $this->setEndpoint($endpoint);

        if (strlen($privateKey))
            $this->setPrivateKey($privateKey);

        if (strlen($publicKey))
            $this->setPublicKey($publicKey);

        if (!is_null($handler))
            $this->setHandler($handler);
    }

    /**
     *
     */
    protected function createGuzzle()
    {
        $guzzleClientHandler = GuzzleHttp\HandlerStack::create($this->handler);

        $guzzleClientHandler->push(GuzzleHttp\Middleware::mapRequest(function (Message\RequestInterface $request) {
            return $this->signMailwizzRequest($request);
        }));

        $guzzleClientHandler->push(GuzzleHttp\Middleware::mapResponse(function (Message\ResponseInterface $response) {
            return $this->emitResponseReceivedEvent($response);
        }));

        $guzzleClientOptions = [
            'base_uri' => $this->getEndpoint(),
            'handler' => $guzzleClientHandler,
            'timeout' => $this->getTimeout(),
        ];

        $this->guzzle = new GuzzleHttp\Client($guzzleClientOptions);
    }

    /**
     * @param \Psr\Http\Message\ResponseInterface $response
     * @return \Psr\Http\Message\ResponseInterface
     */
    protected function emitResponseReceivedEvent(Message\ResponseInterface $response)
    {
        // Get required information

        $httpStatusCode = $response->getStatusCode();

        $httpResponse = $response->getBody()->getContents();

        // Check if we are running under Laravel

        if (class_exists(Application::class) && function_exists('event')) {

            // Emit event

            event(new Events\ResponseReceived($httpStatusCode, $httpResponse));

        }

        // Rewind stream

        $response->getBody()->rewind();


        // Fill local data

        $this->lastRequestHttpResponse = $httpResponse;
        $this->lastRequestHttpStatusCode = $httpStatusCode;

        // Return response

        return $response;
    }

    /**
     * @return string
     */
    public function getEndpoint(): string
    {
        return $this->endpoint;
    }

    /**
     * @return \GuzzleHttp\Client
     */
    public function getGuzzle(): GuzzleHttp\Client
    {
        if (is_null($this->guzzle))
            $this->createGuzzle();

        return $this->guzzle;
    }

    /**
     * @return callable
     */
    public function getHandler(): callable
    {
        return $this->handler;
    }

    /**
     * @return string
     */
    public function getLastRequestHttpResponse(): string
    {
        return $this->lastRequestHttpResponse;
    }

    /**
     * @return int
     */
    public function getLastRequestHttpStatusCode(): int
    {
        return $this->lastRequestHttpStatusCode;
    }

    /**
     * @return string
     */
    public function getPrivateKey(): string
    {
        return $this->privateKey;
    }

    /**
     * @return string
     */
    public function getProxy(): string
    {
        return $this->proxy;
    }

    /**
     * @return string
     */
    public function getPublicKey(): string
    {
        return $this->publicKey;
    }

    /**
     * @return int
     */
    public function getTimeout(): int
    {
        return $this->timeout;
    }

    /**
     * @param string $endpoint
     * @param string $publicKey
     * @param string $privateKey
     */
    public function setCredentials(string $endpoint, string $publicKey, string $privateKey)
    {
        $this->endpoint = $endpoint;
        $this->publicKey = $publicKey;
        $this->privateKey = $privateKey;

        $this->createGuzzle();
    }

    /**
     * @param string $endpoint
     */
    public function setEndpoint(string $endpoint)
    {
        $this->endpoint = $endpoint;

        $this->createGuzzle();
    }

    /**
     * @param callable $handler
     */
    protected function setHandler(callable $handler)
    {
        $this->handler = $handler;

        $this->createGuzzle();
    }

    /**
     * @param string $publicKey
     * @param string $privateKey
     */
    public function setKeys(string $publicKey, string $privateKey)
    {
        $this->publicKey = $publicKey;
        $this->privateKey = $privateKey;
    }

    /**
     * @param string $privateKey
     */
    public function setPrivateKey(string $privateKey)
    {
        $this->privateKey = $privateKey;
    }

    /**
     * @param string $proxy
     */
    public function setProxy(string $proxy)
    {
        $this->proxy = $proxy;
    }

    /**
     * @param string $publicKey
     */
    public function setPublicKey(string $publicKey)
    {
        $this->publicKey = $publicKey;

        $this->createGuzzle();
    }

    /**
     * @param int $timeout
     */
    public function setTimeout(int $timeout)
    {
        $this->timeout = $timeout;
    }

    /**
     * @param \Psr\Http\Message\RequestInterface $request
     * @return \GuzzleHttp\Psr7\Request
     */
    protected function signMailwizzRequest(Message\RequestInterface $request)
    {
        // CURRENT TIMESTAMP

        $timestamp = time();

        // GET BODY PARAMS

        parse_str($request->getBody()->getContents(), $payload_params);

        parse_str($request->getUri()->getQuery(), $query_params);

        $params = array_merge($query_params, $payload_params);

        // ATTACH NEW PARAMS

        $extraParams = array_merge($params, [

            'X-MW-PUBLIC-KEY' => $this->getPublicKey(),
            'X-MW-TIMESTAMP' => $timestamp,

        ]);

        // SORT PARAMS

        ksort($extraParams, SORT_STRING);

        // OBTAIN AND CLONE URI

        $uri = clone $request->getUri();

        // FORMAT MESSAGE

        $message = sprintf('%s %s', $request->getMethod(), $uri->withQuery(http_build_query($extraParams)));

        // SIGN REQUEST

        $signature = hash_hmac('sha1', $message, $this->getPrivateKey(), false);

        // ATTACH BODY AND HEADERS

        $request = $request
            ->withHeader('X-MW-PUBLIC-KEY', $this->getPublicKey())
            ->withHeader('X-MW-TIMESTAMP', $timestamp)
            ->withHeader('X-MW-SIGNATURE', $signature);

        if ($request instanceof GuzzleHttp\Psr7\Request && !in_array($request->getMethod(), ['GET', 'HEAD']))
            $request = $request->withBody(GuzzleHttp\Psr7\stream_for(http_build_query($payload_params)));

        // RETURN REQUEST

        return $request;
    }

}