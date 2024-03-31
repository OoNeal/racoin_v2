<?php

namespace controller\app\test\controller;

use controller\app\actions\get\CategoryAction;
use controller\app\db\connection;
use PHPUnit\Framework\TestCase;

class getCategorieTest extends TestCase
{
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
        connection::createConn();
    }

    public function testGetCategories()
    {
        $getCategorie = new CategoryAction();
        $categories = $getCategorie->getCategories();
        $this->assertIsArray($categories);
        $this->assertNotEmpty($categories);

    }
}
