<x-layout title="{{ $lineup->data->title ?? strftime('%A %d %B %Y', (new DateTime($lineup->data->doors))->getTimestamp()) }}" class="detail">   

    <a href="{{ '/agenda' . ($lineup->page > 1 ? '?page=' .  $lineup->page : '') }}">&#11104; overview</a>

    <h2>
        @if($lineup->data->actsCount > 0)
            <span>{{ implode(' + ', array_slice(array_map(fn($act) => $act->name, $lineup->data->acts->data), 0, 3)) }}</span>
            
            @if($lineup->data->actsCount - 3 > 0 )
                <span> + {{ $lineup->data->actsCount - 3 }} more</span>
            @endif
        @else
            <span>Acts to tba ...</span>
        @endif
    </h2>

    @isset($lineup->data->title)
        <p>{{ $lineup->data->title }}</p>
    @endisset

    <h3>{{ strftime('%A %d %B %Y', (new DateTime($lineup->data->doors))->getTimestamp()) }}</h3>

    <ul class="info">
        <li>Doors open at {{ (new DateTime($lineup->data->doors))->format('H:i') }}</li>
        <li>First show at {{ $lineup->data->start->format('H:i') }} </li>
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
                        <img src="{{ 'https://localhost:7023/image/download/'.$act->imageDataResponse->id }}" alt="{{ $act->imageDataResponse->id }}" />
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