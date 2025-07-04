<?php

namespace App\Http\Livewire\Order;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Order;
use App\Helpers\Helper;
use App\Models\User;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\OrdersExport;
use App\Models\Delivery;
use App\Models\Invoice;
use App\Models\ChangeLog;
use App\Models\OrderItem;
use App\Models\OrderMeasurement;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
// use Barryvdh\DomPDF\Facade as PDF;
// use Barryvdh\DomPDF\PDF;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Validation\Rules\Date;

class OrderLog extends Component
{
    use WithPagination;

    public $customer_id;
    public $created_by, $search,$status,$start_date,$end_date,$order;
    public $invoiceId;
    public $orderId;
    public $totalPrice;
    public $auth;

    public $tab = 'all';
    // protected $listeners = ['cancelOrder'];
    protected $listeners = ['cancelOrder','markReceivedConfirmed','deliveredToCustomer','deliveredToCustomerPartial'];

    protected $paginationTheme = 'bootstrap'; // Optional: For Bootstrap styling

    public function changeTab($status){
        $this->tab = $status;
        $this->resetPage();
    }
    public function resetForm(){
        $this->reset(['search', 'start_date','end_date','created_by','status']);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }




    public function mount($id = null)
    {
        $this->orderId = $id; // Store the customer_id if provided
        $this->order = Order::select('id','order_number','created_at')->findOrFail($this->orderId);

    }
    public function FindCustomer($keywords){
        $this->search = $keywords;
    }
    public function AddStartDate($date){
        $this->start_date = $date;
    }
    public function AddEndDate($date){
        $this->end_date = $date;
    }
    public function CollectedBy($staff_id){
        $this->created_by = $staff_id;
    }
    public function setStatus($status){
        $this->status = $status;
    }


    public function render()
    {
        $placed_by = User::where('user_type', 0)->get();
        $auth = Auth::guard('admin')->user();
        $sl_no=1;
        $logs=ChangeLog::where('order_id',$this->orderId)
        ->with('user:id,name')
        ->get();

        $logs = $logs->map(function ($item) use (&$sl_no)  {
            $data_details=json_decode($item->data_details,true);
            $before = $data_details['before']; // stdClass
            $after = $data_details['after'];

            // Convert to array recursively

            $item->before=trim($this->renderDiff($before));
            $item->after=$this->renderDiff($after);
            $item->sl_no=$sl_no++;

            return $item;
        });

        return view('livewire.order.order-log', [
            'logs' => $logs,
            'order'=>$this->order
        ]);
    }



    private function renderDiff($data)
    {
        //echo "<pre>";print_r($data);exit;
        $label="";
        foreach($data as $key =>$val)
        {
          if($key=='items')
          {
            //$label=
            //echo "<pre>";echo ($this->isAssoc($val).'fggg');
            if($this->isAssoc($val))
            {
                $label='Item>>'.$this->fetchItem($val['id']);
                foreach($val as $key=> $sub_val)
                {
                    //$label.=$sub_val;
                 if(is_array($sub_val))
                 {
                    $label.='>>'.$this->subObject($sub_val,$key);

                    //$label.=$this->subObject($sub_val,$key);exit;
                 }
                 else{
                    if($key!='id')
                    {
                        if($key=='expected_delivery_date')
                        {
                            $sub_val= Date('Y-m-d',strtotime($sub_val));
                        }
                        $label.='>>'.Str::title($key).'>>'.$sub_val;

                    }
                 }
                }
            }
            else{
                $label="";
                foreach($val as $key=> $sub_val)
                {
                $label.='Item>>'.$this->fetchItem($sub_val['id']);
                foreach($sub_val as $sub_key=> $sub_sub_val)
                {
                    //$label.=$sub_val;
                    if(is_array($sub_sub_val))
                    {
                    $label.='>>'.$this->subObject($sub_sub_val,$sub_key);

                    //$label.=$this->subObject($sub_val,$key);exit;
                    }
                    else{
                    if($sub_key!='id')
                    {
                        if($sub_key=='expected_delivery_date')
                        {
                            $sub_sub_val= Date('Y-m-d',strtotime($sub_sub_val));
                        }
                        $label.='>>'.Str::title($sub_key).'>>'.$sub_sub_val;

                    }
                    }
                }
                $label.='<br>';
                }

            }
          }

        }

        return  preg_replace('/\s*>>\s*/', '>>', $label);
    }
    private function isIndexed(array $arr): bool
    {
        return array_keys($arr) === range(0, count($arr) - 1);
    }

    /**
     * Returns true if $arr is an “associative” (object‑like) array:
     *   ['foo' => ..., 'bar' => ...]
     */
    private function isAssoc(array $arr): bool
    {
        return ! $this->isIndexed($arr);
    }
    private function fetchItem($id)
    {
        $item=OrderItem::findOrFail($id);
        //echo "<pre>";print_r( $item->product_name);exit;
        return  $item->product_name;
    }

    private function subObject($arr,$sub_label)
    {

        //$label="";
        if($sub_label=='measurements')
        {

            $label="Measurements>>";
            $item=OrderMeasurement::findOrFail($arr['id']);
            $label.=$item->measurement_name.'>>'.$arr['measurement_value'];

        }
        return preg_replace('/\s*>>\s*/', '>>', $label);
    }


}
