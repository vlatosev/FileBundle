<?php

namespace ED\FileBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ImageControllerTest extends WebTestCase
{
    public function testShowimage()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/showImage');
    }

}
