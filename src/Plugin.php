<?php
namespace Kwf\Composer\SubProject;

use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;
use Composer\EventDispatcher\EventSubscriberInterface;

class Plugin implements PluginInterface, EventSubscriberInterface
{
    protected $composer;
    protected $io;

    public function activate(Composer $composer, IOInterface $io)
    {
        $this->composer = $composer;
        $this->io = $io;
    }

    public static function getSubscribedEvents()
    {
        return array(
            'post-install-cmd' => 'installOrUpdate',
            'post-update-cmd' => 'installOrUpdate',
        );
    }

    public function installOrUpdate($event)
    {
        $extra = $this->composer->getPackage()->getExtra();
        if (isset($extra['koala-framework-subproject'])) {
            $cwd = getcwd();
            foreach ($extra['koala-framework-subproject'] as $subProjectDir=>$subProject) {

                if (isset($subProject['install'])) {
                    chdir($subProjectDir);
                    $this->io->write("running ".$subProject['install']." in $subProjectDir");
                    passthru($subProject['install'], $retVal);
                    if ($retVal) {
                        throw new \Exception("install failed for $subProjectDir");
                    }
                    chdir($cwd);
                }
            }
        }
    }
}
