# GM9988 API

## Environment
- PHP >= 7.2.5
- laravel 7.x
- mariadb 12.4
    - `charset: utf8mb4`
    - `collation: utf8mb4_unicode_ci`

## How to start

### In Development Environment
1. `cp .env.example .env` 並修改 `.env` 的參數
1. `composer install`
1. `composer demo`

### In Production Environment
1. `cp .env.example .env` 並修改 `.env` 的參數 `APP_ENV=prod`
1. `composer install`

## 部分功能說明

### Swagger
此部分可使用兩種方法生成 Api 文件，Api 文件 URL 預設路徑 `{YOURDOMAIN}/api/doc`

1. (推薦)直接手動編輯檔案，詳情參照 [Reference](##Reference) 第四點、第五點
    1. 統一使用 OpenApi version 3.0.0

1. ~~此為套件 [darkaonline/l5-swagger](https://packagist.org/packages/darkaonline/l5-swagger) 提供的方法，在此後做了些微的調整~~
    1. ~~在 `config\swagger` 底下，新增或修改檔案，請`務必確認格式是否相同`~~
    1. ~~上述步驟完成之後，請執行`php artisan l5-swagger:generate --all`~~

若檔案同時存在時，畫面呈現的優先順序為 `yml > yaml > json`

### Permission

有兩種方式可以添加，以 `add articles` 權限為例：
1. 詳細寫法請參照 [Reference](##Reference) 第二點
    ```php
    // route/api.php

    Route::group(['middleware' => ['permission:add articles']], function () {
        //
    });
    ```

1. in laravel Controller，其他詳細請參照 [Reference](##Reference) 第三點
    ```php
    public function __construct()
    {
        $this->can('add articles');
    }
    ```
    or
    ```php
    public function __construct()
    {
        $this->can([
            'add articles'
        ]);
    }
    ```
    指定 function 才驗證權限
    ```php
    public function __construct()
    {
        $this->can([
            'add articles'
        ])->only('getList');
    }

    public function getList()
    {
        //
    }
    ```
    排除指定 function 的權限驗證
    ```php
    public function __construct()
    {
        $this->can([
            'add articles'
        ])->except('getList');
    }

    public function getList()
    {
        //
    }
    ```
## Reference

1. [Swagger Multiple Documentations](https://github.com/DarkaOnLine/L5-Swagger/pull/270)
1. [Middleware with plugins spatie/laravel-permission](https://docs.spatie.be/laravel-permission/v3/basic-usage/middleware/)
1. [Middleware with laravel](https://laravel.com/docs/7.x/controllers#controller-middleware)
1. [Swagger Editor](https://editor.swagger.io/)
1. [OpenApis](http://spec.openapis.org/oas/v3.0.3)
