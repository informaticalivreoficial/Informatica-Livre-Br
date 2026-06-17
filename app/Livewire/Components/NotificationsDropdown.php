<?php

namespace App\Livewire\Components;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class NotificationsDropdown extends Component
{
    public $unreadNotificationsCount = 0;
    public $notifications;

    // Atualiza o dropdown a cada 30 segundos
    protected $listeners = ['refreshNotifications' => 'loadNotifications'];

    public function mount()
    {
        $this->loadNotifications();
    }

    /**
     * Query base das notificações NÃO LIDAS
     */
    protected function baseUnreadQuery()
    {
        $user = Auth::user();

        $query = $user->unreadNotifications();

        return $query;
    }

    public function loadNotifications()
    {
        if (!Auth::check()) return;

        $query = $this->baseUnreadQuery();

        $this->unreadNotificationsCount = $query->count();

        $this->notifications = $query->latest()->take(5)->get();        
    }

    public function markAsRead($notificationId)
    {
        if (!Auth::check()) return;

        $notification = $this->baseUnreadQuery()
            ->where('id', $notificationId)
            ->first();

        if ($notification) {
            $notification->markAsRead();

            $this->loadNotifications();

            $this->dispatch('toast', [
                'type' => 'success',
                'message' => 'Notificação marcada como lida'
            ]);
        }        
    }

    public function markAllAsRead()
    {
        if (!Auth::check()) return;

        $this->baseUnreadQuery()->update([
            'read_at' => now(),
        ]);

        $this->loadNotifications();

        $this->dispatch('toast', [
            'type' => 'success',
            'message' => 'Todas as notificações foram marcadas como lidas'
        ]);        
    }

    public function openNotification(string $notificationId)
    {
        $notification = auth()->user()
            ->notifications()
            ->findOrFail($notificationId);

        $notification->markAsRead();

        return redirect($notification->data['url'] ?? route('notifications.index'));
    }

    public function render()
    {
        return view('livewire.components.notifications-dropdown');
    }
}
