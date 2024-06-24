<?php

declare(strict_types=1);

namespace App\Tests\Integration;

use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

abstract class IntegrationTest extends KernelTestCase
{
    protected EntityManagerInterface $entityManager;

    #[\Override]
    protected function setUp(): void
    {
        parent::setUp();
        self::bootKernel();

        $this->entityManager = static::getContainer()->get('doctrine.orm.entity_manager');
        $purger = new ORMPurger($this->entityManager);
        $purger->purge();
    }

    #[\Override]
    protected function tearDown(): void
    {
        parent::tearDown();
    }
}
