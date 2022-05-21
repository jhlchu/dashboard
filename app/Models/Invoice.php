<?php

namespace App\Models;

use App\FormatOutput\FormatOutput;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

	protected $fillable = [
		'invoice_number',
		'company_id',
		'status_id',
		'salesperson_id',
		'customer_id',
		'completed_at',
		'paid_at'
    ];


	public function status      () { return $this->belongsTo (Status     ::class, 'status_id'     ); }
	public function company     () { return $this->belongsTo (Company    ::class, 'company_id'    ); }
	public function user        () { return $this->belongsTo (User       ::class, 'salesperson_id'); }
	public function customer    () { return $this->belongsTo (Customer   ::class, 'customer_id'   ); }
	public function invoice_row () { return $this->hasMany   (InvoiceRow ::class                  ); }

	public function scopeFilter($query, array $filters) {
		//dd($query->where('invoice_number', '=', request('query'))->orWhere('notes', 'like', '%'.request('query').'%')->get());
		//dd(InvoiceRow::where('description', 'like', '%et%')->leftJoin('invoices', 'invoice_id', '=', 'invoices.id')->select('invoices.*')->get());
		if ($filters['query'] ?? false) {
			$query->where('invoice_number', request('query'))
					//->orWhere('description', 'like', '%'.request('query').'%')
					->orWhere('notes', 'like', '%'.request('query').'%')
					->union(InvoiceRow::where('description', 'like', '%'.request('query').'%')->leftJoin('invoices', 'invoice_id', '=', 'invoices.id')->select('invoices.*'));
		}
		if ($filters['salesperson_id'] ?? false) {
			$query->where('salesperson_id', request('salesperson_id'));
		}
		if ($filters['customer_id'] ?? false) {
			$query->where('customer_id', request('customer_id'));
		}
		if ($filters['company_id'] ?? false) {
			$query->where('company_id', request('company_id'));
		}
		if ($filters['status_id'] ?? false) {
			$query->where('status_id', request('status_id'));
		}
	}

	public function getRouteKeyName()
	{
		return 'invoice_number';
	}

	public function getGrossTotalAttribute()
	{
		$total = ($this->invoice_row)->reduce(
			function ($temp, $item) {
				return $temp + $item->total;
			},
			0
		);
		return $total;
	}

	public function getTaxAttribute()
	{
		return $this->customer->tax;
	}

	public function getBeforeTaxAttribute() {
		/* return $this->gross_total + $this->shipping_handling - FormatOutput::moneyFormat($this->discount); */

		return $this->gross_total + $this->shipping_handling - $this->discount_value;
	}

	public function getTaxTotalAttribute() {
		$tax = ($this->tax)->reduce(
			function ($temp, $tax) {
				return $temp + $tax->value;
			},
			0.00
		);
		return $tax;
	}

	public function getNetTotalAttribute() {
		return $this->before_tax * (1 + $this->tax_total);
	}

	public function getDiscountValueAttribute()
	{
		$discount_value = preg_match('/[0-9]+\.?[0-9]*/', $this->discount, $out) ? floatVal($out[0]) : 0.00;
		if (str_contains($this->discount, '%')) {
			return ($this->gross_total + $this->shipping_handling) * ($discount_value / 100);
		} else {
			return $discount_value;
		}
	}
/* 
	protected function discount(): Attribute
	{
		return Attribute::make(
			get: function ($discount_string) {
				$discount_value = (float) preg_match('/[0-9]+\.?[0-9]+/', $discount_string, $out) ? $out[0] : 0.00;
				if (str_contains($discount_string, '%')) {
					return FormatOutput::moneyFormat(($this->gross_total + $this->shipping_handling) * $discount_value);
				} else {
					return FormatOutput::moneyFormat($discount_value);
				}
			}
		);
	}
 */
	protected function invoice_number(): Attribute
	{
		return Attribute::make(
			get: fn ($value) => intval($value)
		);
	}
}