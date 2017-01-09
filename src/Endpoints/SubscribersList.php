<?php

namespace Applicazza\MailwizzApiClient\Endpoints;

use Applicazza\MailwizzApiClient\Transformers;

/**
 * Class SubscribersList
 * @package Applicazza\MailwizzApiClient\Endpoints
 */
class SubscribersList extends AbstractEndpoint
{
    /**
     * @return \Applicazza\MailwizzApiClient\Contracts\Campaign[]
     */
    public function all()
    {
        return $this->index('lists', new Transformers\SubscribersListTransformer);
    }
}