<?php

namespace AppBundle\Controller\API\V1;

use Symfony\Bundle\FrameworkBundle\HttpCache\HttpCache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Book;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;


class BookController extends Controller
{
    /**
     * @Route("/api/v1/books", name="api_v1_books")
     * @Method("GET")
     */
    public function getBooksAction()
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:Book');
        $books = $repository->findAll();

        $bookspath=$this->getParameter('books_directory');
        $imagespath=$this->getParameter('images_directory');

        foreach ($books as $book)
        {
            $book->setCover($imagespath.$book->getCover());
            $book->setBookfile($bookspath.$book->getBookfile());
            if ($book->getIspublic()==0)
            {
                $book->setBookfile(null);
            }
        }

        $serializer = $this->get('jms_serializer');
        $serialized_data=$serializer->serialize($books, 'json');

        return new Response($serialized_data, 200, array('Content-Type' => 'application/json'));
    }

    /**
     * @Route("/api/v1/books/add", name="api_v1_books_add")
     * @Method("POST")
     */
    public function addBookAction(Request $request)
    {
        $body=$request->request->all();
        $data=json_decode($body[0], true);

        $book = new Book();
        $book->setTitle($data['title']);
        $book->setAuthor($data['author']);
        $book->setReaddate(new \DateTime($data['readdate']));
        $book->setIspublic($data['ispublic']);
        $book->setCover(null);
        $book->setBookfile(null);

        $em=$this->getDoctrine()->getManager();
        $em->persist($book);
        $em->flush();

        //echo "API worked!";
    }

    /**
     * @Route("/api/v1/books/{id}/edit", name="api_v1_books_edit")
     * @Method("PUT")
     */
    public function editBookAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $book = $em->getRepository('AppBundle:Book')->find($id);

        if (!$book) {
            throw $this->createNotFoundException(
                'No book found for id ' . $id
            );
        }

        $body=$request->request->all();
        $data=json_decode($body[0], true);

        $book->setTitle($data['title']);
        $book->setAuthor($data['author']);
        $book->setReaddate(new \DateTime($data['readdate']));
        $book->setIspublic($data['ispublic']);
        $book->setCover(null);
        $book->setBookfile(null);

        $em->flush();

        //echo "API worked!";
    }
}