<?php

namespace Applicazza\MailwizzApiClient\Endpoints;

use Applicazza\MailwizzApiClient\Transformers;

/**
 * Class Subscriber
 * @package Applicazza\MailwizzApiClient\Endpoints
 */
class Subscriber extends AbstractEndpoint
{
    /**
     * @return \Applicazza\MailwizzApiClient\Contracts\Campaign[]
     */
    public function all()
    {
        return $this->index('list_subscribers', new Transformers\SubscriberTransformer);
    }
}