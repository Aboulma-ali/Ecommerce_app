<?php

namespace App\Models;

use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Database\Eloquent\Builder;

class Notification extends DatabaseNotification
{
    /**
     * Récupère les notifications non lues.
     */
    public function scopeUnread(Builder $query)
    {
        return $query->whereNull('read_at');
    }

    /**
     * Récupère les notifications lues.
     */
    public function scopeRead(Builder $query)
    {
        return $query->whereNotNull('read_at');
    }

    /**
     * Accès direct au tableau de données de la notification.
     */
    public function getDataAttribute($value)
    {
        return json_decode($value, true);
    }

    /**
     * Marque la notification comme lue.
     */
    public function markAsRead()
    {
        $this->read_at = now();
        $this->save();
    }

    /**
     * Marque la notification comme non lue.
     */
    public function markAsUnread()
    {
        $this->read_at = null;
        $this->save();
    }
}
