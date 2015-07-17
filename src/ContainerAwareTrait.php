<?php
/**
 * @name        ContainerAwareTrait
 * @package     Yaapi\DI
 * @copyright   Copyright (C) 2014-2015 http://joomworker.com - All rights reserved.
 * @license     GNU LESSER GENERAL PUBLIC LICENSE Version 2.1 or later - http://www.gnu.org
 */

namespace Yaapi\DI;


/**
 * Defines the trait for a Container Aware Class.
 *
 * @since  1.0
 */
trait ContainerAwareTrait
{
	/**
	 * DI Container
	 *
	 * @var    ContainerInterface   $container
	 * @since  1.0
	 */
	private $container;

	
	/**
	 * Get the DI container.
	 *
	 * @return  ContainerInterface
	 *
	 * @since   1.0
	 * @throws  \UnexpectedValueException May be thrown if the container has not been set.
	 */
	//7/public function getContainer(): ContainerInterface
	public function getContainer()
	{
		if ($this->container instanceof ContainerInterface)
		{
			return $this->container;
		}

		throw new \UnexpectedValueException(sprintf('Container is not an instance of ContainerInterface or not set in %s.', __CLASS__));
	}

	/**
	 * Set the DI container.
	 *
	 * @param   Container  $container  The DI container.
	 *
	 * @return  object     $this       Returns itself to support chaining.
	 *
	 * @since   1.0
	 */
	//7/protected function setContainer(ContainerInterface $container)
	protected function setContainer(ContainerInterface $container)
	{
		$this->container = $container;

		return $this;
	}
}
