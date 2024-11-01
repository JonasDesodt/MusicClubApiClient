@php
    use Carbon\Carbon;
@endphp

<x-layout title="{{ __('layout.agenda') }}" class="agenda">   
    <h2>{{ __('layout.agenda') }}</h2>

    <form method="get">
        <div>
            <label for="search">Search</label>

            @php
                $search = request()->query('search');
            @endphp

            @if(isset($search))
                <input type="text" id="search" name="search" value="{{ $search }}"/>
            @else
                <input type="text" id="search" name="search"/>
            @endif
        </div>

        <ul class="buttons">
            <li><button type="submit">{{ __('agenda.search') }}</button></li> <!-- TODO ==> add security -->

            <li><a href="{{ route('agenda.index', ['locale' => app()->getLocale()]) }}">{{ __('agenda.clear') }}</a></li>
        </ul>
    </form>

    @if ($agenda->lineups && count($agenda->lineups) > 0)
        @foreach ($agenda->lineups as $lineup)
            <ul class="data">
                <li>
                <a href="{{ route('agenda.detail', ['locale' => app()->getLocale(), 'id' => $lineup->id ] + request()->query()) }}">
                        @if($lineup->imageDataResponse)
                            <img src="{{ 'https://localhost:7023/image/download/'.$lineup->imageDataResponse->id }}" alt="{{ $lineup->imageDataResponse->alt }}" />
                        @else
                            <span class="dummy-img">No image</span>
                        @endisset

                        <section>
                            <h3>{{ ((Carbon::parse($lineup->doors))->setTimeZone('Europe/Brussels'))->locale(app()->getLocale())->translatedFormat('l, j F Y') }}</h3>

                            @if($lineup->title)
                                <p>{{ $lineup->title }}</p>
                            @endif

                            @if(count($lineup->acts) > 0)
                                <ul>
                                    @foreach ($lineup->acts as $act)
                                        <li>{{ $act->name }}</li>
                                    @endforeach
                                </ul>

                                @if($lineup->actsCount > count($lineup->acts))                    
                                    <p>+ {{ $lineup->actsCount - count($lineup->acts) }} more</p>                                     
                                @endif
                            @endif
                       </section>
                    </a>
                </li>
            </ul>
        @endforeach

        <x-pagination page="{{ $agenda->pagination->page }}" pageSize="{{ $agenda->pagination->pageSize }}" totalCount="{{ $agenda->pagination->totalCount }}" />
    @else
        <p>{{ __('agenda.no_results')}}</p>
    @endif


</x-layout>