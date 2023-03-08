<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;
use App\Models\InformacionGeneral;

class FinalizarDiagnosticoNotification extends Notification
{
    use Queueable;
    
    public $equipo;
    public $orden;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($equipo, $orden)
    {
        $this->equipo = $equipo;
        $this->orden = $orden;

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
                ->subject('Finalización Servicio Diagnóstico en Equipo')
                ->greeting('Hola ' . $this->equipo->user->name . ' ' .  $this->equipo->user->lastname . '!.')
                ->line('El Equipo Tipo: ' . $this->equipo->tipoequipo->nombre . ', Marca: ' . $this->equipo->marca->nombre . ', Modelo: ' . $this->equipo->modelo . ' relacionado a la Orden de Servicio: ' . $this->orden->id . ' ha sido diagnósticado.')
                ->line('Usted ahora debe esperar a que se realice el presupuesto para que pueda tomar una decisión para aceptarlo o rechazarlo. Lo notificaremos en breve.')
                ->action('Ir al Sitio', url('http://localhost:8000/'))
                // ->attach(storage_path() . "/app/library/code-conduct-2014.pdf")  --- Agregar atajo al pdf y enviarlo.
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
