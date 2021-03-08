<?php

declare(strict_types=1);

/*
 * (c) 2021 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Nines\SolrBundle\Annotation as Solr;
use Nines\UtilBundle\Entity\AbstractEntity;

/**
 * Firm.
 *
 * @ORM\Entity(repositoryClass="App\Repository\FirmRepository")
 * @Solr\Document
 */
class Firm extends AbstractEntity {
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     * @Solr\Field(type="text")
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="street_address", type="text", nullable=true)
     * @Solr\Field(type="text")
     */
    private $streetAddress;

    /**
     * @var string
     *
     * @ORM\Column(name="start_date", type="string", length=4, nullable=true)
     * @Solr\Field(type="integer")
     */
    private $startDate;

    /**
     * @var string
     *
     * @ORM\Column(name="end_date", type="string", length=4, nullable=true)
     * @Solr\Field(type="integer")
     */
    private $endDate;

    public function __construct() {
        parent::__construct();
    }

    public function __toString() : string {
        return $this->name;
    }

    public function getName() : ?string {
        return $this->name;
    }

    public function setName(?string $name) : self {
        $this->name = $name;

        return $this;
    }

    public function getStreetAddress() : ?string {
        return $this->streetAddress;
    }

    public function setStreetAddress(?string $streetAddress) : self {
        $this->streetAddress = $streetAddress;

        return $this;
    }

    public function getStartDate() : ?string {
        return $this->startDate;
    }

    public function setStartDate(?string $startDate) : self {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate() : ?string {
        return $this->endDate;
    }

    public function setEndDate(?string $endDate) : self {
        $this->endDate = $endDate;

        return $this;
    }
}
