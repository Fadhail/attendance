<?php

use PHPUnit\Framework\TestCase;

class PagesTest extends TestCase
{

    public function testAdminHomePageLoadsSuccessfully()
    {
        $_SESSION['user'] = (object) ['role' => 'admin'];
        $output = $this->getPageOutput('admin/home');
        $this->assertStringContainsString('<title>Admin</title>', $output);
    }

    public function testDosenHomePageLoadsSuccessfully()
    {
        $_SESSION['user'] = (object) ['role' => 'dosen'];
        $output = $this->getPageOutput('dosen/home');
        $this->assertStringContainsString('<title>Dosen</title>', $output);
    }

    private function getPageOutput($page)
    {
        ob_start();
        include __DIR__ . "/../resources/pages/{$page}.php";
        return ob_get_clean();
    }
}