<?php
namespace Kwf\Composer\SubProject;

use Kwf_Util_Build_Types_Abstract;

class BuildStep extends Kwf_Util_Build_Types_Abstract
{
    protected function _build($options)
    {
        $composerJson = json_decode(file_get_contents('composer.json'), true);
        $extra = $composerJson['extra'];

        $cwd = getcwd();
        foreach ($extra['koala-framework-subproject'] as $subProjectDir=>$subProject) {

            $environment = isset($options['environment']) ? $options['environment'] : 'prod';
            $cmd = null;
            if (isset($subProject['build'])) {
                $cmd = $subProject['build'];
            } else if (isset($subProject['build:' . $environment])) {
                $cmd = $subProject['build:' . $environment];
            }
            if ($cmd) {
                chdir($subProjectDir);
                echo "running $cmd in $subProjectDir\n";
                passthru($cmd, $retVal);
                if ($retVal) {
                    throw new \Exception("build failed for $subProjectDir");
                }
                chdir($cwd);
            }
        }
    }

    public function getTypeName()
    {
        return 'subprojects';
    }
}
