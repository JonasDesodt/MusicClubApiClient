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
            <h1><a href="/">Music Club</a></h1>

            <button type="button" class="toggle-button-menu-open">
                <span>{{ __('layout.menu') }}</span>
                <span>{{ __('layout.close') }}</span>
            </button>
        </div>

        <nav>
            <ul>
                <li @class(['active' => request()->is('agenda*')])>
                    <a href="/agenda">{{ __('layout.agenda') }}</a>
                </li>
                <li @class(['active' => request()->is('about*')])>
                    <a href="/about">{{ __('layout.about') }}</a>
                </li>
                <li @class(['active' => request()->is('contact*')])>
                    <a href="/contact">{{ __('layout.contact') }}</a>
                </li>
            </ul>

            <ul>
                @if(app()->getLocale() == 'nl')
                    <li><a href="{{ route('set-locale', 'en') }}">English</a></li>
                @else
                    <li><a href="{{ route('set-locale', 'nl') }}">Nederlands</a></li>
                @endif
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