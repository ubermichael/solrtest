<?php

declare(strict_types=1);

/*
 * (c) 2021 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Firm.
 *
 * @ORM\Entity(repositoryClass="App\Repository\FirmRepository")
 */
class Firm {
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="street_address", type="text", nullable=true)
     */
    private $streetAddress;

    /**
     * @var string
     *
     * @ORM\Column(name="start_date", type="string", length=4, nullable=true)
     */
    private $startDate;

    /**
     * @var string
     *
     * @ORM\Column(name="end_date", type="string", length=4, nullable=true)
     */
    private $endDate;

    public function getId() : ?int {
        if (is_string($this->id)) {
            return (int) $this->id;
        }

        return $this->id;
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
