<div id="updateFocus">
    <div id="update">
        @if(!$updateMode)
            @include('livewire.media-form-create')
        @else
            @include('livewire.media-form-update')
        @endif
    </div>

    <div>
        <table class="table border-none mt-3">

            <tbody>
                @foreach($media as $value)
                <tr>
                    <td>
                        <img src="{{  Storage::url($value->icon) }}" alt="{{ $value->name }}" class="h-5 w-5"/>
                    </td>
                        <td>
                        <a class="text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition"
                            href="{{ str_replace('#', $value->pivot->server_of_medium, $value->url_structure)  . $value->pivot->user_on_medium }}"
                            target="_blank">
                            @if($value->pivot->server_of_medium)
                               {{ Str::of('@ '. $value->pivot->user_on_medium . '@ '. $value->pivot->server_of_medium)->limit(43) }}
                            @else
                                {{ Str::of('@ '. $value->pivot->user_on_medium)->limit(43) }}
                            @endif
                        </a>
                        </td>
                    <td>
                        <button wire:click.prevent="edit({{ $value->pivot->id }})"  class="inline-flex items-center px-2 py-1 text-xs text-gray-500 uppercase tracking-widest hover:text-gray-900 focus:outline-none focus:border-gray-300 focus:ring focus:ring-gray-200 focus:rounded-sm active:text-gray-900 disabled:opacity-25 transition"><x-icon name="pencil" class="w-4 h-4" /></button>
                        <button wire:click.prevent="delete({{ $value->pivot->id }})" class="inline-flex items-center px-2 py-1 text-xs text-gray-500 uppercase tracking-widest hover:text-gray-900 focus:outline-none focus:border-gray-300 focus:ring focus:ring-gray-200 focus:rounded-sm active:text-gray-900 disabled:opacity-25 transition"><x-icon name="trash" class="w-4 h-4" /></button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>


<script type="text/javascript">
  window.addEventListener('contentChanged', e => {
        $("#selected-focus").attr('class', 'text-gray-500 uppercase');
  })
</script>



</div>