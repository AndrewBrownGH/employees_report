# The Solution #
I used the Symfony to carry out this task because it has a lot of great benefits: Doctrine ORM (Database Migrations,
Entity, Repository, Fixtures) and creating own Commands.

### Database Migrations ###
You have to execute the [Database migrations](https://symfony.com/doc/current/bundles/DoctrineMigrationsBundle/).
> php bin/console  doctrine:migrations:migrate

### Doctrine Fixtures ###
You can load a fake set of data into a database for testing:
> php bin/console doctrine:fixtures:load

### Receive the Reports ###

To get the reports of the best employees for each day of the **current** week:
> php bin/console report:top-employees

