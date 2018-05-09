# Ottivo Code Challenge

*This is example of [zend-expressive](https://github.com/zendframework/zend-expressive)  application*

This CLI application provides ability to calculate vacation days in given year according to next rules.

Each employee has a minimum of 26 vacation days regardless of age
- Employee >= 30 years do get one additional vacation day every 5 years
- Employees can have a special contract that overwrites the amount of vacation days
- Employees starting their work at Ottivo in the course of the year get one-twelfth of the their yearly vacation days starting from the next month's 1st
- Employees can start at the 1st or the 15th of a month

Configuration settings may be changed in /config folder

## Getting Started

Checkout the repository and just run:

```bash
$ composer install
```

That's all.

## Run Forest, run!

```bash
$ composer vacation-calculator
```

or 

```bash
$ php bin/vacation-calculator.php
```

> ### Something went wrong? 
>
> Feel free to write me if you have issues with running CLI command or 
> wrong calculations has been received or rule(-s) is not correctly implemented.

**Note:** SQLite uses as dummy database. It located in /data dir and of course committed in Git just for adding
ability to quickly test the solution. There is no migrations provided, sorry.

## Contributing

Create branch + create the pull request. There is delay may be expected on overview. Thanks for understanding.

## Tests

```bash
$ composer test
```

For additional info see phpunit configuration file
