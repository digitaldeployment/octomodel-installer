<?php

namespace DD\Test\Composer;

use DD\Composer\OctoModelInstaller;
use Composer\TestCase;
use Composer\Util\Filesystem;
use Composer\Composer;
use Composer\Config;

/**
 * @coversDefaultClass \DD\Composer\OctoModelInstaller
 * @see \Composer\Installer\LibraryInstaller\LibraryInstallerTest
 */
class OctoModelInstallerTest extends TestCase {

  protected $fs;
  protected $originalDir;
  protected $rootDir;
  protected $composer;
  protected $config;
  protected $dm;
  protected $repository;
  protected $io;

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    $this->fs = new Filesystem();

    $this->originalDir = getcwd();
    $this->rootDir = $this->getUniqueTmpDirectory();
    chdir($this->rootDir);

    $this->composer = new Composer();
    $this->config = new Config(TRUE, $this->rootDir);
    $this->composer->setConfig($this->config);

    $this->dm = $this->getMockBuilder('Composer\Downloader\DownloadManager')
      ->disableOriginalConstructor()
      ->getMock();
    $this->composer->setDownloadManager($this->dm);

    $this->repository = $this->getMockBuilder('Composer\Repository\InstalledRepositoryInterface')
      ->getMock();
    $this->io = $this->getMockBuilder('Composer\IO\IOInterface')
      ->getMock();
  }

  /**
   * {@inheritdoc}
   */
  protected function tearDown() {
    chdir($this->originalDir);
    $this->fs->removeDirectory($this->rootDir);
  }

  /**
   * Tests installation of Drupal packages.
   */
  public function testInstall() {
    $installer = new OctoModelInstaller($this->io, $this->composer);

    // Assert the installer directories haven't been created yet at this point.
    $this->assertFileNotExists($this->rootDir . '/vendor');
    $this->assertFileNotExists($this->rootDir . '/www/modules/contrib');

    $package = $this->getMockBuilder('Composer\Package\Package')
      ->setConstructorArgs(array(md5(mt_rand()), '1.0.0.0', '1.0.0'))
      ->getMock();
    $package
      ->expects($this->any())
      ->method('getPrettyName')
      ->will($this->returnValue('drupal/token'));
    $package
      ->expects($this->any())
      ->method('getType')
      ->will($this->returnValue('drupal-module'));

    // Expect the token contrib module to be installed in the right spot.
    $this->dm
      ->expects($this->once())
      ->method('download')
      ->with($package, 'www/modules/contrib/token');

    $installer->install($this->repository, $package);

    // Assert all installer directories have been created at this point,
    // including for package types we didn't install.
    $this->assertFileExists($this->rootDir . '/vendor');
    $this->assertFileExists($this->rootDir . '/www/modules/contrib');
    $this->assertFileExists($this->rootDir . '/www/themes/contrib');
  }

}
