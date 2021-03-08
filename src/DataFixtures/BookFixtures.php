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
use Doctrine\Persistence\ObjectManager;

class BookFixtures extends Fixture {
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $em) : void {
        for ($i = 1; $i <= 4; $i++) {
            $fixture = new Book();
            $fixture->setTitle('Title ' . $i);
            $fixture->setPages($i);
            $fixture->setPrice($i + 0.5);
            $fixture->setDescription("<p>This is paragraph {$i}</p>");
            $fixture->setPublished('Published ' . $i);

            $em->persist($fixture);
            $this->setReference('book.' . $i, $fixture);
        }
        $em->flush();
    }
}
