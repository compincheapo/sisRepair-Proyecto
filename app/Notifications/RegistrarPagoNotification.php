<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;
use App\Models\InformacionGeneral;

class RegistrarPagoNotification extends Notification
{
    use Queueable;

    public $equipo;
    public $ordenes;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($equipo, $ordenes)
    {
        $this->equipo = $equipo;
        $this->ordenes = $ordenes;
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
        $ordenesDiagnostico = implode (", ", $this->ordenes);

        return (new MailMessage)
            ->subject('Pago Registrado de Ordenes de Servicio')
            ->greeting('Hola ' . $this->equipo->user->name . ' ' .  $this->equipo->user->lastname . '!.')
            ->line('Se ha realizado el pago de las Ordenes de Servicio: ' . $ordenesDiagnostico . '.')
            ->line('Usted ahora puede ver el comprobante de la realización de dicho pago y acceder al sistema para realizar una retroalimentación respecto a los servicios dados.')
            ->action('Ir al Sitio', url('http://localhost:8000/'))
            ->line('Gracias por confiar en nosotros tus Equipos!. ')
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
