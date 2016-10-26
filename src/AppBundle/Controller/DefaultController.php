<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Book;
use AppBundle\Form\AddBook;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
        ]);
    }

    /**
     * @Route("/addbook", name="addbook")
     */
    public function newAction(Request $request)
    {
        $book = new Book();
        $form = $this->createForm(AddBook::class, $book);
        //return $this->render('default/new.html.twig', array('form' => $form->createView()));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() ) {

            //coverfile upload start
            $imagefile = $book->getCover();
            $imageoriginalname =$imagefile->getClientOriginalName();
            // Generate a unique name for the file before saving it
            $imagefileName = $imageoriginalname.md5(uniqid()).'.'.$imagefile->guessExtension();

            $imagepath='/'.substr($imageoriginalname, 0, 3);
            // Move the file to the directory where images are stored
            $imagefile->move(
                $this->getParameter('images_directory').$imagepath,
                $imagefileName
            );

            // Update the 'cover' property to store the image file name instead of its contents
            $book->setCover($imagefileName);
            //coverfile upload end

            //bookfile upload start
            $file = $book->getBookfile();
            $bookoriginalname =$file->getClientOriginalName();
            // Generate a unique name for the file before saving it
            $fileName = $bookoriginalname.md5(uniqid()).'.'.$file->guessExtension();

            $bookpath='/'.substr($bookoriginalname, 0, 3);
            // Move the file to the directory where books are stored
            $file->move(
                $this->getParameter('books_directory').$bookpath,
                $fileName
            );

            // Update the 'book' property to store the PDF file name instead of its contents
            $book->setBookfile($fileName);
            //bookfile upload end

            $book = $form->getData();

            // Save Entity
            $em = $this->getDoctrine()->getManager();
            $em->persist($book);
            $em->flush();

            return $this->redirectToRoute('task_success');
        }

        // render the template
        return $this->render('default/addbook.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/task_success", name="task_success")
     */
    public function bookAddedAction()
    {
        return new Response(
            '<html><body>Book added</body></html>'
        );
    }
}
