<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class PlayListType extends Enum
{
    const Media =   'media'; // for mp3, mp4, jpg, png
    const EBook =   'ebook'; // for pdf, doc, docx, xls, xlsx, jpg, png
}
