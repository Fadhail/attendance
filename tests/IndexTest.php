<?php

use PHPUnit\Framework\TestCase;

#[CoversClass(Index::class)]
class IndexTest extends TestCase
{
    public function testHomePageLoads()
    {
        $_GET['request_site'] = 'home';
        ob_start();
        include 'index.php';
        $output = ob_get_clean();
        $this->assertStringContainsString('<title>Presenza</title>', $output);
    }

    public function testLoginPageLoads()
    {
        $_GET['request_site'] = 'login';
        ob_start();
        include 'index.php';
        $output = ob_get_clean();
        $this->assertStringContainsString('<title>Login</title>', $output);
    }

    public function testLogoutRedirectsToLogin()
    {
        $_GET['request_site'] = 'logout';
        ob_start();
        include 'index.php';
        $output = ob_get_clean();
        $this->assertStringContainsString('Location: login', xdebug_get_headers());
    }

    public function test404PageLoads()
    {
        $_GET['request_site'] = 'nonexistent';
        ob_start();
        include 'index.php';
        $output = ob_get_clean();
        $this->assertStringContainsString('<title>404 - Page Not Found</title>', $output);
    }
}
