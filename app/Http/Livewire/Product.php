<?php

namespace App\Http\Livewire;
use App\Models\Product as ProductModel;
use DB;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use phpDocumentor\Reflection\Types\Nullable;
use Str;

class Product extends Component
{
    use WithPagination, WithFileUploads;
    public $name, $description, $quantity, $price, $selected_id, $image, $confirming, $img, $search;
    public $updateMode = false;
    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['destroy'];

    public function render()
    {
        $search = $this->search ?? null;

        return view('livewire.product',[
            'products' => ProductModel::when($search, function($q) use($search){
                $q->where('name', 'LIKE',  '%' . $search . '%');
            })
            ->latest()
            ->paginate(5)
        ]);
    }

    public function confirmDelete($id)
    {
        $this->dispatchBrowserEvent('swal:confirm', [
            'type' => 'warning',
            'title' => 'Are you sure?',
            'text' => '',
            'id' => $id
        ]);
    }

    public function create()
    {
        $this->updateMode = false;
        $this->resetInput();
    }

    private function resetInput()
    {
        $this->name = null;
        $this->description = null;
        $this->price = null;
        $this->quantity = null;
        $this->img = null;
    }

    public function store()
    {
        DB::beginTransaction();
        $this->validate([
            'name' => 'required|min:5|unique:products,name',
            'description' => 'required',
            'quantity' => 'required|numeric',
            'price' => 'required|numeric',
            'img' => 'required|image|mimes:jpg,png,jpeg|max:1024'
        ]);

        $slug_name = Str::slug($this->name);
        $path = $this->img->storeAs('products', $slug_name);

        $product = ProductModel::create([
            'name' => $this->name,
            'slug' => $slug_name,
            'description' => $this->description,
            'price' => $this->price,
            'quantity' => $this->quantity,
            'img_src' => $path
        ]);

        if($product) {
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'success',
                'title' => 'Product added successfully.',
                'text' => '',
            ]);
            // session()->flash('message', 'Product added successfully.');
            DB::commit();
            $this->resetInput();
        }

    }

    public function edit($id)
    {
        $this->resetInput();
        $record = ProductModel::findOrFail($id);
        $this->selected_id = $id;
        $this->name = $record->name;
        $this->description = $record->description;
        $this->price = $record->price;
        $this->quantity = $record->quantity;
        $this->image = $record->img_src ? asset('uploads/'.$record->img_src) : null;
        $this->updateMode = true;
    }

    public function update()
    {
        DB::beginTransaction();
        $this->validate([
            'selected_id' => 'required|numeric',
            'name' => 'required|min:2|unique:products,name,'.(int)$this->selected_id,
            'description' => 'required',
            'price' => 'required|numeric',
            'quantity' => 'required|numeric',
            'img' => isset($this->img) ? 'required|image|mimes:jpg,png,jpeg|max:1024' : 'nullable'
        ]);

        if ($this->selected_id) {
            $slug_name = Str::slug($this->name);

            $path = isset($this->img) ? $this->img->store('products') : null;

            $record = ProductModel::findOrFail($this->selected_id);
                $record->update([
                'name' => $this->name,
                'slug' => $slug_name,
                'description' => $this->description,
                'price' => $this->price,
                'quantity' => $this->quantity,
                'img_src' => isset($this->img) ? $path : $record->img_src
            ]);

            DB::commit();
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'success',
                'title' => 'Product updated successfully.',
                'text' => '',
            ]);
            // session()->flash('message', 'Product updated successfully.');
            $this->resetInput();
            $this->updateMode = false;
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        if ($id) {
            $record = ProductModel::findOrFail($id);
            $record->delete();
            DB::commit();
        }
    }



}
