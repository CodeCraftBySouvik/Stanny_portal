<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Supplier;
use App\Models\User;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Invoice;

use Illuminate\Support\Facades\Auth;

class Dashboard extends Component
{
    public $total_suppliers = 0;
    public $total_customers = 0;
    public $total_orders = 0;
    // For Production Team
    public $total_pending = 0;
    public $total_ongoing = 0;
    public $total_delivered = 0;

    public $total_invoice = 0;
    public $monthly_collection = 0;
    public $monthly_expense = 0;
    public $todays_collection = 0;
    public $todays_expense = 0;
    public $user;
    public function mount(){
        $this->user = Auth::guard('admin')->user();
         // Get IDs of self + team (only if not super admin)
    //     $userIds = [$this->user->id];

    //    if (!$this->user->is_super_admin) {
    //     $isTeamLead = User::where('parent_id', $this->user->id)->exists();

    //     if ($isTeamLead) {
    //         $teamIds = User::where('parent_id', $this->user->id)->pluck('id')->toArray();
    //         $userIds = array_merge($userIds, $teamIds); // self + child users
    //     }
    // }

          $userIds = [];

      if ($this->user->is_super_admin) {
          // super admin can see everything → no userIds restriction
          $userIds = User::pluck('id')->toArray();
      } else {
          $userIds = [$this->user->id];
          $isTeamLead = User::where('parent_id', $this->user->id)->exists();

          if ($isTeamLead) {
              $teamIds = User::where('parent_id', $this->user->id)->pluck('id')->toArray();
              $userIds = array_merge($userIds, $teamIds); // self + child users
          }
      }


        $this->total_suppliers = Supplier::count();

        $this->monthly_collection = Payment::whereIn('created_by', $userIds)
        ->whereMonth('created_at', now()->month) // Filter by current month
        ->whereYear('created_at', now()->year)   // Ensure it's the current year
        ->where('payment_for', 'credit')
        ->sum('amount');
        
        $this->monthly_expense = Payment::whereIn('created_by', $userIds)
        ->whereMonth('created_at', now()->month) // Filter by current month
        ->whereYear('created_at', now()->year)   // Ensure it's the current year
        ->where('payment_for', 'debit')
        ->sum('amount');

        $this->todays_collection = Payment::whereIn('created_by', $userIds)
            ->whereDate('created_at', today())  // Filter exactly for today
            ->where('payment_for', 'credit')
            ->sum('amount');

        $this->todays_expense = Payment::whereIn('created_by', $userIds)
            ->whereDate('created_at', today())  // Filter exactly for today
            ->where('payment_for', 'debit')
            ->sum('amount');

        $this->total_customers = User::where('user_type',1)->whereIn('created_by', $userIds)->count();
        if($this->user->designation == 13){
             $this->total_orders = Order::whereHas('items', function($query){
                                      $query->where([
                                        'status' => 'Process',
                                        'tl_status'=> 'Approved',
                                        'admin_status'=> 'Approved',
                                        'assigned_team'=> 'production'
                                      ]);
                                  })->count();
                                 
           $this->total_pending = Order::where('status','Fully Approved By Admin')
                                    ->whereHas('items', function($query){
                                      $query->where([
                                        'status' => 'Process',
                                        'tl_status'=> 'Approved',
                                        'admin_status'=> 'Approved',
                                        'assigned_team'=> 'production'
                                      ]);
                                  })->count();
           $this->total_ongoing = Order::where('status','Received at Production') 
                                    ->whereHas('items', function($query){
                                      $query->where([
                                        'status' => 'Process',
                                        'tl_status'=> 'Approved',
                                        'admin_status'=> 'Approved',
                                        'assigned_team'=> 'production'
                                      ]);
                                  })->count();
           $this->total_delivered = Order::where('status','Fully Delivered By Production') 
                                    ->whereHas('items', function($query){
                                      $query->where([
                                        'status' => 'Process',
                                        'tl_status'=> 'Approved',
                                        'admin_status'=> 'Approved',
                                        'assigned_team'=> 'production'
                                      ]);
                                  })->count();                   
        }else{
            $this->total_orders = Order::whereIn('created_by', $userIds)->count();
             $this->total_pending = 0;
             $this->total_ongoing = 0;
             $this->total_delivered = 0;
        }
        // For Production

        $this->total_invoice = Invoice::whereIn('created_by', $userIds)->count();
    }
    public function render()
    {
        return view('livewire.dashboard');
    }
}
