<?php

namespace App\Constants;
use App\Traits\ConstantsTrait;

enum UserGenderConstants : int
{
    use ConstantsTrait;

    case MALE = 1;
    case FEMALE = 2;

    public function label():string
    {
        return $this->getLabels()[$this->value];
    }

    public function getLabels():array
    {
        return [
            self::MALE->value => __('male'),
            self::FEMALE->value => __('female')
        ];
    }
}
