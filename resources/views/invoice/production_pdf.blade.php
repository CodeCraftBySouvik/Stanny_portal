<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Order Invoice</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
            color: #333;
        }

        .container {
            padding: 20px;
        }

        h3 {
            font-size: 16px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
            margin-top: 20px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .table th,
        .table td {
            border: 1px solid #ccc;
            padding: 6px 8px;
            text-align: left;
        }

        .table th {
            background-color: #f0f0f0;
        }

        .info-box {
            border: 1px solid #ddd;
            padding: 10px;
            margin-top: 10px;
            background: #fcfcfc;
        }

        .section-title {
            font-weight: bold;
            background: #eee;
            padding: 4px 8px;
            margin: 10px 0 5px;
        }

        .total-summary {
            text-align: right;
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #aaa;
            page-break-inside: avoid;
        }

        .highlight {
            color: red;
            font-weight: bold;
        }

        .no-break {
            page-break-inside: avoid;
        }

        @media print {
            * {
                -webkit-print-color-adjust: exact !important;
                color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            table {
                page-break-inside: avoid !important;
                border: 1px solid #000 !important;
            }
        }
    </style>
</head>

<body> 
    <div class="container">
        <div class="no-break">
        {{-- Order Info + Customer Info in One Row --}}
        <table width="100%" cellpadding="10" cellspacing="0" style="margin-bottom: 20px; border:1px solid #ccc;">
            <tr valign="top">
                {{-- Order Information --}}
                <td width="50%" style="border-right: 1px solid #ccc;">
                    <h3 style="margin-top: 0;">Order Information</h3>
                    <table cellpadding="4">
                        <tr>
                            <td><strong>Order Id:</strong></td>
                            <td>{{ $order->order_number ?? '' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Previous Order Id:</strong></td>
                            <td>{{ $previousOrder->order_number ?? 'N/A'}}</td>
                        </tr>
                        <tr>
                            <td><strong>Order Time:</strong></td>
                            <td>{{ $order->created_at->format('d M Y h:i A') }}</td>
                        </tr>


                    </table>
                </td>
            </tr>
        </table>

        {{-- Order Items --}}
        
        
            <h3>Order Items</h3>
            <table class="table" width="100%" style="border:1px solid #ccc;">
                <thead>
                    <tr>
                        <th width="20%">Collection</th>
                        <th width="60%">Order Items</th>
                        <th width="20%">Qty</th>
                        <th width="20%">Expected Delivery Date</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($orderItems->isNotEmpty())
                    @foreach ($orderItems as $item)
                    <tr>
                        <td>{{ $item['collection_title'] }}</td>
                        <td>
                            <div style="display: flex; align-items: center;">
                                <div>{{ $item['product_name'] }}</div>
                            </div>
                        </td>
                        <td>{{ $item['quantity'] }}</td>
                        <td>{{ $item['expected_delivery_date'] ?? null }}</td>
                    </tr>

                    @if ($item['collection_id'] == 1)
                    <tr>
                        <td colspan="5">
                            <div class="info-box">
                                <p><strong>Fabric:</strong> {{ $item['fabrics']->title ?? 'N/A' }}</p>
                                <p><strong>Catalogue:</strong>
                                    {{ optional(optional($item['catalogue'])->catalogueTitle)->title ?? 'N/A' }}
                                    (Page: {{ $item['cat_page_number'] ?? 'N/A' }})
                                </p>

                                @if(!empty($item['remarks']))
                                <p><strong>Remark:</strong> {{ $item['remarks'] }}</p>
                                @endif

                                @if(!empty($item['catlogue_image']['image_path']))
                                <p><strong>Catalogue Image:</strong></p>
                                <img src="{{ asset('storage/' . $item['catlogue_image']['image_path']) }}"
                                    style="width:150px; height:150px; border: 1px solid #ccc; border-radius: 4px;"
                                    alt="Catalogue Image">
                                @endif

                                @if(!empty($item['voice_remark']['voices_path']))
                                <p><strong>Voice Remarks:</strong></p>
                                <audio controls>
                                    <source src="{{ asset('storage/' . $item['voice_remark']['voices_path']) }}"
                                        type="audio/mpeg">
                                    Your browser does not support the audio element.
                                </audio>
                                @endif

                                {{-- Measurements --}}
                                <div class="section-title">Measurements</div>
                                @php
                                $measurements = collect($item['measurements'])->mapWithKeys(function($m) {
                                return [$m['measurement_name'] . ' [' . $m['measurement_title_prefix'] . ']' =>
                                $m['measurement_value']];
                                });
                                $chunks = array_chunk($measurements->toArray(), 5, true);
                                @endphp

                                <table width="100%" cellspacing="0" cellpadding="6">
                                    @foreach($chunks as $row)
                                    <tr>
                                        @foreach($row as $label => $value)
                                        <td style="padding: 8px; vertical-align: top;">
                                            <div
                                                style="display:flex; flex-direction:column; justify-content:space-between;">
                                                <div
                                                    style="font-size: 11px; font-weight: bold; margin-bottom: 3px; min-height:80px;">
                                                    {{ $label }}</div>
                                                <div style="
                                                                border: 1px solid #ccc;
                                                                padding: 6px;
                                                                background: #fff;
                                                                font-size: 12px;
                                                                border-radius: 4px;
                                                                min-height: 25px;
                                                                text-align: center;">
                                                    {{ $value }}
                                                </div>
                                            </div>
                                        </td>
                                        @endforeach
                                        @for ($i = count($row); $i < 5; $i++) <td>
                        </td>
                        @endfor
                    </tr>
                    @endforeach
            </table>

            {{-- Extra Measurements --}}
            <div class="section-title">Extra Requirements</div>

            @php
            $extraFields = [
                // 'Extra Type' => $item['extra_type'] ?? null,
                'Shoulder Type' => $item['shoulder_type'] ?? null,
                'Vents' => $item['vents'] ?? null,
                'Vents Required' => $item['vents_required'] ?? null,
                'Vents Count' => $item['vents_count'] ?? null,
                'Fold Cuff Required' => $item['fold_cuff_required'] ?? null,
                'Fold Cuff Size' => $item['fold_cuff_size'] ?? null,
                'Pleats Required' => $item['pleats_required'] ?? null,
                'Pleats Count' => $item['pleats_count'] ?? null,
                'Back Pocket Required' => $item['back_pocket_required'] ?? null,
                'Back Pocket Count' => $item['back_pocket_count'] ?? null,
                'Adjustable Belt' => $item['adjustable_belt'] ?? null,
                'Suspender Button' => $item['suspender_button'] ?? null,
                'Trouser Position' => $item['trouser_position'] ?? null,
                'Client Name Required' => $item['client_name_required'] ?? null,
                'Client Name' => $item['client_name_place'] ?? null,
            ];

            // Remove null or empty values
            $extraFiltered = array_filter($extraFields, function($value) {
            return !empty($value);
            });
            @endphp

            @if(!empty($extraFiltered))
            <table width="100%" class="table">
                @foreach($extraFiltered as $label => $value)
                <tr>
                    <td width="40%"><strong>{{ $label }}:</strong></td>
                    <td>{{ $value }}</td>
                </tr>
                @endforeach
            </table>
            @else
            <p>No extra details provided.</p>
            @endif

        </div>
        </td>
        </tr>
        @endif
        @endforeach
        @endif
        </tbody>
        </table>
    </div>
    </div>
</body>

</html>