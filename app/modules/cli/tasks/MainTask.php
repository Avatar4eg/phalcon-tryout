<?php
namespace PhalconTryout\Modules\Cli\Tasks;

class MainTask extends \Phalcon\Cli\Task
{
    public function mainAction()
    {
        echo 'Congratulations! You are now flying with Phalcon CLI!';
    }
}
