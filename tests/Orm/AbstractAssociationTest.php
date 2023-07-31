<?php

namespace EasyCorp\Bundle\EasyAdminBundle\Tests\Orm;

use EasyCorp\Bundle\EasyAdminBundle\Test\AbstractCrudTestCase;
use Symfony\Component\DomCrawler\Crawler;

abstract class AbstractAssociationTest extends AbstractCrudTestCase
{
    protected function getTable(Crawler $crawler): Crawler
    {
        return $crawler->filter('.content-body > table.datagrid')->first();
    }

    protected function getTableHeaders(Crawler $crawler): Crawler
    {
        return $this->getTable($crawler)->filter('thead > tr > th');
    }

    protected function getTableHeader(Crawler $crawler, int $position): Crawler
    {
        return $this->getTable($crawler)->filter('thead > tr > th')->eq($position);
    }

    protected function getTableRows(Crawler $crawler): Crawler
    {
        return $this->getTable($crawler)->filter('tbody > tr');
    }

    protected function getCellText(Crawler $tr, int $position): string
    {
        return $tr->filter('td')->eq($position)->text();
    }
}
