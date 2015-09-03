
@section('nav')
    <li class="{{ $helper->menu->active('dashboard') }}">
        <a href="#">
            @translate('phoenix.title.admin.dashboard')
        </a>
    </li>
@stop
