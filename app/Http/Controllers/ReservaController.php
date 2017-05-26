<?php

namespace App\Http\Controllers;
use App\Http\Requests\HorariosReserva;
use App\Http\Requests\StoreReserva;
use App\Http\Requests\UpdateReserva;
use App\Model\Fecha;
use App\Model\Horario;
use App\Model\TipoReserva;
use Illuminate\Http\Request;
use App\Model\Reserva;
use App\Model\Ambiente;
use App\Model\Evento;
use Illuminate\Support\Facades\DB;
use App\Model\Usuario;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Input;

class ReservaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(auth()->user()->esAdministrador()){

            $reservas = Reserva::paginate(7);
            

            return view('reservas.admin.index', compact('reservas'));
        }
        $usuario = auth()->user();
        $reservas = $usuario->reserva;
        return view('reservas.index', compact('reservas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('reservas.create.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreReserva $request)
    {
        $reserva = new Reserva();
        $reserva->id_usuario = auth()->user()->id_usuario;
        $reserva->save();

        $ambiente = Ambiente::findOrFail(1);
        $ambiente->setFecha($request->id_fecha);
        $ids_horas = $request->ids_horas;
        foreach ($ids_horas as $id){
            $ambiente->horarios()->updateExistingPivot($id,['id_reserva' => $reserva->id_reserva , 'estado' => 'Ocupado' ]);
        }

        
        if(auth()->user()->esAutorizado()){
            $evento = new Evento();
            $evento->id_reserva = $reserva->id_reserva;
            $evento->tipo=$request->tipo;
            $evento->descripcion=$request->descripcion;
            $evento->save();
        }

        if(auth()->user()->esDocente()){
            $ids_usuario_materias = $request->ids_usuario_materias;
            foreach ($ids_usuario_materias as $id1){
                $evento = new Evento;
                $evento->id_reserva = $reserva->id_reserva;
                $evento->id_usuario_materia = $id1;
                $evento->save();
            }
        }
        return redirect()->route('reservas.index')
        ->with('mensaje', 'La reserva se ha creado con exito');
        
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
         $eventos= DB::table('evento')
                    ->where('evento.id_reserva','=',$id)
                    ->join('usuario_materia','evento.id_usuario_materia','=','usuario_materia.id_usuario_materia')          
                    ->join('materia','usuario_materia.id_materia' ,'=','materia.id_materia')                   
                    ->select('evento.tipo','evento.descripcion','materia.nombre','usuario_materia.grupo')
                    ->get(); 



         $eventosAutorizado=  DB::table('evento')
                                ->where('evento.id_reserva','=',$id)
                                ->select('evento.tipo','evento.descripcion')
                                ->first(); 
                                

        
        // $reservas= DB::table('reserva')
        //             ->where('reserva.id_reserva','=',$id)
        //             ->join('USUARIO','reserva.id_usuario','=','USUARIO.id_usuario') 
        //             ->join('evento','reserva.id_reserva' ,'=','evento.id_reserva')                   
        //             ->select('reserva.id_reserva','USUARIO.nombre', 'USUARIO.id_usuario', 'USUARIO.apellido_paterno', 'USUARIO.apellido_materno',
        //                 'USUARIO.email','evento.tipo','evento.descripcion')
        //             ->first(); 
        // $materias= DB::table('reserva')
        //             ->where('reserva.id_reserva','=',$id)
        //             ->join('USUARIO','reserva.id_usuario','=','USUARIO.id_usuario') 
        //             ->join('usuario_materia','USUARIO.id_usuario','=','usuario_materia.id_usuario')
        //             ->join('materia', 'usuario_materia.id_usuario_materia','=','materia.id_materia')                   
        //             ->select('materia.nombre','usuario_materia.grupo')
        //             ->get()->toArray();


        
       // dd($eventosAutorizado);            
                     
          return view('reservas.vista.view', compact('eventos','eventosAutorizado'));
    }
    

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $reserva = Reserva::findOrFail($id);
        $eventos = $reserva->eventos;


        if(auth()->user()->esAutorizado()){
            return view('reservas.edit.edit', compact('eventos'));
        }
        if(auth()->user()->esDocente()){
            $usuario = auth()->user();
            $materias = $usuario->materias;
            return view('reservas.edit.edit', compact('eventos'), compact('materias'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateReserva $request, $id)
    {   
        if(auth()->user()->esAutorizado()){
            $evento = Evento::findOrFail(Reserva::findOrFail($id)->eventos->first()->id_evento);
            $evento->fill($request->all());
            $evento->save();
        }
        if(auth()->user()->esDocente()){
            $ids_usuario_materias = $request->ids_usuario_materias;
            $borrado = Evento::where('id_reserva', $id )->delete();

            foreach ($ids_usuario_materias as $id1){
                $evento = new Evento;
                $evento->id_reserva = $id;
                $evento->id_usuario_materia = $id1;
                $evento->save();
            }
        }   
        return redirect()->route('reservas.index')
        ->with('mensaje', 'La reserva se ha modificado');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $reserva = Reserva::findOrFail($id);
        $eventos = $reserva->eventos;
        $horarios = $reserva->horarios;
        //liberando horarios reservados
        foreach ($horarios as $horario){
            $id_hora = $horario->id_horas;
            $reserva->horarios()->updateExistingPivot($id_hora,['id_reserva' => NULL, 'estado' => 'Libre' ]);
        }
        //borrando eventos (1)->para autorizado (n)->para docente
        foreach ($eventos as $evento){
          //borrando evento
          $evento->delete();
        }
        //borrando reserva
        $reserva->delete();
        return redirect()->route('reservas.index')
            ->with('mensaje', 'Se ha eliminado la reserva');
    }

    public function horarios(HorariosReserva $request)
    {
        $ambiente = $request->ambiente;
        $fecha = $request->fecha;
        $ambiente = Ambiente::findOrFail($ambiente);
        $ambiente->setFecha($fecha);
        $horarios = $ambiente->horarios;
        $usuario = auth()->user();
        $materias = $usuario->materias;
        return view('reservas.horarios', compact('horarios', 'ambiente', 'fecha', 'materias'));
    }

    public function config(){
        return view('reservas.admin.config.config');
    }

    public function updateConfig(Request $request){
        $tipo_reserva = TipoReserva::updateOrCreate(
            ['tipo' => $request->tipo],
            ['max_nro_periodos' => $request->numeroPeriodos,
             'max_nro_participantes' => $request->numeroParticipantes,
            ]
        );
        return redirect()
            ->route('reservas.index')
            ->with('mensaje', 'Se ha cofigurado la reserva');
    }

    public function filtrado(Request $request){

        if ($request) {
            $nombre = $request->nombre;
            $usuarios = Usuario::where('nombre', 'LIKE', '%'.$nombre.'%')
                ->orWhere('apellido_paterno', 'LIKE', '%'.$nombre.'%')
                ->orWhere('apellido_materno', 'LIKE', '%'.$nombre.'%')
                ->get();

            $reservas = null;
            foreach ($usuarios as $usuario) {
                foreach ($usuario->reserva as $reserva) {
                    $reservas[] = $reserva;
                }
            }
            $paginate = 10;

            $page = Input::get('page', 1);

            

            $offSet = ($page * $paginate) - $paginate;  

            $itemsForCurrentPage = array_slice($reservas, $offSet, $paginate, true);  

            $reservas = new LengthAwarePaginator($itemsForCurrentPage, count($reservas), 10, $page);

            
            
            
            return view('reservas.admin.index', compact('reservas'));
            
        } else {
            $reservas = Reserva::paginate(7);
            return view('reservas.admin.index', compact('reservas'));
        }
        
    }
}
