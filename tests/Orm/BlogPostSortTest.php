<?php

namespace EasyCorp\Bundle\EasyAdminBundle\Tests\Orm;

use EasyCorp\Bundle\EasyAdminBundle\Tests\TestApplication\Controller\BlogPostSortCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Tests\TestApplication\Controller\SecureDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Tests\TestApplication\Entity\BlogPost;
use Symfony\Component\DomCrawler\Crawler;

class BlogPostSortTest extends AbstractAssociationTest
{
    private $blogPostRepository;

    protected function getControllerFqcn(): string
    {
        return BlogPostSortCrudController::class;
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

        $this->blogPostRepository = $this->entityManager->getRepository(BlogPost::class);
    }

    /**
     * @dataProvider categorySorting
     */
    public function testCategorySorting(array $query, callable $sortFunction, string $expectedSortIcon)
    {
        // Arrange
        $expectedAmountMapping = [];

        foreach ($this->blogPostRepository->findAll() as $blogPost) {
            $expectedAmountMapping[$blogPost->getTitle()] = \count($blogPost->getCategories());
        }

        $expectedAmountMapping = $sortFunction($expectedAmountMapping);

        // Act
        $crawler = $this->client->request('GET', $this->generateIndexUrl().'&'.http_build_query($query));

        // Assert
        $this->assertResponseIsSuccessful();

        $tableHeader = $this->getTableHeader($crawler, 2);

        $this->assertStringContainsString('Categories', $tableHeader->text());
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
                $this->assertSame($expected, $count, sprintf('The blog post "%s" is expected to have %d categories, but it has %d', $name, $expected, $count));
            });
    }

    public function categorySorting(): \Generator
    {
        yield [
            [],
            function (array $data) {
                return $data;
            },
            'fa-sort',
        ];

        yield [
            ['sort' => ['categories' => 'ASC']],
            function (array $data) {
                asort($data);

                return $data;
            },
            'fa-arrow-up',
        ];

        yield [
            ['sort' => ['categories' => 'DESC']],
            function (array $data) {
                arsort($data);

                return $data;
            },
            'fa-arrow-down',
        ];
    }
}
