# DI Package [![Build Status](https://travis-ci.org/yaapi/di.png?branch=master)](https://travis-ci.org/yaapi/di)

[![Latest Stable Version](https://poser.pugx.org/yaapi/di/v/stable)](https://packagist.org/packages/yaapi/di)
[![Latest Unstable Version](https://poser.pugx.org/yaapi/di/v/unstable)](https://packagist.org/packages/yaapi/di)
[![License](https://poser.pugx.org/yaapi/di/license)](https://packagist.org/packages/yaapi/di)

The Dependency Injection package for YaAPI provides a simple IoC Container for your application.
Dependency Injection allows you the developer to control the construction and lifecycle of your objects,
rather than leaving that control to the classes themselves. Instead of hard coding a class's dependencies
within the class `__construct()` method, you instead provide to a class the dependencies it requires as
arguments to its constructor. This helps to decrease hard dependencies and to create loosely coupled code.

An Inversion of Control (IoC) Container helps you to manage these dependencies in a controlled fashion.


## Installation via Composer

Add `"yaapi/di": "^1.0.1"` to the require block in your composer.json.

```json
{
	"require": {
		"yaapi/di": "^1.0.1"
	}
}
```

Alternatively, you can simply run the following from the command line:

```sh
composer require yaapi/di:^1.0.1
```
