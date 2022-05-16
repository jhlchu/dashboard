<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;
	protected $fillable = [
		'name',
		'address1',
		'address2',
		'city',
		'province',
		'country',
		'postalcode',
		'phone',
		'email',
		'url',
		'logo',
    ];

	

	public function invoice() { return $this->hasMany(Invoice::class ); }
}