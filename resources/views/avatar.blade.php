@if(\Illuminate\Support\Facades\Auth::user()->image)
    <x-filament::avatar
        src="{{ asset('storage/'.\Illuminate\Support\Facades\Auth::user()->image) }}" alt="description of myimage"
    />
@endif

@if($compImage)
    <x-filament::avatar
        src="{{ asset('storage/'.$compImage) }}" alt="description "
    />
@endif

