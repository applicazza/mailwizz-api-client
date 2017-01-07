<?php

namespace Applicazza\MailwizzApiClient\Endpoints;

use Applicazza\MailwizzApiClient\Transformers;

/**
 * Class Template
 * @package Applicazza\MailwizzApiClient\Endpoints
 */
class Template extends AbstractEndpoint
{
    /**
     * @return \Applicazza\MailwizzApiClient\Contracts\Campaign[]
     */
    public function all()
    {
        return $this->index('templates', new Transformers\TemplateTransformer);
    }
}