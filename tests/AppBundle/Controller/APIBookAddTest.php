<?php
namespace tests\AppBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Response;
use \DateTime;

class APIBookAddTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'testuser',
            'PHP_AUTH_PW'   => 'testuser'
        ]);

        $data=[
            'title'=>'Book title',
            'author'=>'Author',
            'readdate'=> (new DateTime('2017-10-10'))->format('Y-m-d'),
            'ispublic'=>true
        ];
        echo json_encode($data);
        $response=$client->request('POST', '/api/v1/books/add', [json_encode($data)], [], ['CONTENT_TYPE' => 'application/json']);
    }
}
