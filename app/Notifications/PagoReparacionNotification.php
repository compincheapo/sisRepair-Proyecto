<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;
use App\Models\InformacionGeneral;

class PagoReparacionNotification extends Notification
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
            ->subject('Recordatorio Pago Servicio Reparación')
            ->greeting('Hola ' . $this->equipo->user->name . ' ' .  $this->equipo->user->lastname . '!.')
            ->line('El Equipo Tipo: ' . $this->equipo->tipoequipo->nombre . ', Marca: ' . $this->equipo->marca->nombre . ', Modelo: ' . $this->equipo->modelo . ' relacionado a la Orden de Servicio: ' . $this->orden->id . ' ha sido reparado.')
            ->line('Este recordatorio se repetirá '. $this->veces  .' cantidad de veces. Recuerde que su equipo se registrará como abandonado si no paga antes de estos avisos.')
            ->line('Usted ahora dispone de dos medios de pagos:')
            ->line(new HtmlString("<b>Mercadopago: </b>" .'mediante el cual podrá realizar el pago a través el sistema ingresando  a la última orden del Equipo y tendrá una opcion para ello. También lo puede realizar de forma presencial en el local con el mismo medio.'))
            ->line(new HtmlString("<b>Efectivo: </b>" . 'mediante el cual tendrá que acudir al local para poder realizar el pago en efectivo.'))
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
