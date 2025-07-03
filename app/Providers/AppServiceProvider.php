<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\ChangeTracker;
use App\Models\Order;
use App\Models\ChangeLog;
use Illuminate\Support\Facades\Auth;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }


    public function boot()
    {
        // 🟩 Track Order changes
        Order::updating(function ($order) {
            ChangeTracker::setOrderId($order->id);

            $original = $order->getOriginal();
            $dirty = $order->getDirty();

            $ignoredFields = ['last_payment_date'];

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
                if (in_array($key, $ignoredFields)) continue;

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

        // 🟩 Handle logging at the end of request
        app()->terminating(function () {
            $allChanges = ChangeTracker::getAll();
            if (empty($allChanges)) return;

            $formatted = [
                'before' => [],
                'after'  => [],
            ];

            // 📦 Nesting rules: model => ['parent', 'child_key']
            $nestingRules = [
                'measurements' => ['items', 'measurements'],
                'voice_messages'  => ['items', 'voice_messages'],
            ];

            foreach ($allChanges as $modelType => $entries) {
                foreach ($entries as $entry) {
                    $modelId     = $entry['id'] ?? null;
                    $parentId    = $entry['order_item_id'] ?? null;

                    $injectId = function (array $data, $id) {
                        if ($id !== null) {
                            $data['id'] = $id;
                        }
                        return $data;
                    };

                    if (isset($nestingRules[$modelType])) {
                        [$parentKey, $childKey] = $nestingRules[$modelType];

                        // 🟨 Special case for create/delete-only types like voice_messages
                        if (isset($entry['action']) && in_array($entry['action'], ['created', 'deleted'])) {
                            $data = $entry['data'] ?? [];

                            if ($entry['action'] === 'created') {
                                if ($parentId !== null) {
                                    $formatted['after'][$parentKey]['id'] = $parentId;
                                }
                                $formatted['after'][$parentKey][$childKey][] = array_merge(['id' => $modelId], $data);
                            }

                            if ($entry['action'] === 'deleted') {
                                if ($parentId !== null) {
                                    $formatted['before'][$parentKey]['id'] = $parentId;
                                }
                                $formatted['before'][$parentKey][$childKey][] = array_merge(['id' => $modelId], $data);
                            }

                            continue; // 🛑 Skip default logic for this entry
                        }

                        // ✅ Default before/after logic
                        if (!empty($entry['before'])) {
                            if ($parentId !== null) {
                                $formatted['before'][$parentKey]['id'] = $parentId;
                            }

                            $formatted['before'][$parentKey][$childKey] = array_merge(
                                ['id' => $modelId],
                                $entry['before']
                            );
                        }

                        if (!empty($entry['after'])) {
                            if ($parentId !== null) {
                                $formatted['after'][$parentKey]['id'] = $parentId;
                            }

                            $formatted['after'][$parentKey][$childKey] = array_merge(
                                ['id' => $modelId],
                                $entry['after']
                            );
                        }
                    } else {
                        // 🔁 Fallback for non-nested types
                        if (!empty($entry['before'])) {
                            $formatted['before'][$modelType] = $injectId($entry['before'], $modelId);
                        }

                        if (!empty($entry['after'])) {
                            $formatted['after'][$modelType] = $injectId($entry['after'], $modelId);
                        }
                    }
                }
            }

            if (!empty($formatted['before']) || !empty($formatted['after'])) {
                $orderId = ChangeTracker::getOrderId();
                if ($orderId) {
                    try {
                        ChangeLog::create([
                            'purpose'      => request()->input('action') ?? 'order_edit',
                            'order_id'     => $orderId,
                            'done_by'      => Auth::guard('admin')->id(),
                            'data_details' => json_encode($formatted),
                        ]);
                    } catch (\Throwable $e) {

                    }
                }
            }

            ChangeTracker::clear();
        });
    }


}
