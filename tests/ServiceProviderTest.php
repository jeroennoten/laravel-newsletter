<?php


class ServiceProviderTest extends TestCase
{
    public function testDefaultConfig()
    {
        $this->assertEquals('nieuwsbrief@pvoj.nl', config('newsletter.list'));
    }
}