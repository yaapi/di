<?php
/**
 * @name        Container
 * @package     Yaapi\DI
 * @copyright   Copyright (C) 2014-2015 http://joomworker.com - All rights reserved.
 * @license     GNU LESSER GENERAL PUBLIC LICENSE Version 2.1 or later - http://www.gnu.org
 */

namespace Yaapi\DI;

use Yaapi\DI\Exception\DependencyResolutionException;

/**
 * The Container class.
 *
 * @since  1.0
 */
class Container implements ContainerInterface
{
	/**
	 * Holds the key aliases.
	 *
	 * @var    array  $aliases
	 * @since  1.0
	 */
	protected $aliases = [];

	/**
	 * Holds the shared instances.
	 *
	 * @var    array  $instances
	 * @since  1.0
	 */
	protected $instances = [];

	/**
	 * Holds the keys, their callbacks, and whether or not
	 * the item is meant to be a shared resource.
	 *
	 * @var    array  $dataStore
	 * @since  1.0
	 */
	protected $dataStore = [];

	/**
	 * Parent for hierarchical containers.
	 *
	 * @var    Container
	 * @since  1.0
	 */
	protected $parent;

	/**
	 * Constructor for the DI Container
	 *
	 * @param   Container  $parent  Parent for hierarchical containers.
	 *
	 * @since   1.0
	 */
	public function __construct(ContainerInterface $parent = null)
	{
		$this->parent = $parent;
	}

	/**
	 * Create an alias for a given key for easy access.
	 *
	 * @param   string  $alias      The alias name
	 * @param   string  $key        The key to alias
	 *
	 * @return  ContainerInterface  This object for chaining.
	 *
	 * @since   1.0
	 */
	//7/public function alias(string $alias, string $key): ContainerInterface
	public function alias($alias, $key)
	{
		$this->aliases[$alias] = $key;

		return $this;
	}

	/**
	 * Search the aliases property for a matching alias key.
	 *
	 * @param   string  $key  The key to search for.
	 *
	 * @return  string
	 *
	 * @since   1.0
	 */
	//7/protected function resolveAlias(string $key): string
	protected function resolveAlias($key)
	{
		if (isset($this->aliases[$key]))
		{
			return $this->aliases[$key];
		}

		if ($this->parent instanceof ContainerInterface)
		{
			return $this->parent->resolveAlias($key);
		}

		return $key;
	}

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
	//7/public function buildObject(string $key, bool $shared = false);
	public function buildObject($key, $shared = false)
	{
		try
		{
			$reflection = new \ReflectionClass($key);
		}
		catch (\ReflectionException $e)
		{
			return false;
		}

		$constructor = $reflection->getConstructor();

		// If there are no parameters, just return a new object.
		if (is_null($constructor))
		{
			$callback = function () use ($key) {
				return new $key;
			};
		}
		else
		{
			$newInstanceArgs = $this->getMethodArgs($constructor);

			// Create a callable for the dataStore
			$callback = function () use ($reflection, $newInstanceArgs) {
				return $reflection->newInstanceArgs($newInstanceArgs);
			};
		}

		return $this->set($key, $callback, $shared)->get($key);
	}

	/**
	 * Convenience method for building a shared object.
	 *
	 * @param   string  $key  The class name to build.
	 *
	 * @return  object  Instance of class specified by $key with all dependencies injected.
	 *
	 * @since   1.0
	 */
	//7/public function buildSharedObject(string $key)
	public function buildSharedObject($key)
	{
		return $this->buildObject($key, true);
	}
	
	/**
	 * Get or build a container object.
	 *
	 * @param   string   $class   A qualified class name.
	 *
	 * @return  object
	 * @since   1.0
	 */
	//7/public function getBuildObject(string $class, bool $shared = false)
	public function getBuildObject($class, $shared = false)
	{
		// If class is set within the container we retrieve it, otherwise we build the object.
		return (true === $this->exists($class)) ? $this->get($class) : $this->buildObject($class, $shared);
	}
	
	//7/public function getBuildObject(string $class)
	public function getBuildSharedObject($class)
	{
		// If class is set within the container we retrieve it, otherwise we build the object.
		return (true === $this->exists($class)) ? $this->get($class) : $this->buildSharedObject($class);
	}

	/**
	 * Create a child Container with a new property scope that
	 * has the ability to access the parent scope when resolving.
	 *
	 * @return  ContainerInterface  This object for chaining.
	 *
	 * @since   1.0
	 */
	//7/public function createChild(): ContainerInterface
	public function createChild()
	{
		return new static($this);
	}

	/**
	 * Extend a defined service Closure by wrapping the existing one with a new Closure.  This
	 * works very similar to a decorator pattern.  Note that this only works on service Closures
	 * that have been defined in the current Provider, not parent providers.
	 *
	 * @param   string    $key       The unique identifier for the Closure or property.
	 * @param   \Closure  $callable  A Closure to wrap the original service Closure.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @throws  \InvalidArgumentException
	 */
	//7/public function extend(string $key, \Closure $callable): void
	public function extend($key, \Closure $callable)
	{
		$key = $this->resolveAlias($key);
		$raw = $this->getRaw($key);

		if (is_null($raw))
		{
			throw new \InvalidArgumentException(sprintf('The requested key %s does not exist to extend.', $key));
		}

		$closure = function ($c) use($callable, $raw) {
			return $callable($raw['callback']($c), $c);
		};

		$this->set($key, $closure, $raw['shared']);
	}

	/**
	 * Build an array of constructor parameters.
	 *
	 * @param   \ReflectionMethod  $method  Method for which to build the argument array.
	 *
	 * @return  array  Array of arguments to pass to the method.
	 *
	 * @since   1.0
	 * @throws  DependencyResolutionException
	 */
	//7/protected function getMethodArgs(\ReflectionMethod $method): array
	protected function getMethodArgs(\ReflectionMethod $method)
	{
		$methodArgs = [];

		foreach ($method->getParameters() as $param)
		{
			$dependency = $param->getClass();
			$dependencyVarName = $param->getName();

			// If we have a dependency, that means it has been type-hinted.
			if (!is_null($dependency))
			{
				$dependencyClassName = $dependency->getName();

				// If the dependency class name is registered with this container or a parent, use it.
				if ($this->getRaw($dependencyClassName) !== null)
				{
					$depObject = $this->get($dependencyClassName);
				}
				else
				{
					$depObject = $this->buildObject($dependencyClassName);
				}

				if ($depObject instanceof $dependencyClassName)
				{
					$methodArgs[] = $depObject;
					continue;
				}
			}

			// Finally, if there is a default parameter, use it.
			if ($param->isOptional())
			{
				$methodArgs[] = $param->getDefaultValue();
				continue;
			}

			// Couldn't resolve dependency, and no default was provided.
			throw new DependencyResolutionException(sprintf('Could not resolve dependency: %s', $dependencyVarName));
		}

		return $methodArgs;
	}

	/**
	 * Method to set the key and callback to the dataStore array.
	 *
	 * @param   string   $key          Name of dataStore key to set.
	 * @param   mixed    $value        Callable function to run or string to retrive when requesting the specified $key.
	 * @param   boolean  $shared       True to create and store a shared instance.
	 * @param   boolean  $protected    True to protect this item from being overwritten. Useful for services.
	 *
	 * @return  ContainerInterface     This object for chaining.
	 *
	 * @throws  \OutOfBoundsException  Thrown if the provided key is already set and is protected.
	 *
	 * @since   1.0
	 */
	//7/public function set(string $key, $value, bool $shared = false, bool $protected = false): ContainerInterface
	public function set($key, $value, $shared = false, $protected = false)
	{
		if (isset($this->dataStore[$key]) && true === $this->dataStore[$key]['protected'])
		{
			throw new \OutOfBoundsException(sprintf('Key %s is protected and can\'t be overwritten.', $key));
		}

		// If the provided $value is not a closure, make it one now for easy resolution.
		if (!is_callable($value))
		{
			$value = function () use ($value) {
				return $value;
			};
		}

		$this->dataStore[$key] = [
			'callback'  => $value,
			'shared'    => $shared,
			'protected' => $protected
		];

		return $this;
	}

	/**
	 * Convenience method for creating protected keys.
	 *
	 * @param   string    $key       Name of dataStore key to set.
	 * @param   callable  $callback  Callable function to run when requesting the specified $key.
	 * @param   bool      $shared    True to create and store a shared instance.
	 *
	 * @return  Container  This object for chaining.
	 *
	 * @since   1.0
	 */
	//7/public function set(string $key, $value, bool $shared = false): ContainerInterface
	public function protect($key, $callback, $shared = false)
	{
		return $this->set($key, $callback, $shared, true);
	}

	/**
	 * Convenience method for creating shared keys.
	 *
	 * @param   string    $key        Name of dataStore key to set.
	 * @param   callable  $callback   Callable function to run when requesting the specified $key.
	 * @param   bool      $protected  True to create and store a shared instance.
	 *
	 * @return  Container  This object for chaining.
	 *
	 * @since   1.0
	 */
	//7/public function set(string $key, $value, bool $protected = false): ContainerInterface
	public function share($key, $callback, $protected = false)
	{
		return $this->set($key, $callback, true, $protected);
	}

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
	//7/public function get(string $key, bool $forceNew = false)
	public function get($key, $forceNew = false)
	{
		$key = $this->resolveAlias($key);
		$raw = $this->getRaw($key);

		if (is_null($raw))
		{
			throw new \InvalidArgumentException(sprintf('Key %s has not been registered with the container.', $key));
		}

		if ($raw['shared'])
		{
			if (!isset($this->instances[$key]) || $forceNew)
			{
				$this->instances[$key] = $raw['callback']($this);
			}

			return $this->instances[$key];
		}

		return call_user_func($raw['callback'], $this);
	}

	/**
	 * Method to check if specified dataStore key exists.
	 *
	 * @param   string  $key  Name of the dataStore key to check.
	 *
	 * @return  boolean  True for success
	 *
	 * @since   1.0
	 */
	//7/public function exists(string $key): bool
	public function exists($key)
	{
		$key = $this->resolveAlias($key);

		return (bool) $this->getRaw($key);
	}

	/**
	 * Get the raw data assigned to a key.
	 *
	 * @param   string  $key  The key for which to get the stored item.
	 *
	 * @return  mixed
	 *
	 * @since   1.0
	 */
	//7/protected function getRaw(string $key)
	protected function getRaw($key)
	{
		if (isset($this->dataStore[$key]))
		{
			return $this->dataStore[$key];
		}

		$aliasKey = $this->resolveAlias($key);

		if ($aliasKey != $key && isset($this->dataStore[$aliasKey]))
		{
			return $this->dataStore[$aliasKey];
		}

		if ($this->parent instanceof ContainerInterface)
		{
			return $this->parent->getRaw($key);
		}

		return null;
	}

	/**
	 * Method to force the container to return a new instance
	 * of the results of the callback for requested $key.
	 *
	 * @param   string  $key  Name of the dataStore key to get.
	 *
	 * @return  mixed   Results of running the $callback for the specified $key.
	 *
	 * @since   1.0
	 */
	//7/public function getNewInstance(string $key)
	public function getNewInstance($key)
	{
		return $this->get($key, true);
	}

	/**
	 * Register a service provider to the container.
	 *
	 * @param   ServiceProviderInterface  $provider  The service provider to register.
	 *
	 * @return  ContainerInterface  This object for chaining.
	 *
	 * @since   1.0
	 */
	//7/public function registerServiceProvider(ServiceProviderInterface $provider): ContainerInterface
	public function registerServiceProvider(ServiceProviderInterface $provider)
	{
		$provider->register($this);

		return $this;
	}
}
