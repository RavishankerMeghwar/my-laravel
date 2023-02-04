<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserBySuperAdmin;
use App\Mail\SendEmail;
use App\Models\User;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerifyEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->per_page ?? 10;
        $users = User::when($request->search, function ($query) use ($request) {
            $query->where('first_name', 'LIKE', '%' . $request->search . '%');
        })->when($request->active, function ($query) {
            $query->whereRaw('flags & ?', [User::FLAG_ACTIVE]);
        })->when($request->role, function ($query) use ($request) {
            $query->where('role', $request->role);
        })->where('role', '!=', 'superadmin')->paginate($perPage);
        return $users;
    }

    public function store(StoreUserRequest $request) // user registration
    {
        $user = new User();
        $user->organization_id   = $request->organization_id;
        $user->first_name        = $request->first_name;
        $user->last_name         = $request->last_name;
        $user->email             = $request->email;
        $user->telephone         = $request->telephone;
        $user->mobile_phone      = $request->mobile_phone;
        $user->password          = $request->password;
        $user->role              = User::ROLE_EMPLOYEE;
        $user->verification_code = rand(9999, 99999);
        $user->updateFlag(User::FLAG_STATUS, $request->is_approved);
        // $user->addFlag(User::FLAG_ACTIVE); //TODO: EMAIL VERIFY WORK
        $user->addFlag(User::FLAG_EMAIL_VERIFIED);
        if ($user->save()) {
            // Mail::to($request->email)->send(new VerifyEmail($user));
            // if (count(Mail::failures()) > 0) return response('Email couldn\'t send!', 500);
            return response("Your account is successfully created");
        }
        return api_error();
    }

    public function me() // me call
    {
        $user = request()->user;
        $user->organization = $user->organization()->first();
        $manager = $user->role       == 'manager';
        $superadmin = $user->role    == 'superadmin';
        $employee = $user->role      == 'employee';
        $stock_manager = $user->role == 'stock_manager';
        $plumber = $user->role       == 'plumber';
        if ($manager) {
            $data['english'] = json_decode(file_get_contents(public_path('assets/translation/english/manager_english.json')));
            $data['german'] = json_decode(file_get_contents(public_path('assets/translation/german/manager_german.json')));
            $data['other'] = json_decode(file_get_contents(public_path('assets/translation/other/manager_other.json')));
            return (object) ['user' => $user, 'translation' => $data];
        }
        if ($superadmin) {
            $data['english'] = json_decode(file_get_contents(public_path('assets/translation/english/superadmin_english.json')));
            $data['german'] = json_decode(file_get_contents(public_path('assets/translation/german/superadmin_german.json')));
            $data['other'] = json_decode(file_get_contents(public_path('assets/translation/other/superadmin_other.json')));
            return (object) ['user' => $user, 'translation' => $data];
        }
        if ($employee) {
            $data['english'] = json_decode(file_get_contents(public_path('assets/translation/english/employee_english.json')));
            $data['german'] = json_decode(file_get_contents(public_path('assets/translation/german/employee_german.json')));
            $data['other'] = json_decode(file_get_contents(public_path('assets/translation/other/employee_other.json')));
            return (object) ['user' => $user, 'translation' => $data];
        }
        if ($stock_manager) {
            return (object) ['user' => $user];
        }
        if ($plumber) {
            return (object) ['user' => $user];
        }
        return response('user not found', 500);
    }

    public function show(User $user)
    {
        if ($user)
            return $user;
        return response('User not found', 500);
    }

    public function profilePicture(User $user) // download user profile
    {
        return Storage::download($user->profile_image);
    }

    public function update(UpdateUserRequest $request, $id) // update user
    {
        $user = User::withoutGlobalScopes()->find($id);
        $user->first_name   = $request->input('first_name', $user->first_name);
        $user->last_name    = $request->input('last_name', $user->last_name);
        $user->email        = $request->input('email', $user->email);
        $user->language     = $request->input('language', $user->language);
        $user->telephone    = $request->input('telephone', $user->telephone);
        $user->mobile_phone = $request->input('mobile_phone', $user->mobile_phone);
        $user->updateFlag(User::FLAG_ACTIVE, $request->status);
        $user->updateFlag(User::FLAG_STATUS, $request->is_approved);
        if ($request->has('password') && filled($request->password))
            $user->password = $request->password;
        if ($request->has('role') && filled($request->role)) {
            $user->role = $request->role;
        }
        if ($user->save()) {
            if ($request->hasFile('profile_image')) {
                if ($user->profile_image) Storage::delete($user->profile_image);
                $profile_image = $request->file('profile_image')->store('users/' . $user->user_name);
                $user->profile_image = $profile_image;
                if ($user->save()) {
                    return $user;
                }
                return response('Profile did not update', 500);
            }
            return $user;
        }
        return response('Profile did not update', 500);
    }

    public function storeUser(StoreUserBySuperAdmin $request) //store user by manager and super admin
    {
        $user = new User();
        $user->organization_id   = $request->organization_id;
        $user->first_name        = $request->first_name;
        $user->last_name         = $request->last_name;
        $user->email             = $request->email;
        $user->telephone         = $request->telephone;
        $user->language          = $request->language;
        $user->mobile_phone      = $request->mobile_phone;
        $user->password          = $request->password;
        $user->role              = $request->role;
        $user->addFlag(User::FLAG_ACTIVE);
        $user->addFlag(User::FLAG_EMAIL_VERIFIED);
        if ($user->save()) {
            Mail::to($request->email)->send(new SendEmail($user));
            if (count(Mail::failures()) > 0) return response('Email couldn\'t send!', 500);
            return $user;
        }
        return response('User not Added', 500);
    }

    public function updateProfile(UpdateProfileRequest $request) //stock manager and plumber
    {
        $user = request()->user;
        $user->first_name   = $request->first_name;
        $user->last_name    = $request->last_name;
        $user->telephone    = $request->telephone;
        $user->language     = $request->language;
        $user->mobile_phone = $request->mobile_phone;
        $user->password     = $request->password;
        if ($user->save())
            return $user;
        return response('User not Added', 500);
    }

    public function destroy(User $user)
    {
        if ($user->delete()) return response('User has been deleted');
        return response('something went wrong. try later!');
    }
}
