<?php
/**
 * @name        ContainerInterface
 * @package     Yaapi\DI
 * @copyright   Copyright (C) 2014-2015 http://joomworker.com - All rights reserved.
 * @license     GNU LESSER GENERAL PUBLIC LICENSE Version 2.1 or later - http://www.gnu.org
 */

namespace Yaapi\DI;


/**
 *
 * @since  1.0
 */
interface ContainerInterface
{
    /**
     * Builds an object of class $key and sets it to the container
     * and returns the builded object.
     *
     * @param   string   $key     The class name to build.
     * @param   bool     $shared  True to create a shared resource.
     *
     * @return  mixed    Instance of class specified by $key with all dependencies injected.
     *                   Returns an object if the class exists and false otherwise
     *
     * @since   1.0
     */
    public function buildObject($key, $shared = false);
    
    /**
     * Method to set the key and callback to the dataStore array.
     *
     * @param   string   $key          Name of dataStore key to set.
     * @param   mixed    $value        Callable function to run or string to retrive
     *                                 when requesting the specified $key.
     * @param   boolean  $shared       True to create and store a shared instance.
     * @param   boolean  $protected    True to protect this item from being overwritten. Useful for services.
     *
     * @return  ContainerInterface     This object for chaining.
     *
     * @throws  \OutOfBoundsException  Thrown if the provided key is already set and is protected.
     *
     * @since   1.0
     */
    public function set($key, $value, $shared = false, $protected = false);
    
    /**
     * Method to retrieve the results of running the $callback for the specified $key;
     *
     * @param   string   $key       Name of the dataStore key to get.
     * @param   boolean  $forceNew  True to force creation and return of a new instance.
     *
     * @return  mixed   Results of running the $callback for the specified $key.
     *
     * @since   1.0
     * @throws  \InvalidArgumentException
     */
    public function get($key, $forceNew = false);
    
    /**
     * Method to check if specified dataStore key exists.
     *
     * @param   string  $key  Name of the dataStore key to check.
     *
     * @return  boolean  True for success
     *
     * @since   1.0
     */
    public function exists($key);
    
    /**
     * Register a service provider to the container.
     *
     * @param   ServiceProviderInterface  $provider  The service provider to register.
     *
     * @return  ContainerInterface   This object for chaining.
     *
     * @since   1.0
     */
    public function registerServiceProvider(ServiceProviderInterface $provider);
}
