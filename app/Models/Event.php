<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    protected $connection = 'mysql';
    protected $table = 'events';
    protected $fillable = [
        'code',
        'training_id',
        'location_id',
        'organizer_id',
        'trainer_id',
        'start_date',
        'end_date',
        'start_time',
        'end_time',
    ];



    public function transactions(): HasMany
    {
        return $this->hasMany(EventTransaction::class, 'event_id', 'id');
    }
    public function trainings(): BelongsTo
    {
        return $this->belongsTo(Training::class, 'training_id', 'code');
    }
    public function locations(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'location_id', 'code');
    }
    public function organizers(): BelongsTo
    {
        return $this->belongsTo(Organizer::class, 'organizer_id', 'code');
    }
    public function trainers(): BelongsTo
    {
        return $this->belongsTo(Trainer::class, 'trainer_id', 'code');
    }

    public function startDateFormat(): string
    {
        return Carbon::parse($this->start_date)->format('d M Y');
    }

    public function endDateFormat(): string
    {
        return Carbon::parse($this->end_date)->format('d M Y');
    }

    public function getStartTimeAttribute($value)
    {
        return Carbon::parse($value)->format('H:i');
    }

    public function getEndTimeAttribute($value)
    {
        return Carbon::parse($value)->format('H:i');
    }
}
