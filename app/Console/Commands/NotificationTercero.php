<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\OrdenServicio;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Notifications\PagoReparacionNotification;

class NotificationTercero extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notificacion:tercero';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notificacion a Terceros que no cumplieron la fecha prometida.';

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
        $notificacionestercero = DB::table('notificaciontercero')->get();

        foreach ($notificacionestercero as $notificaciontercero) {
            $ordenServicio = OrdenServicio::findOrfail($notificaciontercero->id_orden)->where('id', $notificaciontercero->id_orden)->first();
            $equipo = $ordenServicio->equipo->first();

            $fechaTomada = Carbon::parse($notificaciontercero->fechatomada);
            $fechaActual = Carbon::now();
            $dias = $fechaActual->diffInDays($fechaTomada);
            

            if($notificacionestercero->activo == 0 && $dias >= 1){
                DB::table('notificaciontercero')
                ->where('id', $notificaciontercero->id)
                ->update(['fechatomada' => Carbon::now(), 'conteo' => 0, 'activo' => 1]);
            }

            if($notificaciontercero->activo == 1){
                $toDate = Carbon::parse($notificaciontercero->fechatomada);
                $fromDate = Carbon::now();     
                if($notificaciontercero->frecuencia == 'semanal'){
                    if($notificaciontercero->conteo < $notificaciontercero->cantidadveces){
                        $days = $toDate->diffInDays($fromDate);
                        if($days >= 7){
                            $nuevoConteo = $notificaciontercero->conteo + 1;                            
                            DB::table('notificaciontercero')
                            ->where('id', $notificaciontercero->id)
                            ->update(['fechatomada' => Carbon::now(), 'conteo' => $nuevoConteo]);
                            $equipo->user->notify(new PagoReparacionNotification($equipo, $ordenServicio, $notificaciontercero->cantidadveces));
                        }
                    }
                    if($notificaciontercero->conteo == $notificaciontercero->cantidadveces){
                        DB::table('notificaciontercero')
                        ->where('id', $notificaciontercero->id)
                        ->update(['activo' => 0]);
                    } 
                }
                if($notificaciontercero->frecuencia == 'mensual'){
                    if($notificaciontercero->conteo < $notificaciontercero->cantidadveces){
                        $months = $toDate->diffInMonths($fromDate);
                        if($months >= 1){
                            $nuevoConteo = $notificaciontercero->conteo + 1;
                            $equipo->user->notify(new PagoReparacionNotification($equipo, $ordenServicio, $notificaciontercero->cantidadveces));
                            DB::table('notificaciontercero')
                            ->where('id', $notificaciontercero->id)
                            ->update(['fechatomada' => Carbon::now(), 'conteo' => $nuevoConteo]);
                        }
                    }
    
                    if($notificaciontercero->conteo == $notificaciontercero->cantidadveces){
                        DB::table('notificaciontercero')
                        ->where('id', $notificaciontercero->id)
                        ->update(['activo' => 0]);
                    } 
                }

                if($notificaciontercero->frecuencia == 'anual'){
                    if($notificaciontercero->conteo < $notificaciontercero->cantidadveces){
                        $years = $toDate->diffInYears($fromDate);
                        if($months >= 1){
                            $nuevoConteo = $notificaciontercero->conteo + 1;
                            $equipo->user->notify(new PagoReparacionNotification($equipo, $ordenServicio, $notificaciontercero->cantidadveces));
                            DB::table('notificaciontercero')
                            ->where('id', $notificaciontercero->id)
                            ->update(['fechatomada' => Carbon::now(), 'conteo' => $nuevoConteo]);
                        }
                    }
    
                    if($notificaciontercero->conteo == $notificaciontercero->cantidadveces){
                        DB::table('notificaciontercero')
                        ->where('id', $notificaciontercero->id)
                        ->update(['activo' => 0]);
                    } 
                }
            }
        } 
    }
}
