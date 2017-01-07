<?php

use Applicazza\MailwizzApiClient\MailwizzApiClient;
use Applicazza\MailwizzApiClient\MailwizzApiClientServiceProvider;
use Orchestra\Testbench\TestCase;

class CampaignTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [MailwizzApiClientServiceProvider::class];
    }

    public function testAll()
    {
        $mock = new GuzzleHttp\Handler\MockHandler([
            new GuzzleHttp\Psr7\Response(200, [], Stub::getStub('campaign', 'index')),
        ]);

        $client = new MailwizzApiClient('', '', '', $mock);

        $campaigns = $client->campaigns->all();

        $this->assertNotEmpty($campaigns);
        $this->assertCount(2, $campaigns);
        $this->assertEquals('Test #1', $campaigns[0]->getName());
    }
}