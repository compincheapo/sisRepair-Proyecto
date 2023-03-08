<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;
use App\Models\InformacionGeneral;

class PresupuestoNotification extends Notification
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
            ->subject('Presupuesto Realizado sobre Servicio Diagnóstico en Equipo')
            ->greeting('Hola ' . $this->equipo->user->name . ' ' .  $this->equipo->user->lastname . '!.')
            ->line('El Equipo Tipo: ' . $this->equipo->tipoequipo->nombre . ', Marca: ' . $this->equipo->marca->nombre . ', Modelo: ' . $this->equipo->modelo . ' relacionado a la Orden de Servicio: ' . $this->orden->id . ' ha sido presupuestado.')
            ->line('Usted ahora puede tomar la decisión de aceptar el presupuesto o rechazarlo mediante el sistema, haciendolo usted mismo, o puede acudir al local y lo haremos por usted.')
            ->line('Tenga en cuenta que al:.')
            ->line(new HtmlString("<b>Aceptar el Presupuesto: </b>" . 'se creará una nueva orden de servicio para Reparación, donde una vez reparado, se le notificará y podrá realizar el pago tanto en el sistema o de forma presencial en el local. Tenga en cuenta que al pagar la reparación, el Servicio de Diagnóstico realizado no se le cobrará.'))
            ->line(new HtmlString("<b>Rechazar el Presupuesto: </b>" . 'podrá realizar el pago del diagnóstico tanto por el sistema o acudiendo al local y podrá realizar el retiro de su Equipo.'))
            ->line('Los medios de pagos disponibles son:.')
            ->line(new HtmlString("<b>Mercadopago: </b>" .'mediante el cual podrá realizar el pago a través el sistema ingresando  a la orden y tendrá una opcion para ello. También lo puede realizar de forma presencial con el mismo medio.'))
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
