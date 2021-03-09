<?php

declare(strict_types=1);

/*
 * (c) 2021 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\DataFixtures;

use App\Entity\DateYear;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class DateYearFixtures extends Fixture {
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $em) : void {
        for ($i = 1; $i <= 4; $i++) {
            $fixture = new DateYear();
            $fixture->setValue('Value ' . $i);
            $fixture->setStart($i);
            $fixture->setStartCirca(0 === $i % 2);
            $fixture->setEnd($i);
            $fixture->setEndCirca(0 === $i % 2);
            $em->persist($fixture);
            $this->setReference('dateyear.' . $i, $fixture);
        }
        $em->flush();
    }
}
