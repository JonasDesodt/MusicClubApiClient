<x-layout title="{{ __('error.error')}}" class="error">   

@if($return_url != url()->current() )
    <a href="{{ $return_url }}">&laquo; {{ __('app.return') }} </a>
@endif

    <h2>{{ __('error.data_fetch_failed') }}</h2>
</x-layout>