# API

A PHP application based on Symfony and with GraphQL endpoints.

**All commands have to be run in the `api` service (`make api`).**

## Code

#### Comments

Comments should only be added if a piece of code is not clear enough.
**Most of the time, using explicit class/function/exception names should be enough.**

Anyway, comments have to start with an uppercase and end with a punctuation.

For instance:

```php
// this code do X because of Y <- incorrect
// Do X because of Y. <- correct
``` 

### Creating a use case

### Tests

// TODO explain how to write tests.
// TODO explain that a use case must be injected somewhere before being usable in tests.
// TODO group tests.

Before pushing your commits to the repository or even while coding, run the following command:

```
composer pest
```

It will execute all tests and display the result and the code coverage in your terminal.

Speaking of code coverage, an HTML output is also available under the `converage` folder.
Do not hesitate to take a look at it!

You can also run tests per group, for instance:

```
pest --group=user,company
```

### Static analysis tools

Before pushing your commits to the repository or even while coding, run the following commands:

```
composer csfix &&
composer cscheck &&
composer phpstan &&
composer deptrac &&
composer yaml-lint
```

## Composer

When installing a PHP dependency, ask yourself if it is a `dev` dependency or not:

```
composer require [--dev] [package]
COMPOSER_MEMORY_LIMIT=-1 composer normalize
```

As we're using Symfony, make sure to choose the package with Symfony support (aka bundle) if available.

📣&nbsp;&nbsp;Vagrant users might encounter some issues with Composer. 
A workaround solution is to add the flag `--prefer-source` to your Composer command.

## Database

### Create a migration

```
php bin/console doctrine:migrations:generate
```

This command will generate a new empty migration in the *src/api/migrations* folder.

Add a meaningful description:

```php
public function getDescription() : string
{
    return 'Create X, Y and Z tables.';
}
```

And throw the following exception the `down` method:

```php
public function down(Schema $schema) : void
{
    throw new RuntimeException('Never rollback a migration!');
}
```

You may now update the `up` method.

### Apply migrations

```
php bin/console doctrine:migrations:migrate -n
```

This command will apply the new migrations to the database.

If you've edited an existing migration, you'll have to reset the database first:

```bash title="console"
php bin/console doctrine:database:drop -n --force &&
php bin/console doctrine:database:create -n &&
php bin/console doctrine:migrations:migrate -n
```

📣&nbsp;&nbsp;You should **only** do that if a remote environment like your production did not already apply the migration.


### Generate PHP classes:

```
php bin/console tdbm:generate
```

This command will regenerate the TDBM `Models` and `DAOs`.

### Development data

The [DevFixturesCommand.php](src/Infrastructure/Command/DevFixturesCommand.php) class provides a Symfony command for 
initializing your development database with dummy data:

```
php bin/console app:fixtures:dev
```

It uses the class [AppFixtures.php](src/Infrastructure/Fixtures/AppFixtures.php) for that task.

You should edit according to your needs.