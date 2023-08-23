<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class Permissions extends Enum
{
    const Read = 'read';
    const Create = 'create';
    const Update = 'update';
    const Delete = 'delete';
}
