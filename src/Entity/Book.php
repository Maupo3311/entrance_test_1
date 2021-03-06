<?php

namespace App\Entity;

use App\Repository\BookRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=BookRepository::class)
 * @UniqueEntity(
 *     fields={"name", "isbn"},
 *     message="Name and isbn must be unique."
 * )
 * @UniqueEntity(
 *     fields={"name", "year"},
 *     message="Name and year of publishing must be unique."
 * )
 */
class Book
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=false, length=24)
     */
    private $name = '';

    /**
     * @var integer
     * @ORM\Column(type="integer", nullable=false)
     */
    private $year = 2000;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=false)
     */
    private $isbn = '';

    /**
     * @var integer
     * @ORM\Column(type="integer", nullable=false)
     */
    private $numberOfPages = 0;

    /**
     * @var Collection
     * @ORM\ManyToMany(targetEntity="Author", mappedBy="books")
     */
    private $authors;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @Assert\File(mimeTypes={"image/png", "image/jpeg"})
     */
    private $image;

    /**
     * @var string
     */
    private $tempImage;

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->name;
    }

    /**
     * Book constructor.
     */
    public function __construct()
    {
        $this->authors = new ArrayCollection();
    }

    /**
     * @return integer|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return int
     */
    public function getYear(): int
    {
        return $this->year;
    }

    /**
     * @param int $year
     */
    public function setYear(int $year): void
    {
        $this->year = $year;
    }

    /**
     * @return string
     */
    public function getIsbn(): string
    {
        return $this->isbn;
    }

    /**
     * @param string $isbn
     */
    public function setIsbn(string $isbn): void
    {
        $this->isbn = $isbn;
    }

    /**
     * @return int
     */
    public function getNumberOfPages(): int
    {
        return $this->numberOfPages;
    }

    /**
     * @param int $numberOfPages
     */
    public function setNumberOfPages(int $numberOfPages): void
    {
        $this->numberOfPages = $numberOfPages;
    }

    /**
     * @return Collection
     */
    public function getAuthors(): Collection
    {
        return $this->authors;
    }

    /**
     * @param Collection|null $authors
     */
    public function setAuthors(?Collection $authors): void
    {
        $this->authors = $authors;
    }

    /**
     * @return mixed
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @return string|null
     */
    public function getTempImage(): ?string
    {
        return $this->tempImage;
    }

    /**
     * @param mixed $image
     */
    public function setImage($image): void
    {
        if (is_string($this->image)) {
            $this->tempImage = $this->image;
        }

        $this->image = $image;
    }

}
