@php
    use Carbon\Carbon;

    $search = request()->query('search');
    $from = request()->query('from');
    $until = request()->query('until');
@endphp

<x-layout title="{{ __('layout.agenda') }}" class="agenda">
    <h2>{{ __('layout.agenda') }}</h2>

    <form method="get" @class(['show' => count(old()) > 0 || isset($search) ||  isset($from) || isset($until), 'toggle-container']) >
        <div>
            <div>
                <label for="search">{{ __('agenda.search_term') }}</label>

                <input type="text" id="search" name="search" value="{{ old('search') ?? $search }}"/>

                @error('search')
                    <p>{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="from">{{ __('agenda.from')}}

                    <span>{{ __('agenda.time_zone') }}: Europe/Brussels</span>
                </label>

                <input type="datetime-local" id="from" name="from" value="{{ old('from') ?? $from ?? (isset($agenda->from) ? ((Carbon::parse($agenda->from))->setTimeZone('Europe/Brussels'))->format('Y-m-d H:i') : null ) }}" />

                @error('from')
                    <p>{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="until">{{ __('agenda.until' )}}
                
                    <span>{{ __('agenda.time_zone') }}: Europe/Brussels</span>
                </label>

                <input type="datetime-local" id="until" name="until" value="{{ old('until') ?? $until }}" />
                
                @error('until')
                    <p>{{ $message }}</p>
                @enderror
            </div>
        </div>

        <ul class="buttons">
            <li><button type="submit">{{ __('agenda.search') }}</button></li> <!-- TODO ==> add security -->

            <li><a href="{{ route('agenda.index', ['locale' => app()->getLocale()]) }}">{{ __('agenda.clear_close') }}</a></li>
        </ul>

        <button type="button" class="toggle-button-show">Filters</button>
    </form>

    @if ($agenda->lineups && count($agenda->lineups) > 0)
        <ul class="data">
            @foreach ($agenda->lineups as $lineup)
                <li @class(['archive' => Carbon::parse($lineup->doors) < gmdate('Y-m-d')])>
                    <a href="{{ route('agenda.detail', ['locale' => app()->getLocale(), 'id' => $lineup->id ] + request()->query()) }}">
                            @if($lineup->image)
                                <img src="{{ 'https://localhost:7023/image/download/'.$lineup->image->id }}" alt="{{ $lineup->image->alt }}" />
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

                                    @if($lineup->actsTotalCount > count($lineup->acts))
                                        <p>+ {{ $lineup->actsTotalCount - count($lineup->acts) }} more</p>
                                    @endif
                                @endif
                        </section>
                    </a>
                </li>
            @endforeach
        </ul>
        
        <x-pagination page="{{ $agenda->page }}" pageSize="{{ $agenda->pageSize }}" totalCount="{{ $agenda->totalCount }}" />
    @else
        <p>{{ __('agenda.no_results')}}</p>
    @endif


</x-layout>