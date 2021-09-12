<?php
declare(strict_types=1);

namespace Tests\Application;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UsersControllerTest extends WebTestCase
{
    private KernelBrowser $client;

    protected function setUp(): void
    {
        $this->client = self::createClient();
    }

    public function testCreateUser(): void
    {
        $rand = random_int(1, 100);
        $email = 'test'.$rand.'@wp.pl';

        $this->client->request(
            method: 'POST',
            uri: '/users',
            parameters: ['email' => $email],
            server: [],
        );
        self::assertResponseIsSuccessful();
    }

    public function deleteUser(): void
    {
        $this->client->request(
            method: 'POST',
            uri: '/users',
            parameters: ['email' => 'test@wp.pl'],
            server: ['API_KEY' => '7e4a903f-d206-4fd0-91d4-7d2464a1f80e'],
        );
        self::assertResponseStatusCodeSame(400);
    }
}
