<?php

require_once 'core/Checker.php';

Launcher::run();

class Launcher
{
    const USER      = 00000000;         // 08 digits
    const PASSWORD  = 'xxxxxxxxxxxxxx'; // 14 characters

    public static $websites = array(
        'inck.fr',
        'free.fr/404',
        'smaltcreation.fr',
        'google.fr/404',
        'smaltcreation.com',
    );

    public static function run()
    {
        try {
            $checker = new Checker(
                new Notifier(
                    self::USER,
                    self::PASSWORD
                )
            );

            $checker->check(self::$websites);
        }

        catch(Exception $e) {
            echo $e->getMessage().PHP_EOL;
        }
    }
}
