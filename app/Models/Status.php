<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    use HasFactory;
	public $timestamps = false;
	public function invoice() { return $this->hasMany  (Invoice::class ); }
}
