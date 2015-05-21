<?php
namespace App\Silex;

use Valitron\Validator as V;

abstract class Validator extends \Silexcane\Silex\Validator
{
    public function __construct($data, $lang = 'ja')
    {
        V::addRule('katakana', function ($field, $value, array $params) {
            if (preg_match('/^[ァ-ヶー]+$/u', $value)) {
                return true;
            }
            return false;
        }, 'はカタカナで入力してください');
        parent::__construct($data, $lang);
    }
}
