<?php

use App\Models\Todo\TodoItem;
use App\Models\Todo\TodoUserScore;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;

const   ar_months = ["Jan" => "يناير", "Feb" => "فبراير", "Mar" => "مارس", "Apr" => "أبريل", "May" => "مايو", "Jun" => "يونيو", "Jul" => "يوليو", "Aug" => "أغسطس", "Sep" => "سبتمبر", "Oct" => "أكتوبر", "Nov" => "نوفمبر", "Dec" => "ديسمبر"];
const   ar_days = ["Sat" => "السبت", "Sun" => "الأحد", "Mon" => "الإثنين", "Tue" => "الثلاثاء", "Wed" => "الأربعاء", "Thu" => "الخميس", "Fri" => "الجمعة"];
const   ar_am_pm = ['AM' => 'صباحاً', 'PM' => 'مساءً'];

function getDiffDateTimeString($recordDate)
{
    Carbon::setLocale('ar');

    $record_date = Carbon::parse($recordDate);
    $now = Carbon::now();
    $dif = $now->diffInDays($recordDate);

    $record_time = new Carbon(new DateTime($recordDate));
    $time_str = '';

    switch ($dif)
    {
        case 0:
            $time_str = "اليوم : ";//. $post_time->format('h:i a');
            $dif_hr=$now->diffInHours($record_date);
            $dif_min=$now->diffInMinutes($record_date);

            if($dif_hr==0)
            {
                $dif_min=$now->diffInMinutes($record_date);
                if($dif_min<2)
                    $time_str = "منذ دقيقة";
                elseif($dif_min>2 && $dif_min <3)
                    $time_str = "منذ دقيقتين";
                else
                    $time_str = "منذ ".$dif_min." دقيقة";
            }
            elseif ($dif_hr<23)
            {
                if($dif_hr=1)
                    $time_str = "منذ ساعة";
                elseif($dif_hr>2)
                    $time_str = "منذ ساعتين";
                else
                    $time_str = "منذ ".$dif_hr." ساعة";
            }
            else
            {
                $time_str = "اليوم : ". $record_time->format('h:i a');
            }
            break;
        case 1:
            $time_str = "أمس : ". $record_time->format('h:i a');
            break;
        default:
            $time_str = $record_time->translatedFormat('d - M - y').' , '.$record_time->format('h:i a');
            break;
    }

    $time_str = str_replace('am','ص',$time_str);
    $time_str = str_replace('pm','م',$time_str);

    return $time_str;
}

function arabicDate($time)
{
        $months = ["Jan" => "يناير", "Feb" => "فبراير", "Mar" => "مارس", "Apr" => "أبريل", "May" => "مايو", "Jun" => "يونيو", "Jul" => "يوليو", "Aug" => "أغسطس", "Sep" => "سبتمبر", "Oct" => "أكتوبر", "Nov" => "نوفمبر", "Dec" => "ديسمبر"];
        $days = ["Sat" => "السبت", "Sun" => "الأحد", "Mon" => "الإثنين", "Tue" => "الثلاثاء", "Wed" => "الأربعاء", "Thu" => "الخميس", "Fri" => "الجمعة"];
        $am_pm = ['AM' => 'صباحاً', 'PM' => 'مساءً'];

        $day = $days[date('D', $time)];
        $month = $months[date('M', $time)];
        $am_pm = $am_pm[date('A', $time)];
        $date = $day . ' ' . date('d', $time) . ' - ' . $month . ' - ' . date('Y', $time) . '   ' . date('h:i', $time) . ' ' . $am_pm;
        $numbers_ar = ["٠", "١", "٢", "٣", "٤", "٥", "٦", "٧", "٨", "٩"];
        $numbers_en = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];

        return str_replace($numbers_en, $numbers_ar, $date);
}

function arabicDateArray($time)
{
    $months = ["Jan" => "يناير", "Feb" => "فبراير", "Mar" => "مارس", "Apr" => "أبريل", "May" => "مايو", "Jun" => "يونيو", "Jul" => "يوليو", "Aug" => "أغسطس", "Sep" => "سبتمبر", "Oct" => "أكتوبر", "Nov" => "نوفمبر", "Dec" => "ديسمبر"];
    $days = ["Sat" => "السبت", "Sun" => "الأحد", "Mon" => "الإثنين", "Tue" => "الثلاثاء", "Wed" => "الأربعاء", "Thu" => "الخميس", "Fri" => "الجمعة"];
    $am_pm = ['AM' => 'صباحاً', 'PM' => 'مساءً'];

    $day = $days[date('D', $time)];
    $month = $months[date('M', $time)];
    $am_pm = $am_pm[date('A', $time)];
    $date = $day . ' ' . date('d', $time) . ' - ' . $month . ' - ' . date('Y', $time) . '   ' . date('h:i', $time) . ' ' . $am_pm;
    $numbers_ar = ["٠", "١", "٢", "٣", "٤", "٥", "٦", "٧", "٨", "٩"];
    $numbers_en = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];

    $date = str_replace($numbers_en, $numbers_ar, $date);
    return ['day'=>$day,'month'=>$month,'year'=>date('Y', $time),'time'=>date('h:i', $time),'ampm'=>$am_pm];
}

function getUserTodoScore($user_id,$item_id,$date)
{
    $score = \App\Models\Todo\TodoUserScore::query()
        ->where('todo_item_id',$item_id)
        ->where('user_id',$user_id)
        ->whereDate('created_at',$date)
        ->first();
    return $score;
}

function getUserTodoPercentage($user_id=null)
{

    if($user_id==null)
        $user_id = User::id();

    $todo_id = User::todoLevel($user_id)->todo_id;

    $todo_items_count = TodoItem::join('todos_products','todos_items.product_id','todos_products.id')
        ->join('todos','todos.id','todos_products.todo_id')
        ->select(
            DB::raw('COUNT(todos_items.id) AS todo_count'),
        )
        ->where('todos.id',$todo_id)
        ->first()->todo_count;


    $min_date = TodoUserScore::query()
        ->select(DB::raw('MIN(created_at) AS min_date'))
        ->where('user_id',$user_id)
        ->first()->min_date;

    if($min_date)
        $min_date = Carbon::createFromFormat('Y-m-d H:i:s', $min_date);
    else
        $min_date =  Carbon::createFromFormat('Y-m-d H:i:s', now());

    $days = now()->diffInDays($min_date);
    $min_date = $min_date->format('Y-m-d');

    if($days==0) $days=1;

    // get start and end dates of current week
    $date = now();
    $dayOfWeek = $date->dayOfWeek;
    $day = $date->day;

    $firstDayOfWeek = abs($day - $dayOfWeek);
    $lastDayOfWeek = $firstDayOfWeek + 5;

    //return $firstDayOfWeek;

    $min_date = Carbon::createFromFormat('Y-m-d',$date->year.'-'.$date->month.'-'.$firstDayOfWeek);
    $max_date = Carbon::createFromFormat('Y-m-d',$date->year.'-'.$date->month.'-'.$lastDayOfWeek);
    $days = $min_date->diffInDays($max_date) + 1;


    $max_todo_count = $todo_items_count * $days;

    $todo_user_score = TodoItem::join('todos_users_scores','todos_items.id','todos_users_scores.todo_item_id')
        ->join('todos_products','todos_items.product_id','todos_products.id')
        ->join('todos','todos.id','todos_products.todo_id')
        ->select(
            DB::raw('COUNT(todos_users_scores.id) AS user_score_count'),
        )
        ->whereBetween('todos_users_scores.created_at',[$min_date->format('Y-m-d'),$max_date->format('Y-m-d')])
        ->where('user_id',$user_id)
        ->where('todos.id',$todo_id)
        ->first()->user_score_count;

    $percentage = ($todo_user_score / $max_todo_count) * 100 ;

    return $percentage;

}

function getLatestLecturesIndex()
{
    return \App\Models\Lecture::query()->orderBy('lecture_number','asc');
}

function decryptStr($str) : string
{
    return Crypt::decryptString($str);
}
function encryptStr($str) : string
{
    return Crypt::encryptString($str);
}

