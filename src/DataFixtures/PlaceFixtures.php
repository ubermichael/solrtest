<?php

declare(strict_types=1);

/*
 * (c) 2021 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\DataFixtures;

use App\Entity\Place;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PlaceFixtures extends Fixture {
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $em) : void {
        for ($i = 1; $i <= 4; $i++) {
            $fixture = new Place();
            $fixture->setName('Name ' . $i);
            $fixture->setSortableName('SortableName ' . $i);
            $fixture->setGeonamesId('GeonamesId ' . $i);
            $fixture->setRegionName('RegionName ' . $i);
            $fixture->setCountryName('CountryName ' . $i);
            $fixture->setLatitude($i + 0.5);
            $fixture->setLongitude($i + 0.5);
            $fixture->setDescription("<p>This is paragraph {$i}</p>");
            $fixture->setNotes("<p>This is paragraph {$i}</p>");
            $em->persist($fixture);
            $this->setReference('place.' . $i, $fixture);
        }
        $em->flush();
    }
}
