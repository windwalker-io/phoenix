# {$controller.item.name.cap$}
{$controller.item.name.lower$}:
    pattern: /{$controller.item.name.lower$}/(id)
    controller: {$controller.item.name.cap$}
    extra:
        layout: {$controller.item.name.lower$}
        active:
            mainmenu: {$controller.list.name.lower$}

# {$controller.list.name.cap$}
{$controller.list.name.lower$}:
    pattern: /{$controller.list.name.lower$}(/page)
    controller: {$controller.list.name.cap$}
    extra:
        layout: {$controller.list.name.lower$}
        active:
            mainmenu: {$controller.list.name.lower$}
