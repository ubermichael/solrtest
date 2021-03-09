<?php

declare(strict_types=1);

/*
 * (c) 2021 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\DataFixtures;

use App\Entity\Alias;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AliasFixtures extends Fixture {
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $em) : void {
        for ($i = 1; $i <= 4; $i++) {
            $fixture = new Alias();
            $fixture->setName('Name ' . $i);
            $fixture->setSortableName('SortableName ' . $i);
            $fixture->setMaiden(0 === $i % 2);
            $fixture->setMarried(0 === $i % 2);
            $fixture->setDescription("<p>This is paragraph {$i}</p>");
            $fixture->setNotes("<p>This is paragraph {$i}</p>");
            $em->persist($fixture);
            $this->setReference('alias.' . $i, $fixture);
        }
        $em->flush();
    }
}
