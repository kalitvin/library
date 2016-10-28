<?php

namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="book")
 */

class Book
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $title;

    /**
     * @ORM\Column(type="string")
     */
    private $author;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Assert\Image(
     *     maxSize = "5M",
     *     mimeTypes = {"image/jpeg", "image/png"},
     *     mimeTypesMessage = "Invalid image"
     * )
     */
    private $cover;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Assert\File(
     *     maxSize = "5M",
     *     mimeTypes = {"application/pdf", "application/x-pdf"},
     *     mimeTypesMessage = "Please upload a valid PDF"
     * )
     */
    private $bookfile;

    /**
     * @ORM\Column(type="date")
     */
    private $readdate;

    /**
     * @ORM\Column(type="boolean")
     */
    private $ispublic;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Book
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set author
     *
     * @param string $author
     *
     * @return Book
     */
    public function setAuthor($author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set cover
     *
     * @param string $cover
     *
     * @return Book
     */
    public function setCover($cover)
    {
        $this->cover = $cover;

        return $this;
    }

    /**
     * Get cover
     *
     * @return string
     */
    public function getCover()
    {
        return $this->cover;
    }

    /**
     * Set bookfile
     *
     * @param string $bookfile
     *
     * @return Book
     */
    public function setBookfile($bookfile)
    {
        $this->bookfile = $bookfile;

        return $this;
    }

    /**
     * Get bookfile
     *
     * @return string
     */
    public function getBookfile()
    {
        return $this->bookfile;
    }

    /**
     * Set readdate
     *
     * @param \DateTime $readdate
     *
     * @return Book
     */
    public function setReaddate($readdate)
    {
        $this->readdate = $readdate;

        return $this;
    }

    /**
     * Get readdate
     *
     * @return \DateTime
     */
    public function getReaddate()
    {
        return $this->readdate;
    }

    /**
     * Set ispublic
     *
     * @param boolean $ispublic
     *
     * @return Book
     */
    public function setIspublic($ispublic)
    {
        $this->ispublic = $ispublic;

        return $this;
    }

    /**
     * Get ispublic
     *
     * @return boolean
     */
    public function getIspublic()
    {
        return $this->ispublic;
    }
}
