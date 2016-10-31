<?php
// src/AppBundle/EventListener/BookSubscriber.php
namespace AppBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
// for Doctrine 2.4: Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use AppBundle\Entity\Book;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

class BookSubscriber implements EventSubscriber
{
    protected $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function getSubscribedEvents()
    {
        return array(
            'postRemove',
            );
    }

    public function postRemove(LifecycleEventArgs $args)
    {
        //$this->index($args);
        $entity = $args->getEntity();

        // perhaps you only want to act on some "Book" entity
        if ($entity instanceof Book) {
            //$entityManager = $args->getEntityManager();
            // ... do something with the Book

            //save path to files
            $tmpCover = $entity->getCover();
            $tmpBookFile = $entity->getBookfile();

            //delete cover image file
            $fs = new Filesystem();
            try {
                $fs->remove($this->container->getParameter('images_directory') . $entity->getCover());
            } catch (IOExceptionInterface $e) {
                echo "An error occurred while delete file" . $e->getPath();
            }

            //delete book pdf file
            $fs = new Filesystem();
            try {
                $fs->remove($this->container->getParameter('books_directory') . $entity->getBookfile());
            } catch (IOExceptionInterface $e) {
                echo "An error occurred while delete file" . $e->getPath();
            }
        }
    }

    /*
    public function index(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        // perhaps you only want to act on some "Book" entity
        if ($entity instanceof Book) {
        //$entityManager = $args->getEntityManager();
        // ... do something with the Book
        }
    }
    */
}