<?php
/**
 * @name        ContainerAwareTraitTest
 * @package     Yaapi\DI\Tests
 * @copyright   Copyright (C) 2014-2015 http://joomworker.com - All rights reserved.
 * @license     GNU LESSER GENERAL PUBLIC LICENSE Version 2.1 or later - http://www.gnu.org
 */

namespace Yaapi\DI\Tests;

use Yaapi\DI\Container;

/**
 * Tests for ContainerAwareTrait class.
 *
 * @since   1.0
 * @covers  \Yaapi\DI\ContainerAwareTrait
 */
class ContainerAwareTraitTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * Holds the Container instance for testing.
	 *
	 * @var    \Yaapi\DI\ContainerAwareTrait
	 * @since  1.0
	 */
	protected $object;

	/**
	 * This method is called before the first test of this test class is run.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public static function setUpBeforeClass()
	{
		// Only run tests on PHP 5.4+
		if (version_compare(PHP_VERSION, '5.4', '<'))
		{
			static::markTestSkipped('Tests are not present in PHP 5.4');
		}
	}

	/**
	 * Setup the tests.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function setUp()
	{
		$this->object = $this->getObjectForTrait('\\Yaapi\\DI\\ContainerAwareTrait');
	}

	/**
	 * Tear down the tests.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function tearDown()
	{
		$this->object = null;
	}

	/**
	 * Tests calling getContainer() without a Container object set
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @coversDefaultClass  getContainer
	 * @expectedException   \UnexpectedValueException
	 */
	public function testGetContainerException()
	{
		$this->object->getContainer();
	}

	/**
	 * Tests calling getContainer() with a Container object set
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @coversDefaultClass  getContainer
	 */
	public function testGetContainer()
	{
		$reflection = new \ReflectionClass($this->object);
		$refProp = $reflection->getProperty('container');
		$refProp->setAccessible(true);
		$refProp->setValue($this->object, new Container);

		$this->assertInstanceOf(
			'\\Yaapi\\DI\\Container',
			$this->object->getContainer(),
			'Validates the Container object was set.'
		);
	}
}
