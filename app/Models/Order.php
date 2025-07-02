<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ChangeLog;
use Illuminate\Support\Facades\Auth;
use App\Services\ChangeTracker;

class Order extends Model
{
    use HasFactory;


    protected $table = 'orders';
    public $_relatedChanges = [];
    protected $fillable = [
        'customer_id',
        'business_type',
        'order_number',
        'customer_name',
        'customer_email',
        'customer_image',
        'billing_address',
        'shipping_address',
        'total_amount',
        'paid_amount',
        'remaining_amount',
        'last_payment_date',
        'payment_mode',
        'status',
        'business_type',
        'created_by' ,
        'team_lead_id',
        'country_code_alt_1',
        'alternative_phone_number_1',
        'country_code_alt_2',
        'alternative_phone_number_2',
        'country_code_whatsapp',
        'country_code_phone',
        'source',
        'reference',
        'ht_amount',
        'tva_amount',
        'ca_amount',
        'due_date',
        'invoice_date',
        'invoice_type',
        'total_product_amount',
        'air_mail'
    ];
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
    public function measurements()
    {
        return $this->hasMany(OrderMeasurement::class);
    }
    public function measurement()
    {
        return $this->belongsTo(Measurement::class, 'measurement_id');
    }
    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function packingslip()
    {
        return $this->hasOne(PackingSlip::class, 'order_id', 'id');
    }
    public function businessType()
    {
        return $this->belongsTo(BusinessType::class, 'business_type');
    }


    protected $status_classes = [
        "Approved"                         => ["Approved", "approved_order"],
        "Ready for Delivery"               => ["Ready for Delivery", "ready_for_delivery"],
        "Cancelled"                        => ["Cancelled", "order_cancelled"],
        "Returned"                         => ["Returned", "order_returned"],
        "Received by Sales Team"           => ["Received by Sales Team", "received_by_sales_team"],
        "Delivered to Customer"            =>["Delivered to Customer","delivered_to_customer"],
        "Partial Delivered to Customer"    =>["Partial Delivered to Customer","partial_delivered_to_customer"],
        "Approval Pending"                 => ["Approval Pending", "approval_pending"],
        "Received at Production"           => ["Received at Production", "received_at_production"],
        "Partial Delivered By Production"  => ["Partial Delivered By Production", "partial_delivered_by_production"],
        "Fully Delivered By Production"    => ["Fully Delivered By Production", "fully_delivered_by_production"],
        "Approved By TL"    => ["Approved By TL", "approved_by_tl"],

    ];

    // Accessor to get status label
    public function getStatusLabelAttribute()
    {
        $order_status = $this->attributes['status'] ?? 'Returned'; // Default to "Returned"
        return $this->status_classes[$order_status][0] ?? "Unknown"; // Fallback to "Unknown"
    }

    // Accessor to get status class
    public function getStatusClassAttribute()
    {
        $order_status = $this->attributes['status'] ?? 'Returned'; // Default to "Returned"
        return $this->status_classes[$order_status][1] ?? "muted"; // Default class if not found
    }
    // In the Order model

    public function invoice()
    {
        return $this->hasOne(Invoice::class, 'order_id', 'id');
    }

    // protected static function booted(): void
    // {
    //     static::updating(function ($order) {
    //         $original = $order->getOriginal();
    //         $dirty = $order->getDirty();

    //         $normalize = function ($value) {
    //             if (is_null($value)) return 0;
    //             if (is_numeric($value)) return (float)$value;
    //             try {
    //                 return (new \DateTime($value))->format('Y-m-d H:i:s');
    //             } catch (\Exception $e) {
    //                 return $value;
    //             }
    //         };

    //         $before = [];
    //         $after = [];

    //         foreach ($dirty as $key => $value) {
    //             $normOld = $normalize($original[$key] ?? null);
    //             $normNew = $normalize($value);
    //             if ($normOld !== $normNew) {
    //                 $before[$key] = $normOld;
    //                 $after[$key] = $normNew;
    //             }
    //         }

    //         if (!empty($before)) {
    //             ChangeTracker::add('order', [
    //                 'order_id' => $order->id,
    //                 'before'   => $before,
    //                 'after'    => $after,
    //             ]);
    //         }
    //     });

    //     static::updated(function ($order) {
    //         $allChanges = ChangeTracker::getAll();
    //         if (empty($allChanges)) return;

    //         ChangeLog::create([
    //             'purpose'      => request()->input('action') ?? 'order_edit',
    //             'order_id'     => $order->id,
    //             'done_by'      => Auth::guard('admin')->id(),
    //             'data_details' => json_encode($allChanges),
    //         ]);

    //         ChangeTracker::clear(); // Clean up after use
    //     });
    // }

    protected static function booted(): void
    {
        static::updating(function ($order) {
            $original = $order->getOriginal();
            $dirty = $order->getDirty();

            $normalize = function ($value) {
                if (is_null($value)) return 0;
                if (is_numeric($value)) return (float)$value;
                try {
                    return (new \DateTime($value))->format('Y-m-d H:i:s');
                } catch (\Exception $e) {
                    return $value;
                }
            };

            $before = [];
            $after = [];

            foreach ($dirty as $key => $value) {
                $normOld = $normalize($original[$key] ?? null);
                $normNew = $normalize($value);
                if ($normOld !== $normNew) {
                    $before[$key] = $normOld;
                    $after[$key] = $normNew;
                }
            }

            if (!empty($before)) {
                ChangeTracker::add('order', [
                    'order_id' => $order->id,
                    'before'   => $before,
                    'after'    => $after,
                ]);
            }
        });

        static::updated(function ($order) {
            $allChanges = ChangeTracker::getAll();
            if (empty($allChanges)) return;

            // Format into { before: {order: {}}, after: {order: {}} }
            $formatted = [
                'before' => [],
                'after'  => [],
            ];

            foreach ($allChanges as $modelType => $entries) {
                foreach ($entries as $entry) {
                    if (!empty($entry['before'])) {
                        $formatted['before'][$modelType] = array_merge(
                            $formatted['before'][$modelType] ?? [],
                            $entry['before']
                        );
                    }
                    if (!empty($entry['after'])) {
                        $formatted['after'][$modelType] = array_merge(
                            $formatted['after'][$modelType] ?? [],
                            $entry['after']
                        );
                    }
                }
            }

            ChangeLog::create([
                'purpose'      => request()->input('action') ?? 'order_edit',
                'order_id'     => $order->id,
                'done_by'      => Auth::guard('admin')->id(),
                'data_details' => json_encode($formatted),
            ]);

            ChangeTracker::clear(); // Clean up after use
        });

}







}
