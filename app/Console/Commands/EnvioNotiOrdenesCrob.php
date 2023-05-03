<?php

namespace App\Console\Commands;

use App\Jobs\SendNotiMotoristaJobs;
use App\Jobs\SendNotiPropietarioJobs;
use App\Models\Afiliados;
use App\Models\InformacionAdmin;
use App\Models\Motoristas;
use App\Models\MotoristasOrdenes;
use App\Models\Ordenes;
use Carbon\Carbon;
use Illuminate\Console\Command;

class EnvioNotiOrdenesCrob extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ordenes:verificar';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envio Noti cada minuto a ordenes nuevas a los propietarios, sino ha contestado aun';

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

        /*$ordenhoy = Ordenes::where('estado_2', 0) // aun no han contestado
        ->where('estado_7', 0) // no ha sido cancelada
        ->whereDate('fecha_orden', '=', Carbon::today('America/El_Salvador')->toDateString())
            ->count();*/

        if($ordenhoy > 0){

            $listaPropietarios = Afiliados::where('activo', 1)
                ->where('disponible', 1)
                ->get();

            $pilaPropietarios = array();
            foreach($listaPropietarios as $p){
                if($p->token_fcm != null){
                    array_push($pilaPropietarios, $p->token_fcm);
                }
            }

            $titulo = "Ordenes Pendiente";
            $mensaje = "Hay Ordenes sin Contestar";

            if($pilaPropietarios != null) {
                SendNotiPropietarioJobs::dispatch($titulo, $mensaje, $pilaPropietarios);
            }
        }

        // notificacion a motoristas que hay ordenes nuevas para que las agarren
        $ordenMotorista = Ordenes::where('estado_2', 1) // inicio preparacion
        //->where('estado_7', 0) // no ha sido cancelada
        //->where('tipoentrega', 1) // domicilio
        ->whereDate('fecha_orden', '=', Carbon::today('America/El_Salvador')->toDateString())
            ->count();

        $seguroNotiMotorista = false;

        if($ordenMotorista > 0){

            $ordenFore = Ordenes::where('estado_2', 1) // inicio preparacion
           // ->where('estado_7', 0) // no ha sido cancelada
            //->where('tipoentrega', 1) // domicilio
            ->whereDate('fecha_orden', '=', Carbon::today('America/El_Salvador')->toDateString())
                ->get();

            // hoy verificar sino han agarrado esa orden
            foreach ($ordenFore as $ff){

                if(MotoristasOrdenes::where('ordenes_id', $ff->id)->first()){
                    // orden agarrada
                }else{
                    // falta orden sin agarrar, enviar notificacion
                    $seguroNotiMotorista = true;
                    break;
                }
            }

            if($seguroNotiMotorista){

                // notificacion a todos los motoristas que hay orden por agarrar
                $listaMotoristas = Motoristas::where('activo', 1)
                    ->where('disponible', 1)
                    ->get();

                $pilaMotoristas = array();
                foreach($listaMotoristas as $p){
                    if($p->token_fcm != null){
                        array_push($pilaMotoristas, $p->token_fcm);
                    }
                }

                $titulo = "Hay Nuevas Ordenes";
                $mensaje = "Por Favor Verificar";

                if($pilaMotoristas != null) {
                    SendNotiMotoristaJobs::dispatch($titulo, $mensaje, $pilaMotoristas);
                }
            }
        }

    }
}
