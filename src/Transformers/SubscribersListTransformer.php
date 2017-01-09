<?php

namespace Applicazza\MailwizzApiClient\Transformers;

use Applicazza\MailwizzApiClient\Contracts;
use League\Fractal\TransformerAbstract;

/**
 * Class SubscribersListTransformer
 * @package Applicazza\MailwizzApiClient\Transformers
 */
class SubscribersListTransformer extends TransformerAbstract
{
    /**
     * Transforms object
     *
     * @param $template
     * @return \Applicazza\MailwizzApiClient\Contracts\SubscribersList
     */
    public function transform($template)
    {
        return new Contracts\SubscribersList();
    }
}