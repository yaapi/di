<?php
/**
 * @name        ServiceProviderInterface
 * @package     Yaapi\DI
 * @copyright   Copyright (C) 2014-2015 http://joomworker.com - All rights reserved.
 * @license     GNU LESSER GENERAL PUBLIC LICENSE Version 2.1 or later - http://www.gnu.org
 */

namespace Yaapi\DI;


/**
 * Defines the interface for a Service Provider.
 *
 * @since  1.0
 */
interface ServiceProviderInterface
{
    /**
     * Registers the service provider with a DI container.
     *
     * @param   ContainerInterface  $container  The DI container.
     *
     * @return  void
     *
     * @since   1.0
     */
    public function register(ContainerInterface $container);
}
