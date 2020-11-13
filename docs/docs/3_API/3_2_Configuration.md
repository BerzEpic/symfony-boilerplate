---
title: Configuration
slug: /api/configuration
---

General documentation on how to configure Symfony. More details are available in the [Guides](../guides/i18n) section
or in the [official documentation](https://symfony.com/doc/current/configuration.html).

## Symfony

The *src/api/config* folder contains Symfony's configuration.

There are two main parts:

* *src/api/config/services.yaml*: YAML configuration file for your application.
* *src/api/config/packages* folder: YAML configuration files of the bundles (Symfony packages).

The *packages* folder root files are the default configurations of the bundles.

According to the `APP_ENV` value of the `api` service, files from the following folders:
 
* *src/api/config/packages/dev*
* *src/api/config/packages/test*
* *src/api/config/packages/prod*

will extend/override the default configurations.

You often don't want to extend/override a configuration directly but instead create a variable. For instance,
the `DATABASE_URL` value contains secrets (the database hostname, password, etc.) you should not commit.
Also, you might use `APP_ENV=prod` for different environments (like staging and production), which do not use the same 
database.

That's why Symfony allows doing the following:

```yaml title="src/api/config/doctrine.yaml"
doctrine:
    dbal:
        url: '%env(resolve:DATABASE_URL)%'
```

This instruction will fetch the value of the given environment variable. 
See the [official documentation](https://symfony.com/doc/current/configuration/env_var_processors.html) for more details.

:::note

📣&nbsp;&nbsp;In your development environment, do not put these environment variables in the *.env* file 
from the *src/api* folder, but instead, put them under the `environment` key from the `api` service of 
your *docker-compose.yml* file.

:::

### Parameters

If you need the value of an environment variable in your code, use the Symfony parameters.

For instance:

```yaml title="src/api/config/services.yaml"
parameters:
    app.foo: : '%env(FOO)%'
```

```php
# A class.
private string $foo;

public function __construct(
    ParameterBagInterface $parameters
) {
    $this->foo = $parameters->get('app.foo');
}
```

## Composer

:::note

📣&nbsp;&nbsp;All commands have to be run in the `api` service (`make api`).

:::

When installing a PHP dependency, ask yourself if it is a `dev` dependency or not:

```
composer require [--dev] [package]
COMPOSER_MEMORY_LIMIT=-1 composer normalize
```

As we're using Symfony, make sure to choose the package with Symfony support (aka bundle) if available.

:::note

📣&nbsp;&nbsp;Vagrant users might encounter some issues with Composer. 
A workaround solution is to add the flag `--prefer-source` to your Composer command.

:::