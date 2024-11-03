@php
    use Carbon\Carbon;

    $queryString = request()->getQueryString();
@endphp

<x-layout title="{{ $lineup->data->title ?? strftime('%A %d %B %Y', (new DateTime($lineup->data->doors))->getTimestamp()) }}" class="detail">   
    <a href="{{ route('agenda.index', ['locale' => app()->getLocale()]) . (isset($queryString) ? '?' . $queryString : '') }}">
        &laquo; {{ __('app.overview') }}
    </a>

    <h2>
        @if($lineup->data->actsCount > 0)
            <span>{{ implode(' + ', array_slice(array_map(fn($act) => $act->name, $lineup->data->acts->data), 0, 3)) }}</span>
            
            @if($lineup->data->actsCount - 3 > 0 )
                <span> + {{ $lineup->data->actsCount - 3 }} {{ __('app.more') }}</span>
            @endif
        @else
            <span>{{ __('detail.acts_tba')}}</span>
        @endif
    </h2>

    @isset($lineup->data->title)
        <p>{{ $lineup->data->title }}</p>
    @endisset

    <h3>{{ ((Carbon::parse($lineup->data->doors))->setTimeZone('Europe/Brussels'))->locale(app()->getLocale())->translatedFormat('l, j F Y') }}</h3>
   
    <ul class="info">
        <li>{{ __('detail.doors_open_at') }} {{ ((Carbon::parse($lineup->data->doors))->setTimeZone('Europe/Brussels'))->format('H:i') }}</li>
        <li>{{ __('detail.first_show_at') }} {{ ((Carbon::parse($lineup->data->start))->setTimeZone('Europe/Brussels'))->format('H:i') }} </li>
    </ul>

    @isset($lineup->data->imageDataResponse)
        <img src="{{ 'https://localhost:7023/image/download/'.$lineup->data->imageDataResponse->id }}" alt="{{ $lineup->data->imageDataResponse->alt }}" />
    @endisset

    @isset($lineup->data->description)
        <p>{{ $lineup->data->description }}</p>
    @endisset

    @if(count($lineup->data->acts->data) > 0)
        <ul>
            @foreach ($lineup->data->acts->data as $act)
                <li>
                    @isset($act->imageDataResponse)
                        <img src="{{ 'https://localhost:7023/image/download/'.$act->imageDataResponse->id }}" alt="{{ $act->imageDataResponse->alt }}" />
                    @else
                        <span class="dummy-img">no image</span>
                    @endisset

                    <section>
                        <h3>{{ $act->name }}</h3>

                        @isset($act->description)
                            <p>{{ $act->description }}</p>
                        @endisset
                    </section>
                </li>
            @endforeach
        </ul>
    @endif
</x-layout>