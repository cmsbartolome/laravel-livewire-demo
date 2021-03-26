<div>
    <form>
        <div class="form-group">
            <label for="exampleInputPassword1">Enter Name</label>
            <input type="text" wire:model="name" class="form-control input-sm"  placeholder="Name">
        </div>
        <div class="form-group">
            <label>Enter Description</label>
            <textarea wire:model="description" class="form-control input-sm" placeholder="Description"></textarea>
        </div>
        <div class="form-group">
            <label>Enter Price</label>
            <input type="number" wire:model="price" class="form-control input-sm" placeholder="Price">
        </div>
        <div class="form-group">
            <label>Enter Quantity</label>
            <input type="number" wire:model="quantity" class="form-control input-sm" placeholder="Quantity">
        </div>
        <div class="form-group">
            @if ($img)
                Photo Preview:
                <img src="{{ asset($img->temporaryUrl()) }}" width="200">
            @endif
            <label>----Upload Image----</label>
            <input type="file" wire:model="img" id="image-{{(function_exists('rand')) ? rand() : random_int(10)}}"  placeholder="Image">
            <div wire:loading wire:target="img">
                <div class="spinner-grow spinner-grow-sm" role="status">
                    <span class="sr-only">Uploading.....</span>
                </div>
            </div>
        </div>
        <button wire:click.prevent="store()" class="btn btn-primary">Save</button>
    </form>
</div>
