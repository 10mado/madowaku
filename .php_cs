<?php
/**
 * PHP-CS-Fixer settings
 * https://github.com/FriendsOfPHP/PHP-CS-Fixer
 */

$finder = Symfony\CS\Finder\DefaultFinder::create()
    ->in('src/App')
;

return Symfony\CS\Config\Config::create()
    ->level(Symfony\CS\FixerInterface::PSR2_LEVEL)
    ->finder($finder)
;
