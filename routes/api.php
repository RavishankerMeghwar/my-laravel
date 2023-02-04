<?php

use App\Http\Controllers\BuildingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ComponentController;
use App\Http\Controllers\ComponentPriceController;
use App\Http\Controllers\ComponentTypeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ElectricityTariffController;
use App\Http\Controllers\EnergySupplierController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ManufacturerController;
use App\Http\Controllers\ModalController;
use App\Http\Controllers\ModalInformationController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\PowerConsumptionController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectTemplateController;
use App\Http\Controllers\PvInverterController;
use App\Http\Controllers\PvModuleController;
use App\Http\Controllers\TariffController;
use App\Http\Controllers\TranslationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ComponentQuantityUpdateController;
use App\Http\Controllers\SubsidyController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('sessionAuth')->group(function () {
    Route::post('login', [LoginController::class, 'login'])->name('login');
    Route::post('register', [UserController::class, 'store'])->name('register');
    Route::post('stock-manager-login', [LoginController::class, 'stock_manager_login'])->name('StockManagerLogin');
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');
    Route::post('verify/email', [LoginController::class, 'verifyEmail'])->name('verifyEmail');
    Route::post('forget/password', [LoginController::class, 'forgetPasswordEmail'])->name('forgetPasswordEmail');
    Route::post('verify/reset/password', [LoginController::class, 'forget_password_email_verification'])->name('forget_password_email_verification');
    Route::post('reset/password', [LoginController::class, 'resetPassword'])->name('resetPassword');
    Route::post('update/password', [LoginController::class, 'updatePassword']);
    Route::get('general/data', [LoginController::class, 'generalData']);
    Route::get('verify/organization/{token}', [OrganizationController::class, 'verifyOrganization'])->name('verifyOrganization');

    Route::get('me', [UserController::class, 'me'])->name('MeCall');
    Route::post('profile/{id}', [UserController::class, 'update'])->name('updateProfile');
    Route::get('account/delete/{user}', [UserController::class, 'destroy'])->name('deleteAccount');
    Route::get('profile/{user}', [UserController::class, 'profilePicture']);
    Route::post('update/profile', [UserController::class, 'updateProfile']);
    // Resource Route
    Route::resource('users', UserController::class);

    // image download
    Route::get('modal-image/{modal}', [ModalController::class, 'modalImage']);                  //modal image
    Route::get('component-image/{component}', [ComponentController::class, 'componentImage']);  //component image
    Route::get('organization-logo/{organization}', [OrganizationController::class, 'logo']);    // organization logo

    // Routes with Prefix
    Route::middleware('managerAuth')->prefix('manager')->group(function () {
        Route::get('update-component-quantity-logs-web', [ComponentQuantityUpdateController::class,'store_web'])->name('UpdateComponentQuantityLogsWeb');
        Route::get('get-component-quantity-logs', [ComponentQuantityUpdateController::class,'index']);
        Route::post('add-component-quantity-logs/{component}', [ComponentQuantityUpdateController::class,'store']);
        Route::resource('users', UserController::class);
        Route::resource('organizations', OrganizationController::class)->only('update');
        Route::resource('component-types.manufacturers', ManufacturerController::class)->only('index');
        Route::resource('component-types', ComponentTypeController::class)->only('index');
        Route::resource('manufacturers.modals', ModalController::class)->only('index');
        Route::resource('projects', ProjectController::class);
        Route::resource('components', ComponentController::class);
        Route::resource('groups', GroupController::class);
        Route::resource('buildings', BuildingController::class);
        Route::resource('pv-modules', PvModuleController::class);
        Route::get('pv-module/{project_id}', [PvModuleController::class,'pvModuleByProject']);
        Route::resource('electricity-tariffs', ElectricityTariffController::class);
        Route::get('electricity/tariff/{project_id}', [ElectricityTariffController::class, 'electricityTariffByProject']);
        Route::resource('pv-inverters', PvInverterController::class);
        Route::get('pv-inverter/{project_id}', [PvInverterController::class, 'show']);
        Route::resource('project-templates', ProjectTemplateController::class);
        Route::resource('components.componentPrices', ComponentPriceController::class);
        Route::resource('energy-suppliers', EnergySupplierController::class);
        Route::resource('power-consumptions', PowerConsumptionController::class);
        Route::resource('subsidies', SubsidyController::class);
        Route::resource('tariffs', TariffController::class);
        Route::post('user/register', [UserController::class, 'storeUser']);
        Route::post('update/user/{id}', [UserController::class, 'update']);
        Route::get('home', [DashboardController::class, 'homeManager']);
        Route::get('get-components', [ComponentController::class, 'activeComponent']);
        Route::get('get-managers', [ProjectTemplateController::class, 'getManager']);
    });

    // Routes with Prefix
    Route::middleware('superAdminAuth')->prefix('admin')->group(function () {
        Route::resource('users', UserController::class);
        Route::resource('organizations', OrganizationController::class);
        Route::resource('component-types', ComponentTypeController::class);
        Route::resource('component-types.manufacturers', ManufacturerController::class);
        Route::resource('manufacturers.modals', ModalController::class);
        Route::resource('manufacturers', ManufacturerController::class)->only('index');
        Route::resource('components', ComponentController::class);
        Route::resource('modals.modalInformations', ModalInformationController::class);
        Route::resource('modals', ModalController::class);
        Route::resource('energy-suppliers', EnergySupplierController::class);
        Route::resource('tariffs', TariffController::class);
        Route::resource('power-consumptions', PowerConsumptionController::class);
        Route::resource('components.componentPrices', ComponentPriceController::class);

        Route::post('store/userbyadmin', [UserController::class, 'storeUser']);
        Route::post('update/user/{id}', [UserController::class, 'update']);
        Route::get('getusers/{organization_id}', [OrganizationController::class, 'getUser']);

        // Translation Controller Calls
        Route::post('upload-csv', [TranslationController::class, 'upload_csv']);
        Route::get('refresh-files', [TranslationController::class, 'refresh_files']);
        Route::get('translations', [TranslationController::class, 'index'])->name('GetTranslation');
        Route::post('translation', [TranslationController::class, 'store'])->name('AddTranslation');
        Route::post('translation/{translation}', [TranslationController::class, 'update'])->name('UpdateTranslation');
        Route::delete('delete-translation/{translation}', [TranslationController::class, 'destroy'])->name('DeleteTranslation');
        // Translation Controller Calls
    });

    Route::middleware('employeeAuth')->prefix('employee')->group(function () {
        Route::resource('users', UserController::class);
        Route::resource('organizations', OrganizationController::class)->only('update');
        Route::resource('component-types.manufacturers', ManufacturerController::class)->only('index');
        Route::resource('component-types', ComponentTypeController::class)->only('index');
        Route::resource('manufacturers.modals', ModalController::class)->only('index');
        Route::resource('projects', ProjectController::class);
        Route::resource('components', ComponentController::class);
        Route::resource('groups', GroupController::class);
        Route::resource('buildings', BuildingController::class);
        Route::resource('pv-modules', PvModuleController::class);
        Route::get('pv-module/{project_id}', [PvModuleController::class,'pvModuleByProject']);
        Route::resource('electricity-tariffs', ElectricityTariffController::class);
        Route::get('electricity/tariff/{project_id}', [ElectricityTariffController::class, 'electricityTariffByProject']);
        Route::resource('pv-inverters', PvInverterController::class);
        Route::get('pv-inverter/{project_id}', [PvInverterController::class, 'show']);
        Route::resource('project-templates', ProjectTemplateController::class);
        Route::resource('components.componentPrices', ComponentPriceController::class);
        Route::resource('energy-suppliers', EnergySupplierController::class);
        Route::resource('power-consumptions', PowerConsumptionController::class);
        Route::resource('tariffs', TariffController::class);
        Route::post('register', [UserController::class, 'storeUser']);
        Route::get('home', [DashboardController::class, 'homeManager']);
        Route::get('get-components', [ComponentController::class, 'activeComponent']);
        Route::get('get-managers', [ProjectTemplateController::class, 'getManager']);
    });
});

Route::get('download-csv', [TranslationController::class, 'download_csv'])->name('download-file');
Route::get('get-csvfile', [TranslationController::class, 'getFile'])->name('getFile');
