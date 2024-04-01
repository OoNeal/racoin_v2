<?php

namespace controller\app\test\controller;

use controller\app\db\connection;
use controller\app\service\classes\DepartmentService;
use PHPUnit\Framework\TestCase;

class getDepartmentTest extends TestCase
{

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
        connection::createConn();
    }

    public function testGetAllDepartments()
    {
        $getDepartment = new DepartmentService();
        $departments = $getDepartment->getDepartments();
        $this->assertIsArray($departments);
        $this->assertNotEmpty($departments);
    }
}
