<?php

namespace DD\Composer;

use Composer\Package\PackageInterface;
use Composer\Installer\LibraryInstaller;

/**
 * Composer installer for the Drupal 8 Standard Model.
 *
 * Adapted from the installer paths in Acquia's Lighting:
 * https://github.com/acquia/lightning-project/blob/8.2.x/composer.json.
 */
class OctoModelInstaller extends LibraryInstaller {

  private $pathsByType = array(
    'drupal-core' => 'www/core',
    'drupal-library' => 'www/libraries/{$name}',
    'drupal-module' => 'www/modules/contrib/{$name}',
    'drupal-custom-module' => 'www/modules/custom/{$name}',
    'drupal-profile' => 'www/profiles/{$name}',
    'drupal-theme' => 'www/themes/contrib/{$name}',
    'drupal-custom-theme' => 'www/themes/custom/{$name}',
    'drupal-drush' => 'drush/contrib/{$name}',
  );

  /**
   * {@inheritDoc}
   */
  protected function initializeVendorDir() {
    // The vendor directory might still be used by packages supported by this
    // installer, such as for autoload.php.
    parent::initializeVendorDir();

    // Proactively create all the directories where packages might get
    // installed. Drupal actually relies on some of these directories existing,
    // such as its testing bootstrap script.
    foreach ($this->pathsByType as $path) {
      $dir = dirname($path);
      // Make sure we don't accidentally create directories with placeholders.
      assert(strpos($dir, '$') === FALSE);
      $this->filesystem->ensureDirectoryExists($dir);
    }
  }

  /**
   * {@inheritDoc}
   */
  public function getInstallPath(PackageInterface $package) {
    $this->initializeVendorDir();

    $type = $package->getType();
    $name = $this->basenameForPackage($package);
    return str_replace('{$name}', $name, $this->pathsByType[$type]);
  }

  /**
   * {@inheritDoc}
   */
  public function supports($packageType) {
    return isset($this->pathsByType[$packageType]);
  }

  /**
   * Determine the basename of the given package.
   *
   * For example, for drupal/token, returns 'token'.
   *
   * @param Composer\Package\PackageInterface $package
   *   The package.
   *
   * @return string
   *   The package basename.
   */
  private function basenameForPackage(PackageInterface $package) {
    $prettyName = $package->getPrettyName();
    if (strpos($prettyName, '/') !== FALSE) {
      list(, $basename) = explode('/', $prettyName);
    }
    else {
      $basename = $prettyName;
    }

    return $basename;
  }

}
