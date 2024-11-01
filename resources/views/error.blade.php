<x-layout title="{{ __('error.error')}}" class="error">   

<!-- bug: if on index page, there is a paged removed (by for example by using the cms) the return button will possible return to another error page -->
@if($return_url != url()->current() )
    <a href="{{ $return_url }}">&laquo; {{ __('app.return') }} </a>
@endif

    <h2>{{ __('error.data_fetch_failed') }}</h2>
</x-layout>s