<?php

namespace App\Http\Livewire\Product;


use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;
use App\Models\Fabric;
use Illuminate\Http\Request;
use App\Helpers\Helper;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\FabricsImport;
use App\Exports\FabricsExport;
use App\Exports\SampleFabricExport;



class FabricIndex extends Component
{
    use WithFileUploads;
    use WithPagination;
    public  $image, $title, $status = 1, $fabricId,$threshold_price;
    public $search = '';
    public $file;
    public $processedFileHash = null; // Store the hash of the last processed file
    protected $paginationTheme = 'bootstrap'; 
    
    public function confirmDelete($id){
        $this->dispatch('showDeleteConfirm',['itemId' => $id]);
    }
    // public function import()
    // {
    //     $this->validate([
    //         'file' => 'required|mimes:xlsx,csv|max:2048',
    //     ]);
    
    //     try {
    //         \Maatwebsite\Excel\Facades\Excel::import(new FabricsImport, $this->file);
    
    //         if (session()->has('duplicate_fabrics')) {
    //             session()->flash('error', 'These fabrics already exist: ' . session('duplicate_fabrics'));
    //         } else {
    //             session()->flash('success', 'File imported successfully.');
    //         }
    //     } catch (\Exception $e) {
    //         session()->flash('error', 'Error importing file: ' . $e->getMessage());
    //     }
    
    //     $this->reset('file');
    // }
    public function import()
    {
        $this->validate([
            'file' => 'required|mimes:xlsx,csv|max:2048',
        ]);
    
        try {
            $import = new FabricsImport();
            \Maatwebsite\Excel\Facades\Excel::import($import, $this->file);
    
            // Get the error message from the import process
            $error = $import->getDuplicateError();
    
            if ($error) {
                session()->flash('error', $error);
            } else {
                session()->flash('success', 'File imported successfully.');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Error importing file: ' . $e->getMessage());
        }
    
        $this->reset('file');
    }
    
    
    // Export Fabrics
    public function export()
    {
        return Excel::download(new FabricsExport(), 'fabrics.csv');
    }

    public function sampleExport()
    {
        return Excel::download(new SampleFabricExport(), 'sample_fabrics.csv');
    }
    public function store()
    {
        $this->validate([
            'title' => [
                'required',
                'string',
                'max:255',
                'unique:fabrics,title', 
            ],
            'image' => [
                'nullable',
                'mimes:jpg,png,jpeg,gif',
            ],
            'threshold_price' => [
                'required',
                'numeric',
                'min:1',
            ],
        ]);

        $absolutePath = null;
        
        if($this->image){
            $imagePath = $this->image->store("fabrics",'public');
            $absolutePath = "storage/".$imagePath;
        }
        

        Fabric::create([
            'collection_id' => 1,
            'title' => $this->title,
            'threshold_price' => $this->threshold_price,
            'image' =>  $absolutePath,
            'status' => $this->status,
        ]);
        
        $this->title = null;
        $this->image = null;
        $this->threshold_price = null;
        // Refresh the fabrics list for the current product
        session()->flash('message', 'Fabric created successfully!');
        $this->resetPage(); // Refresh the list
    }

    // Edit Fabric
    public function edit($id)
    {
        $fabric = Fabric::findOrFail($id);
        $this->fabricId = $fabric->id;
        $this->title = $fabric->title;
        $this->threshold_price = $fabric->threshold_price;
        
        $this->image = $fabric->image;
        $this->status = $fabric->status;
    }
    // Update Fabric
    public function update()
    {
        $this->validate([
            'title' => [
                'required',
                'string',
                'max:255',
                Rule::unique('fabrics', 'title')->ignore($this->fabricId), 
            ],
            'image' => [
                'nullable',
                'mimes:jpg,png,jpeg,gif',
            ],
            'threshold_price' => [
                'required',
                'numeric',
                'min:1',
            ],

        ]);
        
        $fabric = Fabric::findOrFail($this->fabricId);
        $imagePath = $fabric->image;
        if ($this->image) {
            // Store new image
            $newImagePath = $this->image->store("fabrics", 'public');
            $imagePath = "storage/" . $newImagePath;
        }
        $fabric->update([
            'title' => $this->title,
            'threshold_price' => $this->threshold_price,
            'image' => $imagePath,
            'status' => $this->status,
        ]);
        
        $this->title = null;
        $this->image = null;
        $this->threshold_price = null;
        
        session()->flash('message', 'Fabric updated successfully!');
       $this->resetFields();
    }

    // Delete Fabric
    public function destroy($id)
    {
        Fabric::findOrFail($id)->delete();
        session()->flash('message', 'Fabric deleted successfully!');
        $this->fabrics = Fabric::orderBy('id', 'desc')->get();
    }

    // Toggle Status
    public function toggleStatus($id)
    {
        $fabric = Fabric::findOrFail($id);
        $fabric->update(['status' => !$fabric->status]);
        session()->flash('message', 'Fabric status updated successfully!');
    }

    public function downloadFabricCSV()
    {
        $filePath = public_path('assets/csv/sample_fabrics.csv'); // Correct file path

        if (file_exists($filePath)) {
            return response()->download($filePath);
        } else {
            session()->flash('error', 'File not found.');
        }
    }

    public function resetFields(){
        $this->reset(['fabricId','title','image','threshold_price']);
    }
    // Render Method with Search and Pagination
    public function render()
    {
        $fabrics = Fabric::where('title', 'like', "%{$this->search}%")
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('livewire.product.fabric-index', [
            'fabrics' => $fabrics,
        ]);
    }
}