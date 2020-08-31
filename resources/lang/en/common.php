<?php

/**
 * common message
 *
 * 使用 http status code 當開頭，再加上3碼變成自定義錯誤訊息 ex: 403XXX
 */
return [
    // 403
    'forbidden' => '403',
    'account_disable' => '403000', // account has been disabled.
    'main_account_disable' => '403001', // main account has been disabled
    // end

    // 401
    'unauthorized' => '401',
    'unauthorized_user_pass' => '401000', // username or password error.
    'token_change_success' => '401001', // token changed successful.
    // end

    // 404
    'page_not_found' => '404',
    // end

    // 400
    'upper_status_error' => '400000', // 上層設定未啟用
    'owner_member' => '400001', // 大股東不得新增會員
    'insufficient_balance' => '400002', // 餘額不足
    'callback_exists' => '400003', // 該回電已被承接
    // end
];
