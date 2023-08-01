<?php

namespace EasyCorp\Bundle\EasyAdminBundle\Tests\Orm;

use EasyCorp\Bundle\EasyAdminBundle\Tests\TestApplication\Controller\AssociationFizzCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Tests\TestApplication\Controller\DashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Tests\TestApplication\Entity\AssociationFizz;
use Symfony\Component\DomCrawler\Crawler;

class AssociationFizzSortTest extends AbstractAssociationTest
{
    private $repository;

    protected function getControllerFqcn(): string
    {
        return AssociationFizzCrudController::class;
    }

    protected function getDashboardFqcn(): string
    {
        return DashboardController::class;
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->client->followRedirects();
        $this->repository = $this->entityManager->getRepository(AssociationFizz::class);
    }

    /**
     * @dataProvider sorting
     */
    public function testSorting(array $query, callable $sortFunction, string $expectedSortIcon)
    {
        // Arrange
        $expectedAmountMapping = [];

        foreach ($this->repository->findAll() as $entity) {
            $expectedAmountMapping[$entity->getName()] = $entity->getAssociationBaz()->getName();
        }

        $expectedAmountMapping = $sortFunction($expectedAmountMapping);

        // Act
        $crawler = $this->client->request('GET', $this->generateIndexUrl().'&'.http_build_query($query));

        // Assert
        $this->assertResponseIsSuccessful();

        $tableHeader = $this->getTableHeader($crawler, 2);

        $this->assertStringContainsString('Association Baz', $tableHeader->text());
        $this->assertStringContainsString($expectedSortIcon, $tableHeader->html());

        $index = 0;
        $mapping = $expectedAmountMapping;
        $keys = array_keys($mapping);

        // Debug output:
        // echo "Break!".PHP_EOL;

        $this->getTableRows($crawler)
            ->each(function (Crawler $tr) use (&$index, $keys, $mapping) {
                $actualName = $this->getCellText($tr, 1);
                $actualAmount = $this->getCellText($tr, 2);
                $expectedName = $keys[$index++];
                $expectedAmount = $mapping[$expectedName];

                // Debug output:
                // echo sprintf("Name: %s, Actual: %s, Expected: %s\n", $actualName, $actualAmount, $expectedAmount);

                $this->assertSame($expectedName, $actualName);
                $this->assertSame($expectedAmount, $actualAmount, sprintf('The entity "%s" is expected to have "%s" as association, but it has %s', $actualName, $expectedAmount, $actualAmount));
            });
    }

    public function sorting(): \Generator
    {
        yield [
            [],
            function (array $data) {
                return $data;
            },
            'fa-sort',
        ];

        yield [
            ['sort' => ['associationBaz' => 'ASC']],
            function (array $data) {
                asort($data);

                return $data;
            },
            'fa-arrow-up',
        ];

        yield [
            ['sort' => ['associationBaz' => 'DESC']],
            function (array $data) {
                arsort($data);

                return $data;
            },
            'fa-arrow-down',
        ];
    }
}
