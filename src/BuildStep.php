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

            if (isset($subProject['build'])) {
                chdir($subProjectDir);
                echo "running ".$subProject['build']." in $subProjectDir\n";
                passthru($subProject['build'], $retVal);
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
