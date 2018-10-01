
[![Build Status](https://travis-ci.com/Pierstoval/AgateApps.svg?branch=master)](https://travis-ci.com/Pierstoval/AgateApps)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/c9de37c6-0876-464f-899c-90a07e8ea361/mini.png)](https://insight.sensiolabs.com/projects/c9de37c6-0876-464f-899c-90a07e8ea361)

Esteren full portal
========================

This project is a full-stack Symfony application.

It was created in September 2013 with Symfony 2.3, but the different concepts and base code were started in March 2013.

Since, it has followed all Symfony updates, and has been refactored countless times.

It contains multiple apps:

* The Esteren portal.
* The Agate portal.
* The Dragons portal.
* The Vermine 2047 portal.
* The Games portal, which is still WIP at that time.
* **Esteren Maps**, an application that allows users to navigate in the different configured maps, calculate directions and
 imagine scenarios based on travels.
* **Corahn-Rin**, this is the V2 of the first [Corahn-Rin project](https://github.com/Esteren/CorahnRinV1). The goal of this
 application is to provide a character generator, a manager (to help the character grow up in skills!), and a virtual
 campaign board, where game leaders can invite players and reward characters in XP and treasures.

## Documentation index

### Readme:

* [Pre-requisites](#pre-requisites)
* [Contributing](#contributing)
* [Install](#install)
  * [Subdomains](#subdomains)
    * [With a DNS software](#with-a-dns-software)
    * [With hosts file](#with-hosts-file)
  * [Change the port to use another than `8080`](#change-the-port-to-use-another-than-8080)
  * [Assets management](#assets-management)
* [Tests](#tests)
  * [CI](#ci)
* [Issues tracking](#issues-tracking)
* [License](#license)

### Other documentation

* [Routing](docs/routing.md)
* [General technical informations](docs/technical.md)
* [Set up a vhost](docs/vhosts.md)
* [API / webservices](docs/api.md)
* [Esteren Maps library](docs/maps.md)
* [Corahn Rin / Character manager](docs/character_manager.md)
* [Deploy](docs/deploy.md)

## Contributing

If you would like to contribute to this project, there are some rules:

* Read all the documentation about the things you would like to work on.
* Be kind, it's an old project managed by one single person, so there **is** legacy. Quite normal.
* Share! If you have any idea, feel free to open an issue and we can chat about the choices, ideas, features and maybe
your personal contributions 😉.

## Pre-requisites

Simply use **Docker**. If you want to use something else, you can reverse-engineer necessary dockerfiles or vhosts.

## Install

```bash
$ cp .env.dist .env
$ make install
```

Check the [Makefile](./Makefile#L22) if you want to know what this `install` target does.

**TL;DR:** the command builds the Docker images, starts them, installs the vendors, sets up the database, inserts
fixtures, dumps public assets and EsterenMap map tiles.

ℹ️ **Note:** On Windows, most Make implementations are not working. To make it work with the common `cmd` or with tools
like [Cmder](http://cmder.net), I am using the `make.exe` (and lots of other executables) from the Ruby and its Devkit
that are available on the [Ruby Downloads page](https://rubyinstaller.org/downloads/). You can use only the devkit and
remove Ruby from the path if you're not using it. 

### Subdomains

There are multiple subdomains to configure in the project to make it work: API, backend, portals, etc.

You need to be sure that your environment is capable of using the hosts that are configured in the project.

By convention, all domains might end with `.docker`, but it depends on your preferences.

Check the default domains at [.env.dist file](/.env.dist), and update your `.env` file accordingly.

#### With a DNS software

On Linux and MacOS, you can use `dnsmasq` (if you know how to configure it properly) to redirect all `.docker` hosts
to your local host, with a configuration like this:

```ini
address=/docker/127.0.0.1
```

On Windows you can use [Acrylic](http://mayakron.altervista.org/wikibase/show.php?id=AcrylicHome) (if you know how to
configure it properly), or you can use hosts files which is a much simpler way to do so (check next section).

#### With hosts file

On Windows (and on Linux and MacOS too) you can update your machine's hosts file to make your browser able to load your
custom domain names for Agate project.

Example of host files you can copy-paste if you like:

```ini
127.0.0.1   www.studio-agate.docker
127.0.0.1    www.vermine2047.docker
127.0.0.1        www.esteren.docker
127.0.0.1        api.esteren.docker
127.0.0.1       maps.esteren.docker
127.0.0.1      games.esteren.docker
127.0.0.1     portal.esteren.docker
127.0.0.1  corahnrin.esteren.docker
127.0.0.1       back.esteren.docker
# ...
```

On Windows, the hosts file is located in `C:\Windows\System32\drivers\etc\hosts` and must be edited with administrator
permissions.

On Linux and MacOS, the host file is in `/etc/hosts` and must be edited with root permissions.

### Change the port to use another than `8080`

To change the port, you can create a `docker-compose.override.yml` file in the project's root directory and put this in
it :

```yaml
version: '3'

services:
    nginx:
        ports:
            - '8080:80'
```

Here the example is written with `8080` but you can change it to any port if you need to.

### Assets management

Most of the CSS comes from LESS files for Bootstrap, SASS for materialize, and all JS/CSS files are compiled
with Gulp.

We use a specific gulpfile from [Orbitale/Gulpfile](https://github.com/Orbitale/Gulpfile) which allows good
flexibility and is based only on one config variable (similar to Grunt).

## Tests

To run the tests, just run phpunit:

```bash
$ make phpunit
```

### CI

We are using [CircleCI](https://circleci.com/gh/Pierstoval/AgateApps).

Linting tests and phpunit are executed there for both PHP and NodeJS.

You can check CircleCI's config file at [.circleci/config.yml](.circleci/config.yml).

## Issues tracking

Any issue on Github will have to be transformed into a Redmine ticket for internals, but you can just take care of your
Github issues.

## License

View the joined [license file](LICENSE) to know about uses rights.

The important part of the license is: This is a proprietary project, all rights are reserved and its source is opened
mostly for transparency reasons.

Using or deploying this app for other uses than development is not allowed. 
