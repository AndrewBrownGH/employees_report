# Technical task #
The purpose of the task is to write a program that will fetch the information about the employees' time reports from the example database and for each day of the week calculate the top 3 employees who have the highest average number of working hours reported on the corresponding weekday. 

The following is an example of the database tables structure: 

`employees`
| id | name | 
|----| -----|
| 42 | John | 
| 43 | Jane | 

`time_reports`
| id | employee_id | hours |    date   | 
|----|-------------|-------|-----------|
|  1 |      42     |  4.5  | 12/1/2020 | 
|  2 |      42     |  7.0  | 12/2/2020 | 
|  3 |      43     |  5.5  | 12/1/2020 | 
|  4 |      43     |  6.0  | 12/2/2020 | 

employee_id is a foreign key for employee.id
hours has float type 
date is a date in US format `M/d/YYYY` 

The following is a list of requirements for the program: 
1. The output shall include the names of the people and their average working hours in parenthesis for that day rounded up to the 2 decimal points 
2. The output shall include line items for all 7 weekdays  
3. The program shall work correctly for the edge cases (for example, less than 3 employees in the database or no time reports for the day, etc.) 
4. The program shall output directly to the console with the formatting as in the example 
5. The program shall have a clear configuration section for the database connection 
6. The database tables and columns shall be named as defined in the example 
7. A sample SQL dump of the database for the testing may be provided together with the program but this is optional 
8. The program shall be launchable from the console 

# The Solution #
I used the Symfony to carry out this task because it has a lot of great benefits: Doctrine ORM (Database Migrations,
Entity, Repository, Fixtures) and Symfony allows creating console commands.

### Database Migrations ###
You have to execute the [Database migrations](https://symfony.com/doc/current/bundles/DoctrineMigrationsBundle/).
> php bin/console  doctrine:migrations:migrate

### Doctrine Fixtures ###
You can load a fake set of data into a database for testing:
> php bin/console doctrine:fixtures:load

### Receive the Reports ###

To get the reports of the best employees for each day of the **current** week:
> php bin/console report:top-employees

