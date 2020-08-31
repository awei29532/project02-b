<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('login', 'HomeController@login');

Route::group(['middleware' => ['auth.api']], function () {
    Route::post('logout', 'HomeController@logout');

    # agent
    Route::group(['namespace' => 'Agent'], function () {
        Route::apiResource('agent', 'AgentController');
        Route::group(['prefix' => 'agent'], function () {
            Route::post('toggle-enabled', 'AgentController@toggleEnabled');
            Route::post('change-password', 'AgentController@changePassword');
            Route::get('wallet/get', 'AgentController@wallet');
            Route::post('manual-deduct', 'AgentController@ManualDeduct');
            Route::post('manual-add', 'AgentController@ManualAdd');
            Route::post('game-company/toggle-enabled', 'AgentController@gameCompanyToggleEnabled');
            Route::get('game-company-setting/get', 'AgentController@gameCompanySetting');
            Route::post('game/toggle-enabled', 'AgentController@gameToggleEnabled');
            Route::get('game-setting/get', 'AgentController@gameSetting');
            Route::get('search-agent/get', 'AgentController@searchAgent');
            Route::get('login-log/get', 'AgentController@loginLog');
            Route::get('same-ip-user-login/get', 'AgentController@sameIpUserLogin');
            Route::get('same-ip-member-login/get', 'AgentController@sameIpMemberLogin');
            Route::get('same-ip-member-register/get', 'AgentController@sameIpMemberRegister');
            Route::get('login-record-by-member-ip/get', 'AgentController@sameIpSameMember');
            Route::get('login-record-by-user-ip/get', 'AgentController@sameIpSameUser');
        });
    });

    # member
    Route::group(['namespace' => 'Member'], function () {
        Route::apiResource('member', 'MemberController');
        Route::group(['prefix' => 'member'], function () {
            Route::get('ip-list/get', 'MemberController@ipExistsList');
            Route::post('toggle-enabled', 'MemberController@toggleEnabled');
            Route::post('change-password', 'MemberController@changePassword');
            Route::post('manual-deduct', 'MemberController@manualDeduct');
            Route::post('manual-add', 'MemberController@manualAdd');
            Route::post('game-company/toggle-enabled', 'MemberController@gameCompanyToggleEnabled');
            Route::get('game-company-setting/get', 'MemberController@gameCompanySetting');
            Route::post('game/toggle-enabled', 'MemberController@gameToggleEnabled');
            Route::get('game-setting/get', 'MemberController@gameSetting');
            Route::get('member-level/get', 'MemberController@memberLevel');
            Route::get('all-rebate/get', 'MemberController@allRebate');
            Route::get('login-log/get', 'MemberController@loginLog');
            Route::get('same-ip-user-login/get', 'MemberController@sameIpUserLogin');
            Route::get('same-ip-member-login/get', 'MemberController@sameIpMemberLogin');
            Route::get('same-ip-member-register/get', 'MemberController@sameIpMemberRegister');
            Route::get('login-record-by-member-ip/get', 'MemberController@sameIpSameMember');
            Route::get('login-record-by-user-ip/get', 'MemberController@sameIpSameUser');
            Route::get('search-agent/get', 'MemberController@searchAgent');
        });
    });

    # sub account
    Route::apiResource('sub-account', 'SubAccountController');
    Route::group(['prefix' => 'sub-account'], function () {
        Route::post('change-password', 'SubAccountController@changePassword');
        Route::post('toggle-enabled', 'SubAccountController@toggleEnabled');
        Route::get('login-log/get', 'SubAccountController@loginLog');
        Route::get('same-ip-user-login/get', 'SubAccountController@sameIpUserLogin');
        Route::get('same-ip-member-login/get', 'SubAccountController@sameIpMemberLogin');
        Route::get('same-ip-member-register/get', 'SubAccountController@sameIpMemberRegister');
        Route::get('login-record-by-member-ip/get', 'SubAccountController@sameIpSameMember');
        Route::get('login-record-by-user-ip/get', 'SubAccountController@sameIpSameUser');
        Route::get('permission/get', 'SubAccountController@permissionIndex');
    });

    # customer service
    Route::apiResource('customer-service', 'CustomerServiceController');
    Route::group(['prefix' => 'customer-service'], function () {
        Route::post('change-password', 'CustomerServiceController@changePassword');
        Route::post('toggle-enabled', 'CustomerServiceController@toggleEnabled');
        Route::get('all-permission/get', 'CustomerServiceController@allPermission');
        Route::get('wallet/get', 'CustomerServiceController@wallet');
        Route::post('deduct-quota', 'CustomerServiceController@deductQuota');
        Route::post('add-quota', 'CustomerServiceController@addQuota');
        Route::get('transfer-record/get', 'CustomerServiceController@transferRecord');
        Route::get('quota-record/get', 'CustomerServiceController@quotaRecord');
        Route::get('login-log/get', 'CustomerServiceController@loginLog');
        Route::get('same-ip-user-login/get', 'CustomerServiceController@sameIpUserLogin');
        Route::get('same-ip-member-login/get', 'CustomerServiceController@sameIpMemberLogin');
        Route::get('same-ip-member-register/get', 'CustomerServiceController@sameIpMemberRegister');
        Route::get('login-record-by-member-ip/get', 'CustomerServiceController@sameIpSameMember');
        Route::get('login-record-by-user-ip/get', 'CustomerServiceController@sameIpSameUser');
    });

    # archive
    Route::apiResource('archive-account', 'ArchiveAccountController');

    Route::apiResource('personal', 'PersonalController');
    Route::group(['prefix' => 'personal'], function () {
        Route::post('change-password', 'PersonalController@changePassword');
        Route::get('transfer-record/get', 'PersonalController@transferRecord');
        Route::get('quota-recoord/get', 'PersonalController@quotaRecord');
        Route::get('login-log/get', 'PersonalController@loginLog');
    });

    # customer service
    Route::group(['prefix' => 'customer-service', 'namespace' => 'CustomerService'], function () {
        Route::apiResource('callback', 'CallbackController');
        Route::apiResource('complaint-letter', 'ComplaintLetterController');
        Route::apiResource('line-setting', 'LineSettingController');
        Route::apiResource('sms', 'SmsController');
        Route::post('sms/send', 'SmsController@send');
        Route::apiResource('message', 'MessageController');
        Route::post('message/send', 'MessageController@send');
    });

    Route::group(['prefix' => 'game', 'namespace' => 'Game'], function () {
        Route::group(['prefix' => 'setting'], function () {
            #maintenance
            Route::apiResource('maintain', 'GameCompanyController')->except('store', 'destroy');
            Route::patch('company/change-status/{id}', 'GameCompanyController@changeStatus');

            #commission
            Route::apiResource('commission', 'GameCompanyCommissionController')->only('index', 'update');
        });
    });

    Route::group(['namespace' => 'Menu'], function () {
        Route::apiResource('menu', 'MenuController')->only('index');
    });

    Route::group(['namespace' => 'System'], function () {
        Route::apiResource('role', 'RolePermissionController');
    });
});
