<?php

namespace App\Http\Livewire\Staff;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Branch;

class MasterBranch extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap'; 

    public $branchId;
    public $name, $email, $mobile, $whatsapp, $city, $address;
    public $search = '';

    protected $rules = [
        'name' => 'required|unique:branches,name',
        'email'=> 'required|email|unique:branches,email',
        'mobile' => 'required|numeric|unique:branches,mobile',
        'whatsapp' => 'required|numeric|unique:branches,whatsapp',
        'city' => 'required',
        'address' => 'required',
    ];
    public function FindBranch($keywords){
        $this->search = $keywords;
    }
    public function updatingSearch()
    {
        $this->resetPage(); // Reset pagination on search
    }

    public function resetFields()
    {
        $this->search = '';
        $this->branchId = null;
        $this->name = '';
        $this->email = '';
        $this->mobile = '';
        $this->whatsapp = '';
        $this->city = '';
        $this->address = '';
    }

    public function storeBranch()
    {
        $this->validate();     
        Branch::create([
            'name' => $this->name,
            'email' => $this->email,
            'mobile' => $this->mobile,
            'whatsapp' => $this->whatsapp,
            'city' => $this->city,
            'address' => $this->address,
        ]);

        session()->flash('message', 'Branch Created Successfully');
        $this->resetFields();
    }

    public function edit($id)
    {
        $branch = Branch::findOrFail($id);
        $this->branchId = $id;
        $this->name = $branch->name;
        $this->email = $branch->email;
        $this->mobile = $branch->mobile;
        $this->whatsapp = $branch->whatsapp;
        $this->city = $branch->city;
        $this->address = $branch->address;
    }

    public function updateBranch()
    {
        $this->validate([
            'name' => 'required|unique:branches,name,' . $this->branchId,
            'email'=> 'required|email|unique:branches,email,' . $this->branchId,
            'mobile' => 'required|numeric|unique:branches,mobile,' . $this->branchId,
            'whatsapp' => 'required|numeric|unique:branches,whatsapp,' . $this->branchId,
            'city' => 'required',
            'address' => 'required',
        ]);

        Branch::findOrFail($this->branchId)->update([
            'name' => $this->name,
            'email' => $this->email,
            'mobile' => $this->mobile,
            'whatsapp' => $this->whatsapp,
            'city' => $this->city,
            'address' => $this->address,
        ]);

        session()->flash('message', 'Branch Updated Successfully');
        $this->resetFields();
    }

    public function render()
    {
        $branchNames = Branch::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->orderBy('name', 'desc')
            ->paginate(10);

        return view('livewire.staff.master-branch', compact('branchNames'));
    }
}
