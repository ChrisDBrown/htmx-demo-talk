<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use App\Kernel;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;
use Helmich\JsonAssert\JsonAssertions;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Console\Tester\CommandTester;

abstract class FunctionalTest extends WebTestCase
{
    use JsonAssertions;

    protected EntityManagerInterface $entityManager;

    protected KernelBrowser $client;

    #[\Override]
    protected function setUp(): void
    {
        parent::setUp();
        $this->client = static::createClient();

        $this->entityManager = static::getContainer()->get('doctrine.orm.entity_manager');
        $purger = new ORMPurger($this->entityManager);
        $purger->purge();
    }

    #[\Override]
    protected function tearDown(): void
    {
        parent::tearDown();
    }

    protected function getCommandTester(string $name): CommandTester
    {
        /** @var Kernel $kernel */
        $kernel = self::$kernel;
        $app = new Application($kernel);
        $command = $app->find($name);

        return new CommandTester($command);
    }
}
