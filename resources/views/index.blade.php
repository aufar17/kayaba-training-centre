<x-layout.app :user="$user" nav="Dashboard">
    @if ($user->dept == 'HRD')
    <livewire:kpi />
    @endif
</x-layout.app>