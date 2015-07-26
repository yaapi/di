<?php
/**
 * @name        Stubs
 * @package     Yaapi\DI\Tests
 * @copyright   Copyright (C) 2014-2015 http://joomworker.com - All rights reserved.
 * @license     GNU LESSER GENERAL PUBLIC LICENSE Version 2.1 or later - http://www.gnu.org
 */
// @codingStandardsIgnoreFile

namespace Yaapi\DI\Tests;


interface StubInterface {}

class Stub1 implements StubInterface {}

class Stub2 implements StubInterface
{
	protected $stub;

	public function __construct(StubInterface $stub)
	{
		$this->stub = $stub;
	}
}

class Stub3
{
	protected $stub;
	protected $stub2;

	public function __construct(StubInterface $stub, StubInterface $stub2)
	{
		$this->stub = $stub;
		$this->stub2 = $stub2;
	}
}

class Stub4 implements StubInterface {}

class Stub5
{
	protected $stub;

	public function __construct(Stub4 $stub)
	{
		$this->stub = $stub;
	}
}

class Stub6
{
	protected $stub;

	public function __construct($stub = 'foo')
	{
		$this->stub = $stub;
	}
}

class Stub7
{
	protected $stub;

	public function __construct($stub)
	{
		$this->stub = $stub;
	}
}

class Stub8
{
	protected $stub;

	public function __construct(DoesntExist $stub)
	{
		$this->stub = $stub;
	}
}

class Stub9
{
}
