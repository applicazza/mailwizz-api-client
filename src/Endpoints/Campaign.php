<?php

namespace Applicazza\MailwizzApiClient\Endpoints;

use Applicazza\MailwizzApiClient\Transformers;

/**
 * Class Campaign
 * @package Applicazza\MailwizzApiClient\Endpoints
 */
class Campaign extends AbstractEndpoint
{
    /**
     * @return \Applicazza\MailwizzApiClient\Contracts\Campaign[]
     */
    public function all()
    {
        return $this->index('campaigns', new Transformers\CampaignTransformer);
    }
}