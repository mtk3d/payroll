# Payroll

Small application built for manage employments, and generate salary reports.

## Assumptions

- Application is built using as many patterns and good practices as possible in a finite time
- Report generating process is ready to run it asynchronously
- Framework installation deferred as long as possible, use framework-agnostic approach
- App has a basic C4 model and EventStorming session output
- Employee must be attached to Department
- Report read model must be as simple as possible, like simple single database table
- App frontend must be simple as possible using basic tools, like CSS and JS framework from cdn

## Setup local environment

```shell
make up
```

## Run tests

To run functional tests, You have to run app fixtures first

```shell
php bin/console doctrine:fixtures:load
```

and then go inside docker shell

```shell
make shell
```

and then

```shell
make test
```

or you can run only specific type tests

```shell
make test-all
make test-unit
make test-integration
make test-functional
```

## Architecture

- [Event Storming](./docs/EventStorming.md)
- [C4 model](./docs/C4-model.md)
