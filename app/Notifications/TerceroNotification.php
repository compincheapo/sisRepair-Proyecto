<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\InformacionGeneral;
use Illuminate\Support\HtmlString;

class TerceroNotification extends Notification
{
    use Queueable;

    public $equipo;
    public $orden;
    public $veces;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($equipo, $orden, $veces)
    {
        $this->equipo = $equipo;
        $this->orden = $orden;
        $this->veces = $veces;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $informacionGeneral = InformacionGeneral::first();
        return (new MailMessage)
            ->subject('Recordatorio Devolución Equipos')
            ->greeting('Hola ' . $this->equipo->user->name . ' ' .  $this->equipo->user->lastname . '!.')
            ->line('El Equipo Tipo: ' . $this->equipo->tipoequipo->nombre . ', Marca: ' . $this->equipo->marca->nombre . ', Modelo: ' . $this->equipo->modelo . ' relacionado a la Orden de Servicio: ' . $this->orden->id . ' debe ser retroalimentado y devuelto hacia el local.')
            ->line('Este recordatorio se repetirá '. $this->veces  .' cantidad de veces. Recuerde que ha tenido una fecha estimada en la cual debió realizar el servicio y devolver el Equipo.')
            ->action('Ir al Sitio', url('http://localhost:8000/'))
            ->line('Horarios de Atención: ' . $informacionGeneral->diadesde . ' a ' . $informacionGeneral->diahasta . ' de ' . date("H:i", strtotime($informacionGeneral->horadesde)) . ' a ' . date("H:i", strtotime($informacionGeneral->horahasta)))
            ->salutation("\r\n\r\n Saludos,  \r\n $informacionGeneral->nombre.");
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
