<?php

namespace DD\Composer;

use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;

class OctoModelInstallerPlugin implements PluginInterface {

  /**
   * {@inheritDoc}
   */
  public function activate(Composer $composer, IOInterface $io) {
    $installer = new OctoModelInstaller($io, $composer);
    $composer->getInstallationManager()->addInstaller($installer);
  }

}
