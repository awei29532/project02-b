<?php

namespace App\Models;

/**
 * @property int $rakeback_id
 * @property int $company_id
 * @property double $amount
 * @property float $percentage
 * @property string $updated_at
 * @property string $created_at
 */
class RebateConfig extends BaseModel
{
    protected $table = 'rebate_config';
}