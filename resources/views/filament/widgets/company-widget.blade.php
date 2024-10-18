<x-filament-widgets::widget>
    <div class="flex w-full">

        <div class="mr-48 ">
            <x-filament::avatar
                 src="{{ asset('storage/'.$image) }}" alt="description of myimage"
                 size="w-24 h-24"
                 :circular="false"
            />
        </div>


    </div>

</x-filament-widgets::widget>
