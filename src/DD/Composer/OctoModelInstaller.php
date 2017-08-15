<?php

namespace DD\Composer;

use Composer\Package\PackageInterface;
use Composer\Installer\LibraryInstaller;

class OctoModelInstaller extends LibraryInstaller {

  private $pathsByType = array(
    'drupal-core' => 'www/core',
    'drupal-library' => 'www/libraries/{$name}',
    'drupal-module' => 'www/modules/contrib/{$name}',
    'drupal-custom-module' => 'www/modules/custom/{$name}',
    'drupal-profile' => 'www/profiles/{$name}',
    'drupal-theme' => 'www/themes/{$name}',
    'drupal-custom-theme' => 'www/themes/custom/{$name}',
    'drupal-drush' => 'drush/contrib/{$name}',
  );

  /**
   * {@inheritDoc}
   */
  public function getInstallPath(PackageInterface $package) {
    $packageType = $package->getType();
    return $this->pathsByType[$packageType];
  }

  /**
   * {@inheritDoc}
   */
  public function supports($packageType) {
    return isset($this->pathsByType[$packageType]);
  }

}
