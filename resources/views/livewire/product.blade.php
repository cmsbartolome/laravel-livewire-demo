<div>
    <div class="row">
        <div class="col-md-12">
        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <a href="#" class="close" data-dismiss="alert">&times;</a>
                <p>Please check the following error</p>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session()->has('message'))
            <div class="alert alert-success">
                <a href="#" class="close" data-dismiss="alert">&times;</a>
                {{ session('message') }}
            </div>
        @endif
    </div>
    </div>
    <div class="row">
        <div class="col-md-3">
            @include( $updateMode ? 'livewire.update' : 'livewire.create')
        </div>

        <div class="col-md-9">
            <input type="text" wire:model="search" class="form-control input-sm mt-2"  placeholder="Search">
            <p>Search for:  <b>{{ $search ?? '' }}</b></p>
            <table class="table table-striped" style="margin-top:20px;">
                <tr>
                    <td>NO</td>
                    <td></td>
                    <td>NAME</td>
                    <td>DESCRIPTION</td>
                    <td>QUANTITY</td>
                    <td>PRICE</td>
                    <td>ACTION</td>
                </tr>
                @php
                    $count = ($products->currentpage()-1)* $products->perpage() + 1;
                @endphp
                @foreach($products as $row)
                    <?php
                        $image = ($row->img_src) ? asset('uploads/'.$row->img_src) : 'https://via.placeholder.com/100x100';
                    ?>
                    <tr>
                        <td>{{$count++}}</td>
                        <td><img src="{{ $image  }}" width="150"></td>
                        <td>{{ (string) $row->name}}</td>
                        <td>{{ $row->description}}</td>
                        <td>{{ (int) $row->quantity}}</td>
                        <td>{{ number_format($row->price) }}</td>
                        <td>
                            <button wire:click="edit({{$row->id}})" class="btn btn-sm btn-primary py-0">Edit</button> |

{{--                            @if($confirming===$row->id)--}}
{{--                                <button wire:click="destroy({{ $row->id }})"--}}
{{--                                        class="btn btn-sm btn-danger py-0">Sure?</button>--}}
{{--                            @else--}}
                                <button wire:click="confirmDelete({{ $row->id }})"
                                        class="btn btn-sm btn-dark py-0">Delete</button>
{{--                            @endif--}}
                        </td>
                    </tr>
                @endforeach
            </table>
            {{ $products->links() }}
        </div>

    </div>

</div>

