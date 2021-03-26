<div>
    <form enctype="multipart/form-data">
        <input type="hidden" wire:model="selected_id">
        <div class="form-group">
            <label for="exampleInputPassword1">Enter Name</label>
            <input type="text" wire:model="name" class="form-control input-sm"  placeholder="Name">
            @error('name') <span class="error text-danger">{{ $message }}</span> @enderror
        </div>
        <div class="form-group">
            <label>Enter Description</label>
            <textarea wire:model="description" class="form-control input-sm" placeholder="Description"></textarea>
            @error('description') <span class="error text-danger">{{ $message }}</span> @enderror
        </div>
        <div class="form-group">
            <label>Enter Price</label>
            <input type="number" wire:model="price" class="form-control input-sm" placeholder="Price">
            @error('price') <span class="error text-danger">{{ $message }}</span> @enderror
        </div>
        <div class="form-group">
            <label>Enter Quantity</label>
            <input type="number" wire:model="quantity" class="form-control input-sm" placeholder="Quantity">
            @error('quantity') <span class="error text-danger">{{ $message }}</span> @enderror
        </div>
        <div class="form-group">
            @if($image && is_null($img))
                Current Photo:
                <img src="{{ $image }}" width="200">
            @endif
            <br/>
            @if (isset($img))
                Newly Uploaded:
                <img src="{{ $img->temporaryUrl() }}" width="200">
            @endif
            <label>----Upload Image----</label>
            <input type="file" wire:model="img" id="image-{{(function_exists('rand')) ? rand() : random_int(10)}}" placeholder="Image" >
            <div wire:loading wire:target="img">
            <div class="spinner-grow spinner-grow-sm" role="status">
                <span class="sr-only">Uploading.....</span>
            </div>
            </div>
            @error('img') <span class="error text-danger">{{ $message }}</span> @enderror

        </div>
        <button wire:click.prevent="update()" class="btn btn-primary">Update</button>
        <button wire:click.prevent="create()" class="btn btn-success">Add New Product</button>
    </form>
</div>
