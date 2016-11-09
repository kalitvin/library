<?php

namespace tests\AppBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Response;
use \DateTime;

class APIBookEditTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'testuser',
            'PHP_AUTH_PW'   => 'testuser'
        ]);

        $data=[
            'title'=>'Book title edited',
            'author'=>'Author edited',
            'readdate'=> (new DateTime('2017-10-10'))->format('Y-m-d'),
            'ispublic'=>true
        ];
        echo json_encode($data);
        $response=$client->request('PUT', '/api/v1/books/91/edit', [json_encode($data)], [], ['CONTENT_TYPE' => 'application/json']);
    }
}