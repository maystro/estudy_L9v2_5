<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class Roles extends Enum
{
    const Admin = 'admin';
    const Author = 'author';
    const Editor = 'editor';
    const Maintainer = 'maintainer';
    const Teacher = 'teacher';
    const Student = 'student';
    const Subscriber = 'subscriber';
}
