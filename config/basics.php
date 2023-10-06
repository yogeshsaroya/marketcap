<?php
/* print array and string */
function ec($str = null, $txt = null)
{
    if (!is_null($str)) {
        if (is_array($str) || is_object($str)) {
            echo $txt . " ";
            if (is_array($str)) {
                echo count($str);
            }
            echo "<pre>";
            print_r($str);
            echo "</pre>";
        } else {
            echo "$txt <br>$str<br>";
        }
    } else {
        echo "$txt <br>Empty/Null<br>";
    }
}

function num_2($num, $limit = 2)
{
    return number_format((float) $num, $limit, '.', '');
}

function cu($num, $limit = 2)
{
    return number_format((float) $num, $limit);
}

function num($num = null)
{
    if ($num == 0) {
        return 0;
    } else {
        return (int) $num;
    }
}

function show_image($full_path = null, $w = 100, $h = 100, $dimensions = 'ff', $clean_url = 1, $formate = 'jpg')
{
    $img_url = null;
    if (!empty($full_path) && file_exists($full_path)) {
        if ($clean_url == 1) {
            $img_url = SITEURL . "images/" . $w . "_" . $h . "_" . $dimensions . "_" . $formate . "/" . $full_path;
        } else {
            $img_url = SITEURL . "imgd.php?w=$w&h=$h&no-cache&skip-original&$dimensions=ffffff7f&sa=$formate&src=$full_path&ver=" . rand(123456, 987654);
        }
    }
    if (empty($img_url)) {
        if ($clean_url == 1) {
            $img_url = SITEURL . "images/" . $w . "_" . $h . "_ff_png/img/placeholder.png";
        } else {
            $img_url = SITEURL . "imgd.php?w=$w&h=$h&no-cache&skip-original&$dimensions=ffffff7f&sa=png&src=img/placeholder.png&ver=" . rand(123456, 987654);
        }
    }
    return $img_url;
}

/**
 * check give string is date or not
 *
 * @call validateDate($str,$format = 'Y-m-d H:i:s');
 *
 */
function validateDate($date, $format = 'Y-m-d H:i:s')
{
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) == $date;
}

function uc_text($str = null)
{
    return ucwords(strtolower(trim($str)));
}

function set_version($t = null)
{
    if ($t == 'c') {
        return '.css?v=' . rand(111, 999999);
    } elseif ($t == 'j') {
        return '.js?v=' . rand(111, 999999);
    }
}

function set_tooltip($pos = 'top', $title = null)
{
    return '<i class="fa fa-question-circle" data-animation="false" data-toggle="tooltip" data-html="true" data-placement="' . $pos . '" title="' . $title . '"></i>';
}

function paymentsType()
{
    return ['1' => 'Full', '2' => 'Partial'];
}

function callApi($url)
{
    if (!empty($url)) {
        try {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $json = curl_exec($ch);
            curl_close($ch);
            return json_decode($json, true);
        } catch (\Throwable $th) {
        }
    }
}

function search($array, $key, $value)
{
    $results = array();

    if (is_array($array)) {
        if (isset($array[$key]) && $array[$key] == $value) {
            $results[] = $array;
        }

        foreach ($array as $subarray) {
            $results = array_merge($results, search($subarray, $key, $value));
        }
    }

    return $results;
}

function nice_number($n) {
    if( !empty($n) ){
        $n = (0+str_replace(",", "", $n));
        if (!is_numeric($n)) return false;
        if ($n > 1000000000000) return round(($n/1000000000000), 2).' T';
        elseif ($n > 1000000000) return round(($n/1000000000), 2).' B';
        elseif ($n > 1000000) return round(($n/1000000), 2).' M';
        elseif ($n > 1000) return round(($n/1000), 2).' K';
        return number_format($n);
    }
    
}