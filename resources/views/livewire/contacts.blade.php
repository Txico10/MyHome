<div>
    <x-adminlte-card title="Contact" theme="lightblue" theme-mode="outline" icon="fas fa-lg fa-address-book" removable collapsible>
        @foreach ($model->contacts as $key => $contact)
            @if($key>0 && $key < $contacts->count())
                <hr>
            @endif
            @if(strcmp($contact->type, 'email')==0)
                <strong><i class="fas fa-at mr-1"></i> Email</strong>
            @elseif (strcmp($contact->type, 'phone')==0)
                <strong><i class="fas fa-phone-alt mr-1"></i> Phone</strong>
            @elseif (strcmp($contact->type, 'mobile')==0)
                <strong><i class="fas fa-mobile-alt mr-1"></i> Mobile</strong>
            @else
                <strong><i class="fas fa-globe mr-1"></i> Other</strong>
            @endif
            <p class="text-muted">{{__($contact->description)}} <br> {{__(ucfirst($contact->priority))}} contact {{is_null($contact->name)?'':'- '.__($contact->name)}}</p>
            <p>
                <a href="javascript:void(0)" wire:click="$emit('editContact', {{$contact}})"><span class="badge bg-primary"><i class="fas fa-xs fa-pencil-alt"></i> Edit</span></a>
                <a href="javascript:void(0)" wire:click="$emit('deleteContactConfirm', {{$contact->id}})"><span class="badge bg-danger"><i class="fas fa-xs     fa-trash-alt"></i> Delete</span></a>
            </p>
        @endforeach
        <button class="btn btn-block btn-primary" wire:click="$emit('createContact')"> New Contact</button>
    </x-adminlte-card>
    <x-adminlte-modal id="contactmodal" title="Edit Contact" theme="lightblue" icon="fas fa-lg fa-address-book" static-backdrop scrollable>
        @livewire('contacts-form', ['model' => $model])
        {{-- Footer button --}}
        <x-slot name="footerSlot">
            <x-adminlte-button class="mr-auto" theme="danger" label="Dismiss" data-dismiss="modal"/>
            <x-adminlte-button theme="success" label="Save" onclick="saveContactForm()" />
        </x-slot>
    </x-adminlte-modal>
</div>

<script>
    window.addEventListener('swalContact:modal', event => {
        Swal.fire(
            event.detail.title,
            event.detail.text,
            event.detail.icon,
        );
    });
    window.addEventListener('swalContact:confirm', event => {
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
                Livewire.emit('deleteContact', event.detail.id);
                //console.log(assign);
            }
        });
    });
    window.addEventListener('openContactModal', event => {
        $("#contactmodal").modal('show');
    });
    window.addEventListener('closeContactModal', event => {
        $("#contactmodal").modal('hide');
    });
    function saveContactForm() {
        Livewire.emit('saveContactForm')
    }
</script>
