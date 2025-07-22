<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PaymentCollection extends Model
{
  use HasFactory;

  protected $table= 'payment_collections';
  protected $fillable = [
    'customer_id', 'user_id', 'admin_id', 'payment_id', 'collection_amount', 'cheque_date', 'voucher_no', 'payment_type', 'bank_name', 'cheque_number', 'is_ledger_added', 'image', 'is_approve', 'created_from',
    'created_at', 'updated_at','credit_date','cheque_photo','transaction_no'
  ];

  public function customer()
  {
      return $this->belongsTo(User::class, 'customer_id', 'id');
  }
  public function user()
  {
      return $this->belongsTo(User::class, 'user_id', 'id');
  }
  public function admin()
  {
      return $this->belongsTo(User::class, 'admin_id', 'id');
  }
  public function payment()
    {
        return $this->belongsTo(Payment::class, 'payment_id', 'id');
    }
}
