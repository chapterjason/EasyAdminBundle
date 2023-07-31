<?php

namespace EasyCorp\Bundle\EasyAdminBundle\Tests\Orm;

use EasyCorp\Bundle\EasyAdminBundle\Tests\TestApplication\Controller\SecureDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Tests\TestApplication\Controller\UserSortCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Tests\TestApplication\Entity\User;
use Symfony\Component\DomCrawler\Crawler;

class UserSortTest extends AbstractAssociationTest
{
    private $userRepository;

    protected function getControllerFqcn(): string
    {
        return UserSortCrudController::class;
    }

    protected function getDashboardFqcn(): string
    {
        return SecureDashboardController::class;
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->client->followRedirects();
        $this->client->setServerParameters(['PHP_AUTH_USER' => 'admin', 'PHP_AUTH_PW' => '1234']);

        $this->userRepository = $this->entityManager->getRepository(User::class);
    }

    /**
     * @dataProvider userSorting
     */
    public function testUserSorting(array $query, callable $sortFunction, string $expectedSortIcon)
    {
        // Arrange
        $expectedAmountMapping = [];

        foreach ($this->userRepository->findAll() as $user) {
            $expectedAmountMapping[$user->getName()] = \count($user->getBlogPosts());
        }

        $expectedAmountMapping = $sortFunction($expectedAmountMapping);

        // Act
        $crawler = $this->client->request('GET', $this->generateIndexUrl().'&'.http_build_query($query));

        // Assert
        $this->assertResponseIsSuccessful();

        $tableHeader = $this->getTableHeader($crawler, 2);

        $this->assertStringContainsString('Blog Posts', $tableHeader->text());
        $this->assertStringContainsString($expectedSortIcon, $tableHeader->html());

        $index = 0;
        $mapping = $expectedAmountMapping;
        $keys = array_keys($mapping);

        $this->getTableRows($crawler)
            ->each(function (Crawler $tr) use (&$index, $keys, $mapping) {
                $name = $this->getCellText($tr, 1);
                $count = (int) $this->getCellText($tr, 2);
                $key = $keys[$index++];
                $expected = $mapping[$key];

                $this->assertSame($key, $name);
                $this->assertSame($expected, $count, sprintf('The category "%s" is expected to have %d blog posts, but it has %d', $name, $expected, $count));
            });
    }

    public function userSorting(): \Generator
    {
        yield [
            [],
            function (array $data) {
                return $data;
            },
            'fa-sort',
        ];

        yield [
            ['sort' => ['blogPosts' => 'ASC']],
            function (array $data) {
                asort($data);

                return $data;
            },
            'fa-arrow-up',
        ];

        yield [
            ['sort' => ['blogPosts' => 'DESC']],
            function (array $data) {
                arsort($data);

                return $data;
            },
            'fa-arrow-down',
        ];
    }
}
