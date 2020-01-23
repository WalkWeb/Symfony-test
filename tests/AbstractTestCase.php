<?php

declare(strict_types=1);

namespace App\Tests\unit;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

abstract class AbstractTestCase extends WebTestCase
{
    /**
     * @var KernelBrowser
     */
    protected $client;

    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var FormFactoryInterface
     */
    private $factory;

    public function setUp(): void
    {
        $this->client = static::createClient();
        $kernel = $this->client->getKernel();
        $this->entityManager = $kernel->getContainer()->get('doctrine')->getManager();
        $this->factory = $kernel->getContainer()->get('form.factory');
    }

    protected function createForm($type = null, $data = null, array $options = []): FormInterface
    {
        return $this->factory->create($type, $data, array_merge($options, ['csrf_protection' => false]));
    }
}
