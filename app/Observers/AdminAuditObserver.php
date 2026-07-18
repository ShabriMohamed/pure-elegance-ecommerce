<?php

namespace App\Observers;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;

/**
 * Records admin-initiated create/update/delete actions to the activity_logs
 * audit trail. Only fires when the acting user is an admin, so customer/guest
 * actions (e.g. placing an order at checkout) are not logged here.
 */
class AdminAuditObserver
{
    private array $sensitive = ['password', 'remember_token'];

    public function created(Model $model): void
    {
        $this->log('created', $model);
    }

    public function updated(Model $model): void
    {
        $changes = $model->getChanges();
        unset($changes['updated_at']);
        foreach ($this->sensitive as $key) {
            if (array_key_exists($key, $changes)) {
                $changes[$key] = '••••';
            }
        }

        $this->log('updated', $model, $changes);
    }

    public function deleted(Model $model): void
    {
        $this->log('deleted', $model);
    }

    private function log(string $event, Model $model, array $changes = []): void
    {
        if (! auth()->check() || ! auth()->user()->isAdmin()) {
            return;
        }

        ActivityLog::record(class_basename($model) . '.' . $event, $model, $changes);
    }
}
