<?php

declare(strict_types=1);

/*
 * (c) 2021 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Command;

use App\Entity\Book;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CheckUtf8Command extends Command
{
    public const BATCH_SIZE = 100;

    private $em;

    protected static $defaultName = 'app:check:utf8';

    protected function configure() : void {
        $this
            ->setDescription('Check UTF-8 validity of data')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output) : int {
        $n = 0;
        $class = Book::class;

        $iterator = $this->em->createQuery("SELECT b FROM {$class} b")->iterate();

        foreach ($iterator as $row) {
            $n++;
            /** @var Book $book */
            $book = $row[0];
            if (preg_match("/[\x01-\x1F\x7F]/u", $book->getTitle())) {
                $output->writeln("Book {$book->getId()} has bad title.");
            }
            if (preg_match("/[\x01-\x1F\x7F]/u", $book->getDescription())) {
                $output->writeln("Book {$book->getId()} has bad description.");
            }
            if (0 === $n % self::BATCH_SIZE) {
                $output->writeln("{$n}");
                $this->em->clear();
            }
        }

        return 0;
    }

    /**
     * @required
     */
    public function setEntityManager(EntityManagerInterface $em) : void {
        $this->em = $em;
    }
}
