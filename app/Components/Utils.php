<?php

namespace App\Components;

use App\Components\Mp3Info\Mp3Info;
use App\Enums\Roles;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class Utils
{
    /**
     * Check if the value is a valid date
     *
     * @param mixed $value
     *
     * @return boolean
     */
    static function isDate($value): bool
    {
        if (!$value) {
            return false;
        }
        try {
            new \DateTime($value);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    static function getValuesFromCollection(Collection $collection) : Collection
    {
        return $collection->filter(fn($e)=>$e)->values();
    }
    static function collectionToString(Collection $collection): string
    {
        return implode(',', self::getValuesFromCollection($collection)->toArray());
    }
    static function stringToCollection($string,$separator): Collection
    {
        return collect(array_map('intval',explode($separator,$string)));
    }

    static function readableFileSize($bytes)
    {
            if ($bytes >= 1073741824)
            {
                $bytes = number_format($bytes / 1073741824, 2) . ' ج/ب';
            }
            elseif ($bytes >= 1048576)
            {
                $bytes = number_format($bytes / 1048576, 2) . ' م/ب';
            }
            elseif ($bytes >= 1024)
            {
                $bytes = number_format($bytes / 1024, 2) . ' ك/ب';
            }
            elseif ($bytes > 1)
            {
                $bytes = $bytes . ' bytes';
            }
            elseif ($bytes == 1)
            {
                $bytes = $bytes . ' byte';
            }
            else
            {
                $bytes = '0 bytes';
            }

            return $bytes;
        }

    static function Mp3Info($filename): array
    {
//        $document = Lecture::query()->where('id', $file_id)->first();
//        $filename = $document->filename;
//        $fileLocalPath = public_path($document->frm_folder) . "/$filename";
//        $filepath = asset($document->frm_folder) . "/$filename";
//        $t = GetId3::fromDiskAndPath('local', $fileLocalPath);
//        $getID3 = new GetId3($filename);

        $duration = 'غير متاح';
        $bitrate = '';

        if(File::exists($filename)) {
            $audio = new Mp3Info($filename);
            $duration = floor($audio->duration / 60) . ' min ' . floor($audio->duration % 60) . ' sec' . PHP_EOL;
            $bitrate = ($audio->bitRate / 1000) . ' kb/s' . PHP_EOL;
        }
        return ['duration'=>$duration,'bitrate'=>$bitrate];
    }

    static function getStorageDirectories($dir)
    {
        return Storage::disk($dir)->allDirectories();

    }

    static function getFileIcon($filename, $size)
    {
        $ext = File::extension($filename);
        $icon ='';
        switch ($ext)
        {
            case 'pdf':
                $icon="<i class='bx bxs-file-pdf $size'></i>";
                break;
            case 'mp3':
                $icon="<i class='bx bxs-user-voice $size'></i>";
                break;
            case 'mp4':
                $icon="<i class='bx bxs-video $size'></i>";
                break;
            case 'jpg':
                $icon="<i class='bx bxs-file-jpg $size'></i>";
                break;
            case 'png':
                $icon="<i class='bx bxs-file-png $size'></i>";
                break;
            case 'doc':
            case 'docx':
                $icon="<i class='bx bxs-file-doc $size' ></i>";
                break;
            case 'xls':
            case 'xlsx':
                $icon="<i class='bx bx-file $size' ></i>";
                break;
            default:
                $icon="<i class='bx bxs-file $size'></i>";
                break;
        }
        return $icon;
    }

    static function arrayToObject($arr){
        return  json_decode(json_encode($arr, true), false);
    }

}
