<?php

if(!function_exists('errMsg')) {
    function errMsg($m, $error_class = "")
    {
        if(empty($error_class)) {
            return "<div class='invalid-feedback'>{$m}</div>";
        } else {
            return "<div class='{$error_class}'>{$m}</div>";
        }
    }
}

if(!function_exists('errCls')) {
    function errCls()
    {
        return 'is-invalid';
    }
}

if(!function_exists('getUserTextImg')) {
    function getUserTextImg()
    {
        return ucfirst(substr(auth()->user()->name, 0, 1));
    }
}


if(!function_exists('localize')) {
    function localize($date, $format, $timezone) {
        return Carbon\Carbon::parse($date)->timezone($timezone)->format($format);
    }
}

if(!function_exists('getPictureUrl')) {
    function getPictureUrl($picture)
    {
        $profile_picture_url = $picture;
        if(is_null($profile_picture_url) || empty($profile_picture_url)) {
            return "";
        } else {
            if(substr($profile_picture_url, 0, 3) == 'http') {
                // This is from social media
                return $profile_picture_url;
            } else {
                // This is file on our server
                return url('uploads/' . $profile_picture_url);
            }
        }
        return "";
    }
}

if(!function_exists('getTimezone')) {
    function getTimezone() {
        return 'US/Eastern';
        return 'Asia/Kolkata';
        $ip = \request()->ip();
        if($ip == '127.0.0.1') {
            return 'Asia/Kolkata';
        }
        $ip = ($ip == '127.0.0.1') ? '117.247.239.221' : $ip;
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', "https://ipapi.co/{$ip}/json/");
        $response = json_decode($response->getBody());
        if(isset($response->timezone)) {
            return $response->timezone;
        } else {
            return 'Asia/Kolkata';
        }
    }
}


if(!function_exists('isPageActive')) {
    function isPageActive($segment, $segment_number = 1)
    {
        if(is_array($segment)) {
            return in_array(request()->segment($segment_number), $segment) ? "active" : "";
        } else {
            return (request()->segment($segment_number) == $segment) ? "active" : "";
        }
    }
}

if(!function_exists('tooltip')) {
    function tooltip($short, $long)
    {
        $long = str_replace("'", "&#39;", $long);
        $long = str_replace('"', "&#34;", $long);
        return "<span data-toggle='tooltip' title='{$long}'>{$short}</span>";
    }
}

function html_friendly($text) 
{
    $text = str_replace("'", "&#39;", $text);
    $text = str_replace('"', "&#34;", $text);
    return $text;
}

if(!function_exists('getNumbers')) {
    function getNumbers($type = "")
    {
        switch($type) {
            case "register":
                $price = config('price.register.price');
            break;

            default:
                $price = config('price.price');
            break;
        }
        $discount = session()->get('coupon')['discount'] ?? 0;
        $code = session()->get('coupon')['name'] ?? null;
        $newTotal = ($price - $discount);
        if ($newTotal < 0) {
            $newTotal = 0;
        }
        return collect([
            'subTotal' => $price,
            'discount' => $discount,
            'code' => $code,
            'newTotal' => $newTotal,
        ]);
    }
}

if(!function_exists('presentPrice')) {
    function presentPrice($price)
    {
        return '$'.number_format($price / 100, 2);
    }
}



if(!function_exists('time_format')) {
    function time_format($time) 
    {
        $time = explode(":", $time);
        if(is_array($time)) {
            if($time[0] <= 9) {
                $time[0] = "0" . intval($time[0]);
            }
            if($time[1] <= 9) {
                $time[1] = "0" . intval($time[1]);
            }
            return implode(":", $time);
        }
        return $time;
    }
}

if(!function_exists('havePermission')) {
    function havePermission($role = "", $permission_name)
    {
        if(empty($role)) {
            return false;
        }

        if(gettype($role)=="string"){
        $permissions = $role->permissions()->get();


        
        foreach($permissions as $permission) {
            if($permission->name == $permission_name) {
                return true;
            }
        }
    }

    if(gettype($role)=="array"){

        foreach($role as $value){
            $permissions=$value->permission()->get();
            foreach($permissions as $permission) {
                if($permission->name == $permission_name) {
                    return true;
                }
            }

        }

    }







        return false;
    }
}

function can($permissions) 
{
    foreach($permissions as $permission) {
        if(auth(config('auth.guard.admin'))->user()->can($permission)) {
            return true;
        }
    }
    return false;
}

function intakes_html($intakes) 
{
    $intakes = explode(",", $intakes);
    $intakes = array_unique($intakes);
    $html = "";
    foreach($intakes as $intake) {
        if(!is_null($intake)) {
            $html .= "<span class='badge badge-primary mb-50 mr-50'>$intake</span>";
        }
    }
    return !empty($html) ? $html : "<span>N/A</span>";
}