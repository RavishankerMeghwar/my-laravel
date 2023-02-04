<?php

use Illuminate\Support\Str;
use Twilio\Rest\Client;
use Intervention\Image\ImageServiceProvider;
use App\Models\User;
use Illuminate\Validation\ValidationException;
use PhpParser\Node\Expr\Cast\Object_;


if (!function_exists('api_success')) {
    function api_success($message, $data, $response = 200)
    {
        $data = response()->json(['status' => true, 'response' => array('message' => $message, 'detail' => $data)], $response);
        return $data;
    }
}

if (!function_exists('api_success1')) {
    function api_success1($message)
    {
        $data = response()->json(['status' => true, 'response' => $message]);
        return $data;
    }
}
if (!function_exists('json_response')) {
    function json_response($data)
    {
        $data = response()->json(['status' => true, 'response' => $data]);
        return $data;
    }
}

if (!function_exists('api_error')) {
    function api_error($message = 'There is some error!', $error_code = 200)
    {
        $data = response()->json(['status' => false, 'error' => array('message' => $message)], $error_code);
        return $data;
    }
}
if (!function_exists('apiResponse')) {
    function apiResponse($data, $message = '', $status = 'success', $HttpStatus = 200, $headers = [], $options = 0)
    {
        return response()->json([
            'status' => $status,
            'message' => strip_tags($message),
            'data' => $data,
        ], $HttpStatus, $headers, $options);
    }
}

if (!function_exists('api_error_array')) {
    function api_error_array($errors = array(), $error_code = 400)
    {
        $data = response()->json(['status' => false, 'error' => $errors], $error_code);
        return $data;
    }
}
if (!function_exists('is_admin')) {
    function is_admin()
    {
        return request()->user->role == User::ROLE_SUPER_ADMIN;
    }
}
if (!function_exists('is_manager')) {
    function is_manager()
    {
        return request()->user->role == User::ROLE_MANAGER;
    }
}
if (!function_exists('is_employee')) {
    function is_employee()
    {
        return request()->user->role == User::ROLE_EMPLOYEE;
    }
}

if (!function_exists('api_validation_error')) {
    function api_validation_error($message, $data)
    {
        $data = response()->json(['status' => false, 'error' => array('message' => $message, 'detail' => $data)]);
        return $data;
    }
}

if (!function_exists('custom_public_url')) {
    function custom_public_url($string)
    {
        echo url('public/' . $string);
    }
}

if (!function_exists('prefix')) {
    function prefix()
    {
        if (\Request::route()->getPrefix() == 'api/')
            return true;
        return false;
    }
}

if (!function_exists('api_validation_error')) {
    function api_validation_error($message, $data)
    {
        $data = response()->json(['status' => false, 'error' => array('message' => $message, 'detail' => $data)]);
        return $data;
    }
}

if (!function_exists('getTokenWeb')) {
    function getTokenWeb()
    {
        $token  = Session::get('usertoken');
        if ($token) {
            return $token;
        }
        return false;
    }
}

if (!function_exists('getToken')) {
    function getToken($request)
    {
        if (preg_match('/Bearer\s(\S+)/', $request->header('Authorization'), $matches)) {
            return $matches[1];
        }
        return false;
    }
}

if (!function_exists('addFile')) {
    function addFile($file, $path, $width = '1000', $height = '1000', $resize = false)
    {
        $destinationPath = $path;
        $file = $file;
        $name =  rand(99, 9999999) . '.' . $file->extension();

        if ($file->move($destinationPath, $name)) {
            return $name;
        }
    }
}

if (!function_exists('generate_token')) {
    function generate_token($customer)
    {
        $token_params = [
            time(),
            $customer->email,
            "access_token",
        ];
        return base64_encode(md5(\implode("_", $token_params)));
    }
}

if (!function_exists('notification_core')) {
    function notification_core($data)
    {
        $SERVER_API_KEY = 'AAAAIeF_LdY:APA91bEHveylErcbcuRyGE9U2MwNH4WEwA-EqWj3F_OYUpiP1hQ0Ju84JjzoxxZyYr58MPiv4xa_Kf1X8jlZk1Eqx0-zEvIzQ_vAUwC9mCZxfKMAtxdmpt2B8qCDMzpWFIoe_ENssHn_';

        $dataString = json_encode($data);
        $headers = [
            'Authorization: key=' . $SERVER_API_KEY,
            'Content-Type: application/json'
        ];
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
        $response = curl_exec($ch);
        curl_close($ch);
        return response()->json(['response' => $response], 200);
    }
}
// $lsg, $ibm, $type
if (!function_exists('calculate_funding_amount')) {
    function calculate_funding_amount($data)
    {
        $url = 'https://services.pronovo.ch:8443/engine-rest/engine/default/process-definition/key/Tarifrechner_PV/submit-form';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $headers = [
            'Content-Type: application/json'
        ];

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $array = array(
            'lsg' => array(
                'value' => $data->lsg,
                'type' => 'double'
            ),
            'ibm' => array(
                'value' => $data->ibm,
                'type' => 'integer'
            ),
            'kat' => array(
                'value' => $data->kat,
                'type' => 'integer'
            ),
            'grb' => array(
                'value' => $data->grb,
                'type' => 'boolean'
            ),
            'nw' => array(     //Neigungswinkel ≥ 75 Grad
                'value' => $data->nw,
                'type' => 'boolean'
            ),
            'hb' => array(     //Höhenbonus ab 1500m
                'value' => $data->hb,
                'type' => 'boolean'
            ),
            'ev' => array(     //kein Eigenverbrauch	
                'value' => $data->ev,
                'type' => 'boolean'
            )
        );
        $val_arr = json_encode($array);
        $data = array(
            'variables' => ['engiens' =>  ['value' => "[" . $val_arr . "]", 'type' => 'string']]
        );
        $cc = json_encode($data);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $cc);
        $result = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($http_code == 200) {
            return json_decode($result);
        }
        return response('error in calculation', 500);
    }
}

if (!function_exists('get_result')) {
    function get_result($pronovo_id)
    {
        // return $pronovo_id;
        $url = "https://services.pronovo.ch:8443/engine-rest/engine/default/process-instance/" . $pronovo_id . "/variables/json_result";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPGET, 1);

        $result = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($http_code == 200) {
            $result = json_decode($result);
            return json_decode($result->value);
        }
        return response('error in calculation', 500);
    }
}

if (!function_exists('csvToArray')) {
    function csvToArray($filename = '', $delimiter = ',')
    {
        if (!file_exists($filename) || !is_readable($filename)) return [];
        $header = null;
        $data = array();
        $counter = 0;
        if (($handle = fopen($filename, 'r')) !== false) {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== false) {
                $counter++;

                if (!$header) {
                    if ($counter == 2) {
                        return $header = $row;
                    }
                    $header = $row;
                } else {
                    $data[] = array_combine($header, $row);
                }
            }
            fclose($handle);
        }
        return $data;
    }
}

if (!function_exists('nicetime')) {
    function nicetime($date)
    {
        if (empty($date)) {
            return "No date provided";
        }

        $periods         = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
        $lengths         = array("60", "60", "24", "7", "4.35", "12", "10");

        $now             = time();
        $unix_date       = strtotime($date);

        // check validity of date
        if (empty($unix_date)) {
            return "Bad date";
        }

        // is it future date or past date
        if ($now > $unix_date) {
            $difference     = $now - $unix_date;
            $tense         = "ago";
        } else {
            $difference     = $unix_date - $now;
            $tense         = "from now";
        }

        for ($j = 0; $difference >= $lengths[$j] && $j < count($lengths) - 1; $j++) {
            $difference /= $lengths[$j];
        }

        $difference = round($difference);

        if ($difference != 1) {
            $periods[$j] .= "s";
        }

        return "$difference $periods[$j] {$tense}";
    }
}

if (!function_exists('generate_unique_username')) {
    function generate_unique_username($name)
    {
        $new_username = strtolower(str_replace(' ', '_', $name));
        (int) $count = \App\Models\User::where('user_name', 'LIKE', '%' . $new_username . '%')->count();
        if (empty($count)) $new_username = $new_username . '_1';
        else $new_username = $new_username . '_' . ++$count;
        return $new_username;
    }
}

if (!function_exists('messages')) {
    function messages($errors)
    {
        if (session()->has('req_error')) { ?>
            <div class="alert custom-alert alert-danger d-flex align-items-center" role="alert">
                <ul>
                    <li><i class="fas fa-exclamation-triangle"></i>&nbsp;<?php echo session()->get('req_error') ?></li>
                </ul>
            </div>
        <?php session()->forget('req_error');
        } else if (session()->has('req_success')) { ?>
            <div class="alert custom-alert alert-success d-flex align-items-center" role="alert">
                <ul>
                    <li><i class="fas fa-solid fa-circle-check"></i>&nbsp; <?php echo session()->get('req_success') ?></li>
                </ul>
            </div>
        <?php session()->forget('req_success');
        } else if ($errors->any()) { ?>
            <div class="alert custom-alert alert-danger d-flex align-items-center" role="alert">
                <ul>
                    <?php foreach ($errors->all() as $key => $value) { ?>
                        <li><i class="fas fa-exclamation-triangle"></i> <?php echo $value ?></li>
                    <?php } ?>
                </ul>
            </div>
<?php }
    }
}
