{{-- Part of phoenix project. --}}

<div class="navbar navbar-default navbar-fixed-top">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="{{ $uri->path }}">Windwalker Phoenix</a>
        </div>
        <div class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
                @section('nav')
                    @include('_global.{$package.name.lower$}.widget.mainmenu')
                @show
            </ul>
            <ul class="nav navbar-nav navbar-right">
                {{-- Add right menu here --}}
            </ul>
        </div>
        <!--/.nav-collapse -->
    </div>
</div>
