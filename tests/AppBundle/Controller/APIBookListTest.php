<?php
//Test PHP Git Hooks
namespace tests\AppBundle\Controller\API\V1;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Response;

class APIgetBookList extends WebTestCase
{

    public function testIndex()
    {
        $client = static::createClient();
        $client->request('GET', '/api/v1/books');
        $response = $client->getResponse();
        $data = json_decode($response->getContent(), true);
    }
}
