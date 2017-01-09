<?php

use Applicazza\MailwizzApiClient\MailwizzApiClient;
use Applicazza\MailwizzApiClient\MailwizzApiClientServiceProvider;
use Orchestra\Testbench\TestCase;

class TemplateTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [MailwizzApiClientServiceProvider::class];
    }

    public function testAll()
    {
        $mock = new GuzzleHttp\Handler\MockHandler([
            new GuzzleHttp\Psr7\Response(200, [], Stub::getStub('template', 'index')),
        ]);

        $client = new MailwizzApiClient('http://www.example.com', '123', '456', $mock);

        $templates = $client->templates->all();

        $this->assertEmpty($templates);
    }
}