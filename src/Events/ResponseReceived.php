<?php

namespace Applicazza\MailwizzApiClient\Events;

/**
 * Class ResponseReceived
 * @package Applicazza\MailwizzApiClient\Events
 */
class ResponseReceived
{
    /**
     * @var int
     */
    public $httpStatusCode;

    /**
     * @var string
     */
    public $httpResponse;

    /**
     * ResponseReceived constructor.
     * @param int $httpStatusCode
     * @param string $httpResponse
     */
    function __construct(int $httpStatusCode, string $httpResponse)
    {
        $this->httpStatusCode = $httpStatusCode;
        $this->httpResponse = $httpResponse;
    }
}