<div>
    <x-adminlte-card title="Contact" theme="lightblue" theme-mode="outline" icon="fas fa-lg fa-address-book" removable collapsible>
        @foreach ($contacts as $key => $contact)
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
        @endforeach
        <x-adminlte-button label="Edit contact" class="btn-block" theme="primary" icon="fas fa-pencil-alt"/>
    </x-adminlte-card>
</div>
