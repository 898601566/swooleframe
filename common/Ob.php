<?php

namespace common;

use fastswoole\InstanceTrait;

class  Ob
{
    use InstanceTrait;
    public function run()
    {
        echo "<br>";
        ob_start();
        echo 111;
        $content = ob_get_clean();
        the_print($content);

        echo "<br>";
        ob_start();
        echo 222;
        ob_flush();
        $content = ob_get_clean();
        the_print($content);

        echo "<br>";
        ob_start();
        echo 333;
        $content = ob_get_flush();
        ob_end_flush();
        the_print($content);

        echo "<br>";
        ob_start();
        echo 444;
        $content = ob_get_flush();
        ob_start();
        echo 555;
        ob_clean();
        ob_end_flush();
        the_print($content);
    }

}
