<?php declare(strict_types=1);

namespace Tests\Application;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ArticlesControllerTest extends WebTestCase
{
    private KernelBrowser $client;

    protected function setUp(): void
    {
        $this->client = self::createClient();
    }

    public function testCreateArticle(): void
    {
        $this->client->request(
            method: 'POST',
            uri: '/articles',
            parameters: ['title' => 'Super tygodnik2', 'content' => 'lorem'],
            server: ['API_KEY' => '7e4a903f-d206-4fd0-91d4-7d2464a1f80e'],
        );
        self::assertResponseStatusCodeSame(400);
    }
    public function testGetArticles(): void
    {
        $this->client->request(
            method: 'GET',
            uri: '/articles',
            server: ['API_KEY' => '7e4a903f-d206-4fd0-91d4-7d2464a1f80e'],
        );
        self::assertResponseStatusCodeSame(400);
    }
}
