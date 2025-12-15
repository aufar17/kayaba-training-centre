<x-layout.app :user="$user" nav="Dashboard">
    @if ($user->dept == 'HRD')
    <livewire:dashboard />
    @endif
</x-layout.app>