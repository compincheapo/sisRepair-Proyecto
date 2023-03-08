<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;
use App\Models\InformacionGeneral;



class CreateUserNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $user;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
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
       
        if($this->user['roles'][0] == 'Cliente'){
            return (new MailMessage)
                ->subject('Alta de Usuario Cliente en sisRepair')
                ->greeting('Hola ' . $this->user['name'] .' ' .  $this->user['lastname'] . ', usted ha sido registrado en el Sistema de Gestión de Diagnósticos y Reparaciones sisRepair!')
                ->line('El usuario y contraseña que utilizará para consultar sus Equipos y las ordenes asociadas son:')
                ->line(new HtmlString("<b>Usuario: </b>" . $this->user['username']))
                ->line(new HtmlString("<b>Contraseña: </b>" . $this->user['password']))
                ->action('Ir al Sitio', url('http://localhost:8000/'))
                ->attach(public_path('assets\pdf\terminosycondiciones.pdf'))  
                ->line('Gracias por confiar en nosotros tus Equipos, le estaremos notificando los Estados!.')
                ->line('Horarios de Atención: ' . $informacionGeneral->diadesde . ' a ' . $informacionGeneral->diahasta . ' de ' . date("H:i", strtotime($informacionGeneral->horadesde)) . ' a ' . date("H:i", strtotime($informacionGeneral->horahasta)))
                ->salutation("\r\n\r\n Saludos,  \r\n $informacionGeneral->nombre.");
        }

        if($this->user['roles'][0] == 'Tecnico'){
            return (new MailMessage)
                ->subject('Alta de Usuario Técnico en sisRepair')
                ->greeting('Hola ' . $this->user['name'] .' ' .  $this->user['lastname'] . ', usted ha sido registrado en el Sistema de Gestión de Diagnósticos y Reparaciones sisRepair!')
                ->line('El usuario y contraseña que utilizará para consultar sus asignaciones de Diagnóstico y Reparaciones asociadas son:')
                ->line(new HtmlString("<b>Usuario: </b>" . $this->user['username']))
                ->line(new HtmlString("<b>Contraseña: </b>" . $this->user['password']))
                ->action('Ir al Sitio', url('http://localhost:8000/'))
                ->attach(public_path('assets\pdf\terminosycondiciones.pdf'))  
                ->line('Gracias por confiar en nosotros para realizar servicios, no te olvides de ingresar al sistema y reportar avances!.')
                ->line('Horarios de Atención: ' . $informacionGeneral->diadesde . ' a ' . $informacionGeneral->diahasta . ' de ' . date("H:i", strtotime($informacionGeneral->horadesde)) . ' a ' . date("H:i", strtotime($informacionGeneral->horahasta)))
                ->salutation("\r\n\r\n Saludos,  \r\n $informacionGeneral->nombre.");
        }

        if($this->user['roles'][0] == 'Tercero'){
            return (new MailMessage)
                ->subject('Alta de Usuario Tercero en sisRepair')
                ->greeting('Hola ' . $this->user['name'] .' ' .  $this->user['lastname'] . ', usted ha sido registrado en el Sistema de Gestión de Diagnósticos y Reparaciones sisRepair!')
                ->line('El usuario y contraseña que utilizará para consultar sus asignaciones de Diagnóstico y Reparaciones asociadas son:')
                ->line(new HtmlString("<b>Usuario: </b>" . $this->user['username']))
                ->line(new HtmlString("<b>Contraseña: </b>" . $this->user['password']))
                ->action('Ir al Sitio', url('http://localhost:8000/'))
                ->attach(public_path('assets\pdf\terminosycondiciones.pdf'))  
                ->line('Gracias por confiar en nosotros para realizar servicios, no te olvides de ingresar al sistema y reportar avances!.')
                ->line('Horarios de Atención: ' . $informacionGeneral->diadesde . ' a ' . $informacionGeneral->diahasta . ' de ' . date("H:i", strtotime($informacionGeneral->horadesde)) . ' a ' . date("H:i", strtotime($informacionGeneral->horahasta)))
                ->salutation("\r\n\r\n Saludos,  \r\n $informacionGeneral->nombre.");
        }
        if($this->user['roles'][0] == 'Vendedor'){
            return (new MailMessage)
                ->subject('Alta de Usuario Vendedor en sisRepair')
                ->greeting('Hola ' . $this->user['name'] .' ' .  $this->user['lastname'] . ', usted ha sido registrado en el Sistema de Gestión de Diagnósticos y Reparaciones sisRepair!')
                ->line('El usuario y contraseña que utilizará para consultar sus asignaciones de Diagnóstico y Reparaciones asociadas son:')
                ->line(new HtmlString("<b>Usuario: </b>" . $this->user['username']))
                ->line(new HtmlString("<b>Contraseña: </b>" . $this->user['password']))
                ->action('Ir al Sitio', url('http://localhost:8000/'))
                ->attach(public_path('assets\pdf\terminosycondiciones.pdf'))  
                ->line('Gracias por confiar en nosotros para realizar la atención a los Clientes!.')
                ->line('Horarios de Atención: ' . $informacionGeneral->diadesde . ' a ' . $informacionGeneral->diahasta . ' de ' . date("H:i", strtotime($informacionGeneral->horadesde)) . ' a ' . date("H:i", strtotime($informacionGeneral->horahasta)))
                ->salutation("\r\n\r\n Saludos,  \r\n $informacionGeneral->nombre.");
        }
                    
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
