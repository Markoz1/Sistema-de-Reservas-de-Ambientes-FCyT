	
				
				<table class="table table-bordered table-hover">
					<tr>
						<td><strong>Id. de Usuario</strong></td>
						<td>{{ $reservas->id_usuario }}</td>
					</tr>
					<tr>
						<td><strong>Id de Reserva</strong></td>
						<td>{{ $reservas->id_reserva }}</td>
					</tr>
					<tr>
						<td><strong>Apellido </strong></td>
						<td>{{ $reservas->apellido_paterno }}</td>
					</tr>
					<tr>
						<td><strong>Apellido Materno</strong></td>
						<td>{{ $reservas->apellido_materno }}</td>
					</tr>
					<tr>
						<td><strong>Email</strong></td>
						<td>{{ $reservas->email }}</td>
					</tr>
					<tr>
						<td><strong>Materias</strong></td>				               
                        <td>@foreach($materias as $item) // {{ $item->nombre }} @endforeach</td>                                   
                    </tr>                  		
					
					
				</table>
			
