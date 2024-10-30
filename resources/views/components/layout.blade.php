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
                <span>Menu</span>
                <span>Close</span>
            </button>
        </div>

        <nav>
            <ul>
                <li @class(['active' => request()->is('agenda*')])>
                    <a href="/agenda">Agenda</a>
                </li>
                <li @class(['active' => request()->is('about*')])>
                    <a href="/about">About</a>
                </li>
                <li @class(['active' => request()->is('contact*')])>
                    <a href="/contact">Contact</a>
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