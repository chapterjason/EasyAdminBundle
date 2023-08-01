<?php

namespace EasyCorp\Bundle\EasyAdminBundle\Tests\Orm;

use EasyCorp\Bundle\EasyAdminBundle\Tests\TestApplication\Controller\AssociationLoremCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Tests\TestApplication\Controller\DashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Tests\TestApplication\Entity\AssociationLorem;
use Symfony\Component\DomCrawler\Crawler;

class AssociationLoremSortTest extends AbstractAssociationTest
{
    private $repository;

    protected function getControllerFqcn(): string
    {
        return AssociationLoremCrudController::class;
    }

    protected function getDashboardFqcn(): string
    {
        return DashboardController::class;
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->client->followRedirects();
        $this->repository = $this->entityManager->getRepository(AssociationLorem::class);
    }

    /**
     * @dataProvider sorting
     */
    public function testSorting(array $query, callable $sortFunction, string $expectedSortIcon)
    {
        // Arrange
        $expectedAmountMapping = [];

        foreach ($this->repository->findAll() as $entity) {
            $expectedAmountMapping[$entity->getName()] = $entity->getAssociationIpsum()?->getName() ?? null;
        }

        $expectedAmountMapping = $sortFunction($expectedAmountMapping);

        // Act
        $crawler = $this->client->request('GET', $this->generateIndexUrl().'&'.http_build_query($query));

        // Assert
        $this->assertResponseIsSuccessful();

        $tableHeader = $this->getTableHeader($crawler, 2);

        $this->assertStringContainsString('Association Ipsum', $tableHeader->text());
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
                $expectedAmount = $mapping[$expectedName] ?? 'Null';

                // Debug output:
                // echo sprintf("ActualName: %s, ExpectedName: %s, ActualAmount: %s, ExpectedAmount: %s\n", $actualName, $expectedName, $actualAmount, $expectedAmount ?? 'null');

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
            ['sort' => ['associationIpsum' => 'ASC']],
            function (array $data) {
                asort($data);

                return $data;
            },
            'fa-arrow-up',
        ];

        yield [
            ['sort' => ['associationIpsum' => 'DESC']],
            function (array $data) {
                arsort($data);

                return $data;
            },
            'fa-arrow-down',
        ];
    }
}
