{{-- Part of Phoenix project. --}}

<div class="checkbox checkbox-primary single-checkbox">
    <input id="grid-checkboxes-toggle" type="checkbox" class="grid-check-all"
        onclick="{{ $phoenixJsObject }}.Grid.toggleAll(this.checked, {{ $options['duration'] }})" />
    <label for="grid-checkboxes-toggle"></label>
</div>
