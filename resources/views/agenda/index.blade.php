<x-layout title="Agenda" class="agenda">   
    <h2>Agenda</h2>

    @if ($agenda->lineups && count($agenda->lineups) > 0)
    <ul>
        @foreach ($agenda->lineups as $lineup)
        <li>
            <a href="{{ 'agenda/' . $lineup->id . ( $agenda->pagination->page > 1 ? '?page=' . $agenda->pagination->page : '' ) }}">
                @if($lineup->imageDataResponse)
                    <img src="{{ 'https://localhost:7023/image/download/'.$lineup->imageDataResponse->id }}" alt="{{ $lineup->imageDataResponse->alt }}" />
                @else
                    <span class="dummy-img">No image</span>
                @endisset

                <section>
                    <h3>{{ strftime('%A %d %B %Y', (new DateTime($lineup->doors))->getTimestamp()) }}</h3>              

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
        @endforeach
    </ul>

    <x-pagination page="{{ $agenda->pagination->page }}" pageSize="{{ $agenda->pagination->pageSize }}" totalCount="{{ $agenda->pagination->totalCount }}" />
    @endif
</x-layout>