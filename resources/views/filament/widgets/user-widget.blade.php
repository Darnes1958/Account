<x-filament-widgets::widget>
    <div class="flex w-full">

        <div >
            @if($has_image2)
                <x-filament::avatar
                    src="{{ asset('storage/'.$image2) }}" alt="description of myimage"
                    size="w-12 h-12"
                />
            @endif

        </div>

    </div>

</x-filament-widgets::widget>
