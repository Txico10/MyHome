<div>
    <!-- The timeline -->
    <div class="timeline timeline-inverse">
        @foreach ($notification_chunks as $key=>$notification_chunk)
            <!-- timeline time label -->
            <div class="time-label">
                <span class="bg-primary">
                    {{$notification_chunk->first()->created_at->format('d M Y')}}
                </span>
            </div>
            <!-- /.timeline-label -->
            @foreach($notification_chunk as $key => $notification)
                <!-- timeline item -->
                <div>
                    <i class="{{$notification->data['icon']}} bg-primary"></i>

                    <div class="timeline-item">
                        <span class="time"><i class="far fa-clock"></i> {{$notification->created_at->diffForHumans()}}</span>

                        <h3 class="timeline-header"><a href="#">{{$notification->data['title']}}</a> sent you an email</h3>

                        <div class="timeline-body">
                            @if ($notification->read_at)
                                {{$notification->data['text']}}
                            @else
                                {{Str::limit($notification->data['text'], 25, '...')}}
                            @endif
                        </div>
                        <div class="timeline-footer">
                            @if ($notification->read_at)
                                <button class="btn btn-danger btn-sm" wire:click="$emit('deleteNotificationConfirm',{{$notification}})">Delete</button>
                            @else
                                <button class="btn btn-primary btn-sm" wire:click="readNotification({{$notification}})">Read more</button>
                            @endif
                        </div>
                    </div>
                </div>
                <!-- END timeline item -->
            @endforeach
        @endforeach
        <div>
            <i class="far fa-clock bg-gray"></i>
        </div>
    </div>
    <script>
        window.addEventListener('swalNotification::modal', event => {
            Swal.fire({
                position: 'top-end',
                icon: event.detail.icon,
                title: event.detail.title,
                text: event.detail.text,
                showConfirmButton: false,
                timer: 3000
            });
        });

        window.addEventListener('swalNotification:confirm', event => {
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
                    Livewire.emit('deleteNotification', event.detail.id);
                    //console.log(assign);
                }
            });
        });
    </script>
</div>
