<?php

define("ROOT",realpath(__DIR__.DIRECTORY_SEPARATOR.'..'));
define("CONTROLLER",realpath(ROOT.DIRECTORY_SEPARATOR.'src'.DIRECTORY_SEPARATOR.'Controller'));
define("TEMPLATES",realpath(ROOT.DIRECTORY_SEPARATOR.'templates'));
define("MYSQL_FILE_PATH",realpath(ROOT.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'mysql.ini'));

require ROOT . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php'; //implode(DIRECTORY_SEPARATOR,'vendor','autoload.php');