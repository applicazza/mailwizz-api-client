<?php

namespace Applicazza\MailwizzApiClient\Endpoints;

use Applicazza\MailwizzApiClient\Exceptions;
use Applicazza\MailwizzApiClient\MailwizzApiClient;
use GuzzleHttp;
use League\Fractal\TransformerAbstract;
use Psr;
use Spatie\Fractalistic\ArraySerializer;
use Spatie\Fractalistic\Fractal;

/**
 * Class AbstractEndpoint
 * @package Applicazza\MailwizzApiClient\Endpoints
 */
abstract class AbstractEndpoint
{
    /**
     * @var \Applicazza\MailwizzApiClient\MailwizzApiClient
     */
    protected $client;

    /**
     * AbstractEndpoint constructor.
     * @param \Applicazza\MailwizzApiClient\MailwizzApiClient $client
     */
    function __construct(MailwizzApiClient $client)
    {
        $this->client = $client;
    }

    protected function index(string $uri, TransformerAbstract $transformer, array $query = [])
    {
        $response = $this->request('GET', $uri, $query);

        $records = [];

        if (array_key_exists('data', $response) && array_key_exists('records', $response['data']))
            $records = &$response['data']['records'];

        $items = Fractal::create()
            ->collection($records)
            ->transformWith($transformer)
            ->serializeWith(new ArraySerializer)
            ->toArray();

        return $items;
    }

    protected function request(string $method, string $uri, array $query = [], array $payload = [])
    {
        $options = [
            'query' => $query,
            'timeout' => $this->client->getTimeout(),
            'form_params' => $payload,
        ];

        $options = array_filter($options);

        try {

            $response = $this->client->getGuzzle()->request($method, $uri, $options);

            $decodedResponse = GuzzleHttp\json_decode($response->getBody()->getContents(), true);

            if ($decodedResponse['status'] != 'success')
                throw new Exceptions\MailwizzException;

            return $decodedResponse;

        } catch (GuzzleHttp\Exception\ConnectException $e) {

            throw new Exceptions\NetworkException($e);

        } catch (GuzzleHttp\Exception\RequestException $e) {

            throw new Exceptions\NetworkException($e);

        } catch (GuzzleHttp\Exception\TransferException $e) {

            throw new Exceptions\NetworkException($e);

        }
    }
}