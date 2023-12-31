<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    use HasFactory;

    protected $table = "statuss";

    protected $fillable = ['id', 'status', 'created_at', 'updated_at'];

    public function contractVendor()
    {
        return $this->hasMany(ContractVendor::class, 'status_id');
    }
}
