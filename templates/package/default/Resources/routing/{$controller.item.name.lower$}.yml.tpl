# {$controller.item.name.cap$}
{$controller.item.name.lower$}:
    pattern: /{$controller.item.name.lower$}(/id)
    controller: {$controller.item.name.cap$}
    variables:
        layout: {$controller.item.name.lower$}

# {$controller.list.name.cap$}
{$controller.list.name.lower$}:
    pattern: /{$controller.list.name.lower$}(/page)
    controller: {$controller.list.name.cap$}
    action:
        post: CopyController
        patch: BatchController
        put: FilterController
    variables:
        layout: {$controller.list.name.lower$}
