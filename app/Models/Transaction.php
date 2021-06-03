<?php

namespace App\Models;

use App\Models\Traits\UsesUuid;
use App\Models\Traits\UsesSerializeDates;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Transaction Model class.
 */
class Transaction extends Model
{
    use SoftDeletes, UsesUuid, UsesSerializeDates;

    protected $table = 'transactions';

    protected $primaryKey = 'id';

    protected $fillable = ['id', 'payee_id', 'payer_id', 'value', 'status'];
}
