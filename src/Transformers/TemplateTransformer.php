<?php

namespace Applicazza\MailwizzApiClient\Transformers;

use Applicazza\MailwizzApiClient\Contracts;
use League\Fractal\TransformerAbstract;

/**
 * Class TemplateTransformer
 * @package Applicazza\MailwizzApiClient\Transformers
 */
class TemplateTransformer extends TransformerAbstract
{
    /**
     * @param $template
     * @return \Applicazza\MailwizzApiClient\Contracts\Template
     */
    public function transform($template)
    {
        return new Contracts\Template();
    }
}