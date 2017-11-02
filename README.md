# octomodel-installer

[![CircleCI](https://circleci.com/gh/digitaldeployment/octomodel-installer.svg?style=svg)](https://circleci.com/gh/digitaldeployment/octomodel-installer)

This is a Composer plugin that, when added as a dependency, causes Drupal package dependencies to be installed into a typical Drupal directory structure in `web/` instead of a flat structure in `vendor/`.

It's similar to using [`composer/installers`](https://github.com/composer/installers) with [`composer-installers-extender`](https://github.com/oomphinc/composer-installers-extender) like [`Acquia Lightning`](https://github.com/acquia/lightning-project/blob/8.2.x/composer.json), except you can pull it in as a single dependency instead of setting up a bunch of stuff in `composer.json` for every project.
