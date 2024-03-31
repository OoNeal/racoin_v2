<?php

namespace controller\app\test\controller;

use controller\app\actions\get\DepartmentAction;
use controller\app\db\connection;
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
        $getDepartment = new DepartmentAction();
        $departments = $getDepartment->getDepartments();
        $this->assertIsArray($departments);
        $this->assertNotEmpty($departments);
    }
}
