<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Order RI {{ $order_no }}</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      font-size: 14px;
      margin: 20px;
      line-height: 1.5;
    }

    .title {
      text-align: center;
      font-size: 18px;
      font-weight: bold;
      margin-bottom: 15px;
    }
    .info-table td{
        border:1px solid
    }
    .info-table, .status-table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 10px;
    }

    .info-table td, .status-table td {
      padding: 6px 10px;
    }

    .barcode {
      text-align: center;
      font-family: monospace;
      font-size: 16px;
      margin: 20px 0;
    }

    .payment-table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }

    .payment-table th, .payment-table td {
      border: 1px solid black;
      padding: 6px 10px;
      text-align: center;

    }

    .payment-table td {
      height: 40px; /* adjust as needed */
      font-size: 14px; /* match header font size */
    }

    .section-title {
      font-weight: bold;
      margin-top: 30px;
    }

  </style>
</head>
<body>

  <div class="title">ORDER NO. {{ $order_no }}</div>
  <div style="padding-left:35px;float:left;">PREV. ORDER NO. {{ $last_order_no }}</div>
  <div style="padding-left:400px;">R1 NEXT ORDER NO</div>
  <table class="info-table">
    <tr>
      <td>NAME </td>
      <td>{{ $name }}</td>
    </tr>
    <tr>
        <td>RANK </td>
        <td>{{ $rank }}</td>
      </tr>
    <tr>
      <td>ADDRESS</td>
      <td>{{ $address }} </td>
    </tr>
    <tr>
        <td>TELEPHONE</td>
        <td>{{ $telephone }} </td>
      </tr>
    <tr>
      <td>AMOUNT</td>
      <td>{{ $amount }}</td>
    </tr>
    <tr>
        <td>ITEM SOLD</td>
        <td>{{ $item_sold }}</td>
    </tr>
    <tr>
        <td>REST ITEMS</td>
        <td>{{ $rest_items }}</td>
    </tr>
    <tr>
        <td>STATUS</td>
        <td>{{ $status }}</td>
    </tr>
    <tr>
        <td>LIVRAISON SIGN & DATE</td>
        <td style="border: none !important; vertical-align: top;border-top: none !important;">
            <table class="payment-table" style="border-collapse: collapse; width: 100%;" cellspacing="0"cellpadding="0">
                @for ($i = 1; $i <= $net_qty; $i++)
                    @if ($i % 3 == 1)
                        <tr>
                    @endif

                    <td style="border: 1px solid #000; width: 33%; height: 50px;"></td>

                    @if ($i % 3 == 0 || $i == $net_qty)
                        </tr>
                    @endif
                @endfor
            </table>
        </td>


    </tr>
  </table>



  <table class="payment-table">
    <thead>
      <tr>
        <th>DATE</th>
        <th>PAY</th>
        <th>TOTAL.REST</th>
        <th>ACT.REST</th>
        <th>SIGNATURE</th>
      </tr>
    </thead>
    <tbody>
      {{-- <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
      </tr>
      <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
      </tr>
      <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
      </tr>
      <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
      </tr> --}}
      {{-- <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
      </tr>
      <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
      </tr>
      <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
      </tr>
      <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
      </tr>
      <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
      </tr> --}}
        @forelse($paymentRows as $row)
      <tr>
        <td>{{ $row['date'] }}</td>
        <td>{{ $row['pay'] }}</td>
        <td>{{ $row['total_rest'] }}</td>
        <td>{{ $row['act_rest'] }}</td>
        <td>{{ $row['signature'] }}</td>
      </tr>
    @empty
      @for($i = 1; $i <= 9; $i++)
        <tr>
          <td></td><td></td><td></td><td></td><td></td>
        </tr>
      @endfor
    @endforelse
    </tbody>
  </table>

</body>
</html>
