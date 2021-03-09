<?php

declare(strict_types=1);

/*
 * (c) 2021 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\DataFixtures;

use App\Entity\Contribution;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ContributionFixtures extends Fixture implements DependentFixtureInterface {
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $em) : void {
        for ($i = 1; $i <= 4; $i++) {
            $fixture = new Contribution();

            $fixture->setRole($this->getReference('role.' . $i));
            $fixture->setPerson($this->getReference('person.' . $i));
            $fixture->setPublication($this->getReference('publication.' . $i));
            $em->persist($fixture);
            $this->setReference('contribution.' . $i, $fixture);
        }
        $em->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function getDependencies() {
        return [
            RoleFixtures::class,
            PersonFixtures::class,
            PublicationFixtures::class,
        ];
    }
}
