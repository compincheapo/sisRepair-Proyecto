<?php

namespace App\Console\Commands;

use App\Models\OrdenServicio;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Notifications\PagoReparacionNotification;

class NotificationPago extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notification:pago';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notificacion a clientes para que realizen el pago de las reparaciones';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $notificacionespago = DB::table('notificacionpago')->get();

        foreach ($notificacionespago as $notificacionpago) {
            $ordenServicio = OrdenServicio::findOrfail($notificacionpago->id_orden)->where('id', $notificacionpago->id_orden)->first();
            $equipo = $ordenServicio->equipo->first();
            if($notificacionpago->activo == 1){
                $toDate = Carbon::parse($notificacionpago->fechatomada);
                $fromDate = Carbon::now();     
                if($notificacionpago->frecuencia == 'semanal'){
                    if($notificacionpago->conteo < $notificacionpago->cantidadveces){
                        $days = $toDate->diffInDays($fromDate);
                        if($days >= 7){
                            $nuevoConteo = $notificacionpago->conteo + 1;                            
                            DB::table('notificacionpago')
                            ->where('id', $notificacionpago->id)
                            ->update(['fechatomada' => Carbon::now(), 'conteo' => $nuevoConteo]);
                            $equipo->user->notify(new PagoReparacionNotification($equipo, $ordenServicio, $notificacionpago->cantidadveces));
                        }
                    }
                    if($notificacionpago->conteo == $notificacionpago->cantidadveces){
                        DB::table('notificacionpago')
                        ->where('id', $notificacionpago->id)
                        ->update(['activo' => 0]);
                    } 
                }
                if($notificacionpago->frecuencia == 'mensual'){
                    if($notificacionpago->conteo < $notificacionpago->cantidadveces){
                        $months = $toDate->diffInMonths($fromDate);
                        if($months >= 1){
                            $nuevoConteo = $notificacionpago->conteo + 1;
                            $equipo->user->notify(new PagoReparacionNotification($equipo, $ordenServicio, $notificacionpago->cantidadveces));
                            DB::table('notificacionpago')
                            ->where('id', $notificacionpago->id)
                            ->update(['fechatomada' => Carbon::now(), 'conteo' => $nuevoConteo]);
                        }
                    }
    
                    if($notificacionpago->conteo == $notificacionpago->cantidadveces){
                        DB::table('notificacionpago')
                        ->where('id', $notificacionpago->id)
                        ->update(['activo' => 0]);
                    } 
                }

                if($notificacionpago->frecuencia == 'anual'){
                    if($notificacionpago->conteo < $notificacionpago->cantidadveces){
                        $years = $toDate->diffInYears($fromDate);
                        if($months >= 1){
                            $nuevoConteo = $notificacionpago->conteo + 1;
                            $equipo->user->notify(new PagoReparacionNotification($equipo, $ordenServicio, $notificacionpago->cantidadveces));
                            DB::table('notificacionpago')
                            ->where('id', $notificacionpago->id)
                            ->update(['fechatomada' => Carbon::now(), 'conteo' => $nuevoConteo]);
                        }
                    }
    
                    if($notificacionpago->conteo == $notificacionpago->cantidadveces){
                        DB::table('notificacionpago')
                        ->where('id', $notificacionpago->id)
                        ->update(['activo' => 0]);
                    } 
                }
            }
        } 
    }
}
