<div>
    <x-adminlte-card title="Addresses" theme="lightblue" theme-mode="outline" icon="fas fa-lg fa-map-marker-alt" removable collapsible>
        @foreach ($model->addresses as $key => $address)
        @if($key>0 && $key <$model->addresses->count())
            <hr>
        @endif
        <strong><i class="fas fa-globe mr-1"></i> <a href="javascript:void(0)" wire:click="$emit('editAddress', {{$address}})"">{{ucfirst($address->type)}} address</a></strong>
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
        @endforeach
        <button class="btn btn-block btn-primary" wire:click="$emit('createAddress')"><i class="fas fa-lg fa-map-marker-alt"></i> New address</button>
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
