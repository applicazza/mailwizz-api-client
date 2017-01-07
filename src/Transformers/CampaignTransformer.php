<?php

namespace Applicazza\MailwizzApiClient\Transformers;

use Applicazza\MailwizzApiClient\Contracts;
use League\Fractal\TransformerAbstract;

/**
 * Class CampaignTransformer
 * @package Applicazza\MailwizzApiClient\Transformers
 */
class CampaignTransformer extends TransformerAbstract
{
    /**
     * @param $campaign
     * @return \Applicazza\MailwizzApiClient\Contracts\Campaign
     */
    public function transform($campaign)
    {
        return new Contracts\Campaign([
            'uid' => $campaign['campaign_uid'],
            'name' => $campaign['name'],
            'status' => $campaign['status'],
        ]);
    }
}