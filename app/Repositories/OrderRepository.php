<?php

namespace App\Repositories;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PackingSlip;
use App\Models\Invoice;
use App\Models\InvoiceProduct;
use App\Models\Ledger;
use App\Models\City;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Support\Facades\DB;

class OrderRepository
{
    public function approveOrder($orderId, $staffId = null)
    {
        try {
            DB::beginTransaction();

            $order = Order::with('items', 'customer')->findOrFail($orderId);

            $order->update([
                'status' => 'Approved',
                'created_by' => $staffId ?? $order->created_by,
                'last_payment_date' => now(),
            ]);

            // Recalculate total
            $subtotal = 0;
            foreach ($order->items as $item) {
                $item->total_price = $item->piece_price * $item->quantity;
                $item->save();
                $subtotal += $item->total_price;
            }

            $airMail = $order->air_mail ?? 0;
            $order->update(['total_amount' => $subtotal + $airMail]);

            // Create packing slip
            $packingSlip = PackingSlip::create([
                'order_id' => $order->id,
                'customer_id' => $order->customer_id,
                'slipno' => $order->order_number,
                'is_disbursed' => 0,
                'created_by' => $staffId,
                'created_at' => now(),
                'disbursed_by' => $staffId,
            ]);

            // Create invoice
            $lastInvoice = Invoice::latest()->first();
            $invoice_no = str_pad(optional($lastInvoice)->id + 1, 6, '0', STR_PAD_LEFT);

            $invoice = Invoice::create([
                'order_id' => $order->id,
                'customer_id' => $order->customer_id,
                'user_id' => $staffId,
                'packingslip_id' => $packingSlip->id,
                'invoice_no' => $invoice_no,
                'net_price' => $order->total_amount,
                'required_payment_amount' => $order->total_amount,
                'created_by' => $staffId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            foreach ($order->items as $item) {
                InvoiceProduct::create([
                    'invoice_id' => $invoice->id,
                    'product_id' => $item->product_id,
                    'product_name' => optional($item->product)->name ?? '',
                    'quantity' => $item->quantity,
                    'single_product_price' => $item->piece_price,
                    'total_price' => $item->total_price + ($item->air_mail ?? 0),
                    'is_store_address_outstation' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            Ledger::insert([
                'user_type' => 'customer',
                'transaction_id' => $invoice_no,
                'customer_id' => $order->customer_id,
                'transaction_amount' => $order->total_amount,
                'bank_cash' => 'cash',
                'is_credit' => 0,
                'is_debit' => 1,
                'entry_date' => now(),
                'purpose' => 'invoice',
                'purpose_description' => 'Invoice raised for customer order',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::commit();
            return true;

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Order Approval Error: ' . $e->getMessage());
            throw $e;
        }
    }
}