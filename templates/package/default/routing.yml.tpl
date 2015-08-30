{$controller.item.name.lower$}:
    pattern: /{$controller.item.name.lower$}(/id)
    controller: {$controller.item.name.cap$}
{$controller.list.name.lower$}:
    pattern: /{$controller.list.name.lower$}(/page)
    controller: {$controller.list.name.cap$}
    action:
        post: CopyController
        patch: UpdateController
        put: FilterController
