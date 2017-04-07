<?php
namespace PhalconTryout\Modules\Cli\Tasks;

/**
 * @property \Phalcon\Config config
 */
class VersionTask extends \Phalcon\Cli\Task
{
    public function mainAction()
    {
        echo $this->config->get('version');
    }
}
