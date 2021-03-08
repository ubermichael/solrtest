<?php

declare(strict_types=1);

/*
 * (c) 2021 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\DataFixtures;

use App\Entity\Firm;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class FirmFixtures extends Fixture {
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $em) : void {
        for ($i = 1; $i <= 4; $i++) {
            $fixture = new Firm();
            $fixture->setName('Name ' . $i);
            $fixture->setStreetAddress("<p>This is paragraph {$i}</p>");
            $fixture->setStartDate('StartDate ' . $i);
            $fixture->setEndDate('EndDate ' . $i);

            $em->persist($fixture);
            $this->setReference('firm.' . $i, $fixture);
        }
        $em->flush();
    }
}
