<x-filament-widgets::widget>

        @if($has_image)
        <div >
            <x-filament::avatar
                 src="{{ asset('storage/'.$image) }}" alt="description of myimage"
                 size="w-12 h-12"
                 :circular="false"
            />
        </div>
        @endif


</x-filament-widgets::widget>
