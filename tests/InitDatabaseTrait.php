<?php

declare(strict_types=1);

namespace Test;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Component\HttpKernel\KernelInterface;

trait InitDatabaseTrait
{
    private function initDatabase(KernelInterface $kernel): void
    {
        /** @var EntityManagerInterface $entityManager */
        $entityManager = $kernel->getContainer()->get('doctrine.orm.entity_manager');
        $metaData = $entityManager->getMetadataFactory()->getAllMetadata();
        $schemaTool = new SchemaTool($entityManager);
        $schemaTool->updateSchema($metaData);
    }
}
