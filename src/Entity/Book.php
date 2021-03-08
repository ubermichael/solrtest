<?php

declare(strict_types=1);

/*
 * (c) 2021 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Entity;

use App\Repository\BookRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Nines\SolrBundle\Annotation as Solr;
use Nines\UtilBundle\Entity\AbstractEntity;

/**
 * @ORM\Entity(repositoryClass=BookRepository::class)
 * @ORM\HasLifecycleCallbacks
 * @Solr\Document
 */
class Book extends AbstractEntity {
    /**
     * @var string
     * @ORM\Column(type="string", nullable=false)
     * @Solr\Field(type="text")
     */
    private $title;

    /**
     * @var int
     * @ORM\Column(type="integer", nullable=false)
     * @Solr\Field(type="integer")
     */
    private $pages;

    /**
     * @var float
     * @ORM\Column(type="float", nullable=false)
     * @Solr\Field(type="float")
     */
    private $price;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=false)
     * @Solr\Field(type="text")
     */
    private $description;

    /**
     * @var DateTimeImmutable
     * @ORM\Column(type="datetime_immutable", nullable=false)
     * @Solr\Field(type="date", mutator="format('Y-m-d\T00:00:00\Z')")
     */
    private $published;

    public function __toString() : string {
        return $this->title;
    }

    public function getTitle() : ?string {
        return $this->title;
    }

    public function setTitle(string $title) : self {
        $this->title = $title;

        return $this;
    }

    public function getPages() : ?int {
        return $this->pages;
    }

    public function setPages(int $pages) : self {
        $this->pages = $pages;

        return $this;
    }

    public function getPrice() : ?float {
        return $this->price;
    }

    public function setPrice(float $price) : self {
        $this->price = $price;

        return $this;
    }

    public function getDescription(bool $text = false) : ?string {
        if ($text) {
            return strip_tags($this->description);
        }

        return $this->description;
    }

    public function setDescription(string $description) : self {
        $this->description = $description;

        return $this;
    }

    public function getPublished() : ?DateTimeImmutable {
        return $this->published;
    }

    public function setPublished(DateTimeImmutable $published) : self {
        $this->published = $published;

        return $this;
    }
}
