<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\HttpCache\HttpCache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Book;
use AppBundle\Form\AddBook;
use AppBundle\Form\EditBook;
use Symfony\Component\HttpFoundation\File\File;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:Book');
        $books = $repository->findBy([], ['readdate' => 'DESC']);

        $response = $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir') . '/..') . DIRECTORY_SEPARATOR,
            'books' => $books,
        ]);

        $response->setSharedMaxAge(86400);
        $response->headers->addCacheControlDirective('must-revalidate', true);
        return $response;
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

        if ($form->isSubmitted() && $form->isValid()) {

            //coverfile upload start
            $imagefile = $book->getCover();
            $imageoriginalname = $imagefile->getClientOriginalName();
            // Generate a unique name for the file before saving it
            $imagefileName = $imageoriginalname . md5(uniqid()) . '.' . $imagefile->guessExtension();

            $imagepath = '/' . substr($imageoriginalname, 0, 3);
            // Move the file to the directory where images are stored
            $imagefile->move(
                $this->getParameter('images_directory') . $imagepath,
                $imagefileName
            );

            // Update the 'cover' property to store the image file name instead of its contents
            $book->setCover($imagepath . '/' . $imagefileName);
            //coverfile upload end

            //bookfile upload start
            $file = $book->getBookfile();
            $bookoriginalname = $file->getClientOriginalName();
            // Generate a unique name for the file before saving it
            $fileName = $bookoriginalname . md5(uniqid()) . '.' . $file->guessExtension();

            $bookpath = '/' . substr($bookoriginalname, 0, 3);
            // Move the file to the directory where books are stored
            $file->move(
                $this->getParameter('books_directory') . $bookpath,
                $fileName
            );

            // Update the 'book' property to store the PDF file name instead of its contents
            $book->setBookfile($bookpath . '/' . $fileName);
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

    /**
     * @Route("/editbook/{id}", name="editbook")
     */
    public function bookEditAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $book = $em->getRepository('AppBundle:Book')->find($id);

        if (!$book) {
            throw $this->createNotFoundException(
                'No book found for id ' . $id
            );
        }

        //save path to files
        $tmpCover = $book->getCover();
        $tmpBookFile = $book->getBookfile();

        //transform string to File object
        if (is_null($tmpCover) == false) {
            $book->setCover(
                new File($this->getParameter('images_directory') . $book->getCover())
            );
        }

        if (is_null($tmpBookFile) == false) {
            $book->setBookfile(
                new File($this->getParameter('books_directory') . $book->getBookfile())
            );
        }


            // Update the 'cover' property to store the image file name instead of its contents
            $book->setCover($tmpCover);
            // Update the 'book' property to store the PDF file name instead of its contents
            $book->setBookfile($tmpBookFile);
            $em->flush();


        $form = $this->createForm(EditBook::class, $book);
        $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $book->setCover($tmpCover);
                $book->setBookfile($tmpBookFile);
                $em->flush();
                $book = $form->getData();

                if ($form->get('deletecover')->getData() == 1) {
                    $fs = new Filesystem();
                    try {
                        $fs->remove($this->getParameter('images_directory') . $book->getCover());
                    } catch (IOExceptionInterface $e) {
                        echo "An error occurred while delete file" . $e->getPath();
                    }
                    $book->setCover(null);
                    $em->flush();
                    return new Response(
                        '<html><body>Cover deleted</body></html>'
                    );
                } else {
                    $imagefile = $form->get('cover')->getData();
                    if ($imagefile<>null) {
                        $imageoriginalname = $imagefile->getClientOriginalName();
                        // Generate a unique name for the file before saving it
                        $imagefileName = $imageoriginalname . md5(uniqid()) . '.' . $imagefile->guessExtension();

                        $imagepath = '/' . substr($imageoriginalname, 0, 3);
                        // Move the file to the directory where images are stored
                        $imagefile->move(
                            $this->getParameter('images_directory') . $imagepath,
                            $imagefileName
                        );

                        // Update the 'cover' property to store the image file name instead of its contents
                        $book->setCover($imagepath . '/' . $imagefileName);
                    }
                }

                if ($form->get('deletebookfile')->getData() == 1) {
                    $fs = new Filesystem();
                    try {
                        $fs->remove($this->getParameter('books_directory') . $book->getBookfile());
                    } catch (IOExceptionInterface $e) {
                        echo "An error occurred while delete file" . $e->getPath();
                    }
                    $book->setBookfile(null);
                    $em->flush();
                    return new Response(
                        '<html><body>Book file deleted</body></html>'
                    );
                } else {
                    $file = $form->get('bookfile')->getData();
                    if ($file<>null) {
                        $bookoriginalname = $file->getClientOriginalName();
                        // Generate a unique name for the file before saving it
                        $fileName = $bookoriginalname . md5(uniqid()) . '.' . $file->guessExtension();

                        $bookpath = '/' . substr($bookoriginalname, 0, 3);
                        // Move the file to the directory where books are stored
                        $file->move(
                            $this->getParameter('books_directory') . $bookpath,
                            $fileName
                        );

                        // Update the 'book' property to store the PDF file name instead of its contents
                        $book->setBookfile($bookpath . '/' . $fileName);
                    }

                }

                $em->flush();
                return $this->redirectToRoute('book_updated');
            }

            // render the template
            return $this->render('default/editbook.html.twig', array(
                'form' => $form->createView(),
            ));

    }

    /**
     * @Route("/book_updated", name="book_updated")
     */
    public function bookUpdatedAction()
    {
        return new Response(
            '<html><body>Book updated</body></html>'
        );
    }

    /**
     * @Route("/delbook/{id}", name="deletebook")
     */
    public function bookDeletedAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $book = $em->getRepository('AppBundle:Book')->find($id);

        if (!$book) {
            throw $this->createNotFoundException(
                'No book found for id ' . $id
            );
        }

        // Save Entity
        $em->remove($book);
        $em->flush();

        return new Response(
            '<html><body>Book '.$id.' deleted</body></html>'
        );
    }
}
