
#### Documentation index

* [Documentation](../README.md)
* [Routing](routing.md)
* [General technical informations](technical.md)
* [API / webservices](api.md)
* [Esteren Maps library](maps.md)

# General technical informations

## Views

Contrary to [Symfony's recommendations and best practices](http://symfony.com/doc/current/best_practices/templates.html#template-locations),
we need the bundles to be the most standalone possible, so the views are kept inside each bundle.
Only the base template and the EasyAdmin ([view below](#backoffice)) views are stored in `app/Resources/views`.

## CMS

With [OrbitaleCmsBundle](https://github.com/Orbitale/CmsBundle), a very simple CMS is handled for every configured
 subdomains. Each can have its own CMS as long as every `Page` object is configured with the `host` property.

## Backoffice

The backoffice is powered by [EasyAdminBundle](https://github.com/javiereguiluz/EasyAdminBundle).
Its configuration resides in [app/config/_easyadmin.yml](../app/config/_easyadmin.yml).
An `AdminBundle` exists only to store the `AdminController` which allows complete override of any of EasyAdmin's feature.

[IvoryCKEditorBundle](https://github.com/egeloen/IvoryCKEditorBundle) is installed and configured in the `Page` entity
 to use a WYSIWYG.

## Maximal configuration evolutivity (a bit exaggerating, though)

You may notice that the classic `app/config/config.yml` is left **unchanged** compared to Symfony standard edition.
All application-related config is written in [app/config/_app.yml](../app/config/_app.yml).

Why?

Because there is nothing in the Standard edition that facilitates new versions upgrade, so all that's possible to upgrade
 the Standard Edition is a dirty copy/paste. Having files unchanged makes the diffs easier when upgrading.

Be careful that `config_dev.yml` and `config_prod.yml` files **must** import `_app.yml` instead of `config.yml`.
This way, the base config is left unchanged, and `config.yml` is included **after** `_app.yml`.

Some pretty spaces are also added in `AppKernel.php` and `composer.json` to separate Symfony's default configuration to
 the application specific config.

## Menus

Menus are managed with [KnpMenuBundle](https://github.com/KnpLabs/KnpMenuBundle).
A single menu is present in the top-menu and is configured in [GeneralMenu.php](../src/Esteren/PortalBundle/Menu/GeneralMenu.php)

## Tests

Unfortunately, it's a bit hard to keep tests standalone, so they're all located in `tests/`.
A directory is created for each kind of tests.
For now, there's only PHPUnit, but maybe one day there'll be behat or phpspec tests to be runned, this is why there is
 another directory level to allow this more easily.

## Users

Users are managed with [FOSUserBundle](https://github.com/FriendsOfSymfony/FOSUserBundle).
A simple `UsersBundle` exists to contain some fixtures and the `User` entity to be used in the whole application.
It's important to note that this bundle **extends FOSUserBundle**, because in the future we might need to tweak/override
 the behavior of some FormTypes or controllers (because there's a project of merging users from different platforms).