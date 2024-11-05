@php
    use Carbon\Carbon;

    $queryString = request()->getQueryString();
@endphp

<x-layout title="{{ $lineup->title ?? strftime('%A %d %B %Y', (new DateTime($lineup->data->doors))->getTimestamp()) }}" class="detail">   
    <a href="{{ route('agenda.index', ['locale' => app()->getLocale()]) . (isset($queryString) ? '?' . $queryString : '') }}">
        &laquo; {{ __('app.overview') }}
    </a>

    <h2>
        @if($lineup->actsTotalCount > 0)
            <span>{{ implode(' + ', array_slice(array_map(fn($act) => $act->name, $lineup->acts), 0, 3)) }}</span>
            
            @if($lineup->actsTotalCount - 3 > 0 )
                <span> + {{ $lineup->actsTotalCount - 3 }} {{ __('app.more') }}</span>
            @endif
        @else
            <span>{{ __('detail.acts_tba')}}</span>
        @endif
    </h2>

    @isset($lineup->title)
        <p>{{ $lineup->title }}</p>
    @endisset

    <h3>{{ ((Carbon::parse($lineup->doors))->setTimeZone('Europe/Brussels'))->locale(app()->getLocale())->translatedFormat('l, j F Y') }}</h3>
   
    <ul class="info">
        <li>{{ __('detail.doors_open_at') }} {{ ((Carbon::parse($lineup->doors))->setTimeZone('Europe/Brussels'))->format('H:i') }}</li>
        <li>{{ __('detail.first_show_at') }} {{ ((Carbon::parse($lineup->start))->setTimeZone('Europe/Brussels'))->format('H:i') }} </li>
    </ul>

    @isset($lineup->image)
        <img src="{{ 'https://localhost:7023/image/download/'.$lineup->image->id }}" alt="{{ $lineup->image->alt }}" />
    @endisset

    @isset($lineup->description)
        <p>{{ $lineup->description }}</p>
    @endisset

    @if(count($lineup->acts) > 0)
        <ul>
            @foreach ($lineup->acts as $act)
                <li>
                    @isset($act->image)
                        <img src="{{ 'https://localhost:7023/image/download/'.$act->image->id }}" alt="{{ $act->image->alt }}" />
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