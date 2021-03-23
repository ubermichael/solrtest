<?php

declare(strict_types=1);

/*
 * (c) 2021 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\DataFixtures;

use App\Entity\Book;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class BookFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $em) : void {
        for ($i = 1; $i <= 4; $i++) {
            $fixture = new Book();
            $fixture->setTitle("<p>This is paragraph {$i}</p>");
            $fixture->setSortableTitle("<p>This is paragraph {$i}</p>");
            $fixture->setLinks(['Links ' . $i]);
            $fixture->setDescription("<p>This is paragraph {$i}</p>");
            $fixture->setNotes("<p>This is paragraph {$i}</p>");
            $fixture->setDateyear($this->getReference('dateyear.' . $i));
            $fixture->setLocation($this->getReference('location.' . $i));
            $em->persist($fixture);
            $this->setReference('book.' . $i, $fixture);
        }
        $em->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function getDependencies() {
        return [
            DateYearFixtures::class,
            PlaceFixtures::class,
        ];
    }
}
