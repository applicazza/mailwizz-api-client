<?php

namespace Applicazza\MailwizzApiClient\Contracts;

/**
 * Class Campaign
 * @package Applicazza\MailwizzApiClient\Contracts
 */
class Campaign extends AbstractContract
{
    /**
     *
     */
    const PAUSED = 'paused';

    /**
     *
     */
    const SENDING = 'sending';

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $status;

    /**
     * @var string
     */
    protected $uid;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @return string
     */
    public function getUid(): string
    {
        return $this->uid;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @param string $status
     */
    public function setStatus(string $status)
    {
        $this->status = $status;
    }

    /**
     * @param string $uid
     */
    public function setUid(string $uid)
    {
        $this->uid = $uid;
    }
}