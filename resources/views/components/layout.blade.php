@php
    $currentLocale = app()->getLocale();
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">

    <title>{{ $title ?? 'Music Club' }}</title>
</head>
<body class="toggle-container">
    <header>
        <div>
            <h1><a href="{{ route('agenda.index', ['locale' => app()->getLocale()]) }}">Music Club</a></h1>

            <button type="button" class="toggle-button-menu-open">
                <span>{{ __('layout.menu') }}</span>
                <span>{{ __('layout.close') }}</span>
            </button>
        </div>

        <nav>
            <ul>
                <li @class(['is-active' => request()->is(app()->getLocale() . '/agenda*')])>
                    <a href="{{ route('agenda.index', ['locale' => $currentLocale]) }}">{{ __('layout.agenda') }}</a>
                </li>
                <li @class(['is-active' => request()->is(app()->getLocale() . '/about*')])>
                    <a href="{{ route('about.index', ['locale' => $currentLocale]) }}">{{ __('layout.about') }}</a>
                </li>
                <li @class(['is-active' => request()->is(app()->getLocale() . '/contact*')])>
                    <a href="{{ route('contact.index', ['locale' => $currentLocale]) }}">{{ __('layout.contact') }}</a>
                </li>
            </ul>

            <ul>
                @php
                    $newLocale = app()->getLocale() == 'nl' ? 'en' : 'nl';
                @endphp

                <li>
                    <a href="{{ preg_replace("/^\/$currentLocale\//", "/$newLocale/", request()->getRequestUri()) }}">
                        {{ $newLocale == 'en' ? 'English' : 'Nederlands' }}
                    </a>
                </li>
            </ul>
        </nav>
    </header>

    <main {{ $attributes->only('class')}}>
        {{ $slot }}
    </main>

    <footer>
        <p>&copy 2024 {{ date('Y') != 2024 ? '- '.date('Y') : '' }} Music Club</p>
    </footer>

    <script src="{{ asset('js/scripts.js') }}" ></script>
</body>
</html>