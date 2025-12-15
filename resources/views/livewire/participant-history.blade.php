<div>
    <x-card :icon="'fa-table'">
        @slot('title')
        History Training
        @endslot
        @slot('body')
        <div class="table-responsive">
            <table id="participant-history" class="table table-hover table-bordered table-striped table-light">
                <thead>
                    <tr>
                        <th class="text-center">No.</th>
                        <th class="text-center">Code</th>
                        <th class="text-center">Training</th>
                        <th class="text-center">Dates</th>
                        <th class="text-center">Completed</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($histories as $history)
                    <?php 
                    $completed = $this->completedLabel($history->completed);
                    ?>
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td class="text-center">{{ $history->event->trainings->code }}</td>
                        <td class="text-center">{{ $history->event->trainings->name }}</td>
                        <td class="text-center">{{ $history->event->startDateFormat() }} - {{
                            $history->event->endDateFormat() }}</td>
                        <td class="text-center">
                            <span class="badge {{ $completed['class'] }}">{{ $completed['text'] }}</span>
                        </td>
                    </tr>
                    @empty
                    @endforelse
                </tbody>
            </table>
        </div>
        @endslot
    </x-card>
</div>