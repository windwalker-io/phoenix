{{-- Part of Windwalker project. --}}
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{{ \Phoenix\Html\Document::getPageTitle() }}}</title>

    <link rel="shortcut icon" type="image/x-icon" href="{{ $uri['media.path'] }}images/favicon.ico" />
    <meta name="generator" content="Windwalker Framework" />
    @yield('meta')

    {{ \Phoenix\Asset\Asset::renderStyles(true); }}
    <link rel="stylesheet" href="{{ $uri['media.path'] }}phoenix/css/phoenix.css?{{ \Phoenix\Asset\Asset::getVersion() }}" />
    @yield('style')

    {{ \Phoenix\Asset\Asset::renderScripts(true); }}
    @yield('script')

</head>
<body>

@yield('body', 'Body')

</body>
</html>
