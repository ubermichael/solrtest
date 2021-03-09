<?php

declare(strict_types=1);

/*
 * (c) 2021 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Nines\UtilBundle\Entity\AbstractTerm;

/**
 * Genre.
 *
 * @ORM\Table(name="genre")
 * @ORM\Entity(repositoryClass="App\Repository\GenreRepository")
 */
class Genre extends AbstractTerm {
    use HasPublications {
        HasPublications::__construct as private trait_constructor;
    }

    /**
     * @var Collection|Publication[]
     * @ORM\ManyToMany(targetEntity="Publication", mappedBy="genres")
     * @ORM\OrderBy({"title": "ASC"})
     */
    private $publications;

    public function __construct() {
        $this->trait_constructor();
        parent::__construct();
        $this->publications = new ArrayCollection();
    }

    /**
     * @return Collection|Publication[]
     */
    public function getPublications() : Collection {
        return $this->publications;
    }

    public function addPublication(Publication $publication) : self {
        if ( ! $this->publications->contains($publication)) {
            $this->publications[] = $publication;
            $publication->addGenre($this);
        }

        return $this;
    }

    public function removePublication(Publication $publication) : self {
        if ($this->publications->removeElement($publication)) {
            $publication->removeGenre($this);
        }

        return $this;
    }
}
