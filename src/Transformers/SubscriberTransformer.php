<?php

namespace Applicazza\MailwizzApiClient\Transformers;

use Applicazza\MailwizzApiClient\Contracts;
use League\Fractal\TransformerAbstract;

/**
 * Class SubscriberTransformer
 * @package Applicazza\MailwizzApiClient\Transformers
 */
class SubscriberTransformer extends TransformerAbstract
{
    /**
     * Transforms object
     *
     * @param $template
     * @return \Applicazza\MailwizzApiClient\Contracts\Subscriber
     */
    public function transform($template)
    {
        return new Contracts\Subscriber();
    }
}