<?php

namespace controller\app\test\controller;

use controller\app\controller\getDepartment;
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
        $getDepartment = new getDepartment();
        $departments = $getDepartment->getAllDepartments();
        $this->assertIsArray($departments);
        $this->assertNotEmpty($departments);
    }
}
