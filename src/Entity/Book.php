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
use FS\SolrBundle\Doctrine\Annotation as Solr;

/**
 * @ORM\Entity(repositoryClass=BookRepository::class)
 * @ORM\HasLifecycleCallbacks
 * @Solr\Document
 */
class Book {
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     *
     * @Solr\Id
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=false)
     *
     * @Solr\Field(type="text")
     */
    private $title;

    /**
     * @var int
     * @ORM\Column(type="integer", nullable=false)
     *
     * @Solr\Field(type="integer")
     */
    private $pages;

    /**
     * @var float
     * @ORM\Column(type="float", nullable=false)
     *
     * @Solr\Field(type="float")
     */
    private $price;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=false)
     *
     * @Solr\Field(type="text", getter="getDescription(true)")
     */
    private $description;

    /**
     * @var DateTimeImmutable
     * @ORM\Column(type="datetime_immutable", nullable=false)
     *
     * @Solr\Field(type="date", getter="format('Y-m-d')")
     */
    private $published;

    /**
     * @var DateTimeImmutable
     * @ORM\Column(type="datetime_immutable", nullable=false)
     *
     * @Solr\Field(type="date", getter="format('Y-m-d\TH:i:s.z\Z')")
     */
    private $created;

    /**
     * @var DateTimeImmutable
     * @ORM\Column(type="datetime_immutable", nullable=false)
     *
     * @Solr\Field(type="date", getter="format('Y-m-d\TH:i:s.z\Z')")
     */
    private $updated;

    public function __toString() : string {
        return $this->title;
    }

    public function getId() : ?int {
        if(is_string($this->id)) {
            return (int)$this->id;
        }
        return $this->id;
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
        if($text) {
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

    public function getCreated() : ?DateTimeImmutable {
        return $this->created;
    }

    public function setCreated(DateTimeImmutable $created) : self {
        $this->created = $created;

        return $this;
    }

    public function getUpdated() : ?DateTimeImmutable {
        return $this->updated;
    }

    public function setUpdated(DateTimeImmutable $updated) : self {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Sets the created and updated timestamps.
     *
     * @ORM\PrePersist
     */
    public function prePersist() : void {
        $this->created = new DateTimeImmutable();
        $this->updated = new DateTimeImmutable();
    }

    /**
     * Sets the updated timestamp.
     *
     * @ORM\PreUpdate
     */
    public function preUpdate() : void {
        $this->updated = new DateTimeImmutable();
    }
}
