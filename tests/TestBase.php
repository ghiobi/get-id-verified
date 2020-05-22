<?php
namespace Tests;

use \WP_Mock;
use \WP_Mock\Tools\TestCase;

WP_Mock::bootstrap();

abstract class TestBase extends TestCase {
	public function setUp(): void {
		WP_Mock::setUp();
	}

	public function tearDown(): void {
		WP_Mock::tearDown();
	}
}