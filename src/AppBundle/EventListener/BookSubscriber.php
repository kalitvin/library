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
    protected $books_directory;
    protected $images_directory;

    public function __construct($books_directory, $images_directory)
    {
        $this->books_directory = $books_directory;
        $this->images_directory = $images_directory;
    }

    public function getSubscribedEvents()
    {
        return array(
            'postRemove',
            );
    }

    public function postRemove(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        // perhaps you only want to act on some "Book" entity
        if ($entity instanceof Book) {
            //delete cover image file
            $fs = new Filesystem();
            if ($entity->getCover()!=null) {
                $fs->remove($this->images_directory . $entity->getCover());
            }

            //delete book pdf file
            $fs = new Filesystem();
            if ($entity->getBookfile()!=null) {
                $fs->remove($this->books_directory . $entity->getBookfile());
            }
        }
    }
}
