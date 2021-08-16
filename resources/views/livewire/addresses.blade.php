<div>
    <x-adminlte-card title="Addresses" theme="lightblue" theme-mode="outline" icon="fas fa-lg fa-map-marker-alt" removable collapsible>
        @foreach ($model->addresses as $key => $address)
        @if($key>0 && $key <$model->addresses->count())
            <hr>
        @endif
        <strong><i class="fas fa-globe mr-1"></i> {{ucfirst($address->type)}} address</strong>
        <p class="text-muted">
            {{$address->number}}
            {{$address->street}},
            @if(!is_null($address->suite))
            App: {{$address->suite}},
            @endif
            {{$address->city}},
            {{$address->region}},
            {{$address->country}},
            {{$address->postcode}}
        </p>
        <p>
            <a href="javascript:void(0)" wire:click="$emit('editAddress', {{$address}})"><span class="badge bg-primary"><i class="fas fa-xs fa-pencil-alt"></i> Edit</span></a>
            @if (strcmp($address->type, 'primary')!=0)
            <a href="javascript:void(0)" wire:click="$emit('deleteAddressConfirm', {{$address->id}})"><span class="badge bg-danger"><i class="fas fa-xs fa-trash-alt"></i> Delete</span></a>
            @endif

        </p>
        @endforeach
        @if($model->addresses->count()<3)
        <button class="btn btn-block btn-primary" wire:click="$emit('createAddress')"><i class="fas fa-lg fa-map-marker-alt"></i> New address</button>
        @endif
    </x-adminlte-card>
    <x-adminlte-modal id="addressmodal" title="Edit Address" size="lg" theme="lightblue" icon="fas fa-lg fa-map-marker-alt" static-backdrop scrollable>
        @livewire('addresses-form', ['model' => $model])
        {{-- Footer button --}}
        <x-slot name="footerSlot">
            <x-adminlte-button class="mr-auto" theme="danger" label="Dismiss" data-dismiss="modal"/>
            <x-adminlte-button theme="success" label="Save" onclick="saveform()" />
        </x-slot>
    </x-adminlte-modal>


    <script>

        window.addEventListener('alert', event => {
            toastr[event.detail.type](event.detail.message);
        });

        window.addEventListener('swal:modal', event => {
            Swal.fire(
                event.detail.title,
                event.detail.text,
                event.detail.icon,
            );
        });

        window.addEventListener('swal:confirm', event => {
            Swal.fire({
                title: event.detail.title,
                text: event.detail.text,
                icon: event.detail.icon,
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                    //if user clicks on delete
                if (result.isConfirmed) {
                        // calling destroy method to delete
                    Livewire.emit('deleteAddress', event.detail.id);
                    //console.log(assign);
                }
            });
        });

        window.addEventListener('openAddressModal', event => {
            $("#addressmodal").modal('show');
        });

        window.addEventListener('closeAddressModal', event => {
            $("#addressmodal").modal('hide');
        });

        function saveform() {
            Livewire.emit('saveAddressForm')
        }

    </script>
</div>
