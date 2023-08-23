<?php

use App\Components\Mp3Info\Mp3Info;

$data = [
    Mp3Info::MPEG_1 => [
		1 => [null, 32000, 64000, 96000, 128000, 160000, 192000, 224000, 256000, 288000, 320000, 352000, 384000, 416000, 448000, false], // MPEG 1 layer 1
		2 => [null, 32000, 48000, 56000, 64000, 80000, 96000, 112000, 128000, 160000, 192000, 224000, 256000, 320000, 384000, false],  // MPEG 1 layer 2
		3 => [null, 32000, 40000, 48000, 56000, 64000, 80000, 96000, 112000, 128000, 160000, 192000, 224000, 256000, 320000, false],  // MPEG 1 layer 3
    ],
	Mp3Info::MPEG_2 => [
		1 => [null, 32000, 48000, 56000, 64000, 80000, 96000, 112000, 128000, 144000, 160000, 176000, 192000, 224000, 256000, false],  // MPEG 2 layer 1
		2 => [null, 8000, 16000, 24000, 32000, 40000, 48000, 56000, 64000, 80000, 96000, 112000, 128000, 144000, 160000, false],  // MPEG 2 layer 2
		3 => [null, 8000, 16000, 24000, 32000, 40000, 48000, 56000, 64000, 80000, 96000, 112000, 128000, 144000, 160000, false],  // MPEG 2 layer 3
    ],
];

$data[Mp3Info::MPEG_25] = $data[Mp3Info::MPEG_2];
return $data;
