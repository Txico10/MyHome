<div>
    <div class="row">
        <div class="col-md-6">

            <div class="row">
                @foreach($users as $index => $user)
                    <div class="col-sm-9">
                        <div class="form-group">
                            <label class="text-lightblue" for="user_name{{$index}}">Name</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text text-lightblue"><i class="fas fa-fw fa-user"></i></span>
                                </div>
                                <input type="text" name="user_name{{$index}}" id="user_name{{$index}}" class="form-control" wire:model.lazy="users.{{$index}}.name" placeholder="Enter Lessee name">
                            </div>
                            @error('users.'.$index.'.name')
                                <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label class="text-lightblue" for="user_email{{$index}}">Email</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text text-lightblue"><i class="fas fa-fw fa-at"></i></span>
                                </div>
                                <input type="email" name="user_email{{$index}}" id="user_email{{$index}}" class="form-control" wire:model.lazy="users.{{$index}}.email" placeholder="Enter Lessee email">
                            </div>
                            @error('users'.$index.'email')
                                <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-3">
                        @if($index < 1)
                            <button class="btn btn-primary" wire:click.prevent="addUser()">Add row</button>
                        @else
                            <button class="btn btn-danger" wire:click.prevent="removeUser({{$index}})" >Delete</button>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>

</div>
