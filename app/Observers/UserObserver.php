<?php

namespace App\Observers;

use App\Filament\Resources\UserResource;
use App\Jobs\NotifyFilamentUsers;
use App\Models\User;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        $notification = Notification::make()
            ->success()
            ->title('New user created')
            ->icon('heroicon-o-user')
            ->body("User {$user->name} successfully created.")
            ->actions([
                Action::make('View')->url(
                    UserResource::getUrl('edit', ['record' => $user])
                )->markAsRead(),
            ]);

        dispatch(new NotifyFilamentUsers($notification));
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        //
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        //
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        //
    }
}
