<x-filament-widgets::widget>
    <div class="flex w-full">
        @if($has_image)
        <div class="mr-48 ">
            <x-filament::avatar
                 src="{{ asset('storage/'.$image) }}" alt="description of myimage"
                 size="w-24 h-24"
                 :circular="false"
            />
        </div>
        @endif


    </div>

</x-filament-widgets::widget>
