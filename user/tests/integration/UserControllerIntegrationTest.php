<?php

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Users;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Dotenv\Dotenv;

class UserControllerIntegrationTest extends WebTestCase
{
    private $client;

    protected function setUp(): void
    {
        parent::setUp();
        (new Dotenv())->loadEnv(dirname(__DIR__, 2).'/.env.test');
        $this->client = static::createClient();
    }

    protected static function getKernelClass(): string
    {
        return \App\Kernel::class;
    }

    public function testCreateUserWithInvalidData()
    {
        // Get the entity manager
        $entityManager = $this->client->getContainer()->get('doctrine')->getManager();

        // Clear existing data
        $entityManager->createQuery('DELETE FROM App\Entity\Users')->execute();

        // Make a request to the controller with invalid data (missing required fields)
        $this->client->request('POST', '/api/user', [], [], [], json_encode([
            'email' => '',
            'firstName' => 'ali',
            'lastName' => 'ahmed'
        ]));

        // Assert that the response status code is 400 (Bad Request)
        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());

        // Print the response content to the console
        echo "\nResponse content for invalid data:\n";
        echo $this->client->getResponse()->getContent() . "\n";
    }

    public function testCreateUserWithValidData()
    {
        // Get the entity manager
        $entityManager = $this->client->getContainer()->get('doctrine')->getManager();

        // Clear existing data
        $entityManager->createQuery('DELETE FROM App\Entity\Users')->execute();

        // Make a request to the controller with valid data
        $this->client->request('POST', '/api/user', [], [], [], json_encode([
            'email' => 'zubidev@gmail.com',
            'firstName' => 'Muhammad',
            'lastName' => 'khan'
        ]));

        // Assert that the response status code is 201 (Created)
        $this->assertEquals(201, $this->client->getResponse()->getStatusCode());

        // Print the response content to the console
        echo "\nResponse content for valid data:\n";
        echo $this->client->getResponse()->getContent() . "\n";
    }
}

