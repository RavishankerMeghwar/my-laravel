<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ForgetPasswordEmail;
use App\Http\Requests\LanguageCountryRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Requests\VerifyEmailRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Models\LoginAttempt;
use App\Mail\VerifyEmail;
use App\Mail\ResetPasswordLinkMail;
use App\Mail\ResetPasswordTokenMail;

class LoginController extends Controller
{
    function login(LoginRequest $request)
    {
        $user = User::where("email", $request->email)->first();
        if ($user && HASH::check($request->password, $user->password)) {
            if ($user->active) {
                if ($user->email_verified) {
                    request()->user               = $user;
                    $user->organization           = $user->organization()->first();
                    $login_attempt                = new LoginAttempt();
                    $login_attempt->user_id       = $user->id;
                    $login_attempt->access_token  = generate_token($user);
                    $login_attempt->access_expiry = date("Y-m-d H:i:s", strtotime("1 year"));
                    if (!$login_attempt->save()) return api_error();
                    $manager = $user->role     == 'manager';
                    $super_admin = $user->role == 'superadmin';
                    $employee = $user->role    == 'employee';
                    if ($manager) {
                        $data['english'] = json_decode(file_get_contents(public_path('assets/translation/english/manager_english.json')));
                        $data['german'] = json_decode(file_get_contents(public_path('assets/translation/german/manager_german.json')));
                        $data['other'] = json_decode(file_get_contents(public_path('assets/translation/other/manager_other.json')));
                        return (object) ['user' => $user, 'access_token' => $login_attempt->access_token, 'translation' => $data];
                    }
                    if ($super_admin) {
                        $data['english'] = json_decode(file_get_contents(public_path('assets/translation/english/superadmin_english.json')));
                        $data['german'] = json_decode(file_get_contents(public_path('assets/translation/german/superadmin_german.json')));
                        $data['other'] = json_decode(file_get_contents(public_path('assets/translation/other/superadmin_other.json')));
                        return (object) ['user' => $user, 'access_token' => $login_attempt->access_token, 'translation' => $data];
                    }
                    if ($employee) {
                        $data['english'] = json_decode(file_get_contents(public_path('assets/translation/english/employee_english.json')));
                        $data['german'] = json_decode(file_get_contents(public_path('assets/translation/german/employee_german.json')));
                        $data['other'] = json_decode(file_get_contents(public_path('assets/translation/other/employee_other.json')));
                        return (object) ['user' => $user, 'access_token' => $login_attempt->access_token, 'translation' => $data];
                    }
                } else {
                    Mail::to($request->email)->send(new VerifyEmail($user));
                    if (count(Mail::failures()) > 0) return response("Email couldn\'t send!", 500);
                    return response("We have sent you a Token on to your email address. Kindly open it and change your password!");
                }
            } else {
                return response(["message" => "Your Account is not active yet!"], 400);
            }
        } else {
            return response(["message" => "Invalid email / password"], 400);
        }
    }


    function stock_manager_login(LoginRequest $request)
    {
        $user = User::where("email", $request->email)->first();
        if ($user && HASH::check($request->password, $user->password) && ($user->role == User::ROLE_STOCK_MANAGER || $user->role == User::ROLE_PLUMBER)) {
            if ($user->active) {
                if ($user->email_verified) {
                    request()->user               = $user;
                    $login_attempt                = new LoginAttempt();
                    $login_attempt->user_id       = $user->id;
                    $login_attempt->access_token  = generate_token($user);
                    $login_attempt->access_expiry = date("Y-m-d H:i:s", strtotime("1 year"));
                    if (!$login_attempt->save()) return api_error();
                    return (object) ['user' => $user, 'access_token' => $login_attempt->access_token];
                } else {
                    Mail::to($request->email)->send(new VerifyEmail($user));
                    if (count(Mail::failures()) > 0) return response("Email couldn\'t send!", 500);
                    return response("We have sent you a Token on to your email address. Kindly open it and change your password!");
                }
            } else {
                return response(["message" => "Your Account is not active yet!"], 400);
            }
        } else {
            return response(["message" => "Invalid email / password"], 400);
        }
    }

    public function forgetPasswordEmail(ForgetPasswordEmail $request)
    {
        $user = User::where('email', $request->email)->first();
        if ($user) {
            $user->reset_token = rand(99999, 999999);
            if ($user->save()) {
                Mail::to($user->email)->send(new ResetPasswordLinkMail($user));
                if (count(Mail::failures()) > 0) return response("Email couldn\'t send!", 500);
                return response("We have sent you a Token on to your email address. Kindly open it and change your password!");
            }
        }
        return response("Invalid token!", 500);
    }

    public function forget_password_email_verification(Request $request)
    {
        $request->validate(['email' => 'bail|required|email']);
        $user = User::where('email', $request->email)->first();
        if ($user) {
            $user->reset_token = rand(9999999, 999999999);
            if ($user->save()) {
                Mail::to($user->email)->send(new ResetPasswordTokenMail($user));
                if (count(Mail::failures()) > 0) return api_error('Email couldn\'t send!');

                return response('We have sent you a Token on to your email address. Kindly open it and change your password!');
            }
        }
        return api_error('Invalid email!');
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        $user = User::where('reset_token', $request->reset_token)->first();
        if ($user) {
            $user->reset_token = NULL;
            $user->password = $request->password;
            if ($user->save()) return response("Your password has been updated successfully! You can now log into your profile again!");
        }
        return response("Invalid token!", 500);
    }

    public function updatePassword(UpdatePasswordRequest $request)
    {
        $user = User::where('email', request()->user->email)->first();
        if ($user) {
            $user->password = $request->password;
            if ($user->save()) return response("Your password has been updated successfully!");
        }
        return response("", 500);
    }

    public function verifyEmail(VerifyEmailRequest $request)
    {
        $user = User::where('email', $request->email)->first();
        if ($user) {
            $user->verification_code = NULL;
            $user->addFlag(User::FLAG_EMAIL_VERIFIED);
            if ($user->save()) return response("Your email is verified successfully!");
        }
        return response("Invalid token!", 500);
    }

    public function logout(Request $request)
    {
        if ($request->login_attempt) {
            $request->login_attempt->access_expiry = date("Y-m-d H:i:s");
            $request->login_attempt->save();
        }
        $data['english'] = json_decode(file_get_contents(public_path('assets/translation/english/general_english.json')));
        $data['german'] = json_decode(file_get_contents(public_path('assets/translation/german/general_german.json')));
        $data['other'] = json_decode(file_get_contents(public_path('assets/translation/other/general_other.json')));
        return  $data;
    }

    public function generalData(LanguageCountryRequest $request)
    {
        $data = config($request->value);
        if ($data)
            return response($data);
        else return response([]);
    }
}
