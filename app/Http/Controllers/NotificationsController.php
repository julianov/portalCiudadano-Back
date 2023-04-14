<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Services\PlSqlService;
use Carbon\Carbon;


class NotificationsController extends Controller
{

    protected PlSqlService $plSqlServices;

	public function __construct(PlSqlService $plSqlServices)
	{

		$this->plSqlServices = $plSqlServices;
	}

    


    public function sendNotificationsEmails ($recipients,$age_from,$age_to,$department_id,$locality_id,$message_title,$message_body,$attachment_type,$attachment,$notification_date_from,$notificaion_date_to)
    {


        $birthday = Carbon::now()->subYears($age_from);
        $min_fecha_nacimiento = $birthday->format('dd/mm/yyyy');

        $birthday = Carbon::now()->subYears($age_to);
        $max_fecha_nacimiento = $birthday->format('dd/mm/yyyy');

        $usuarios= $this->plSqlServices->getEmailsForNotification($min_fecha_nacimiento, $max_fecha_nacimiento, $localidad_id, $departamento_id,$recipients);

        $result_code=1; //es solo para prueba

        if ($attachment_type=='img'){

            Mail::to($usuario->email)
                ->queue((new EmailConfirmation($usuario, $result_code))
                    ->from('ciudadanodigital@entrerios.gov.ar', 'Ciudadano Digital - Provincia de Entre Ríos')
                    ->subject('Validación de correo e-mail')
                    ->attachData($base64Image, 'nombre_imagen.jpg', ['mime' => 'image/jpeg']));
        

        }elseif ($attachment_type=='pdf'){

            Mail::to($usuario->email)
            ->queue((new EmailConfirmation($usuario, $result_code))
                ->from('ciudadanodigital@entrerios.gov.ar', 'Ciudadano Digital - Provincia de Entre Ríos')
                ->subject('Validación de correo e-mail')
                ->attachData($base64File, 'nombre_archivo.pdf', ['mime' => 'application/pdf']));
        

        }else{

            foreach ($usuarios as $usuario) {
                Mail::to($usuario->email)
                    ->queue((new EmailConfirmation($usuario, $result_code))
                        ->from('ciudadanodigital@entrerios.gov.ar', 'Ciudadano Digital - Provincia de Entre Ríos')
                        ->subject('Validación de correo e-mail'));
            }

        }
      

    }

    public function newNotification(Request $request){

        $request->validate([
           // "token" => "required|string",
            "recipients" => [
                'required',
                'string',
                Rule::in(['citizen', 'actor', 'both']), // Verificar que el valor sea uno de los permitidos
            ], 
            "age_from" => "required|numeric",
            "age_to" => "required|numeric",
            "department_id" => "required|numeric",
            "locality_id" => "required|numeric",
            "message_title" => "required|string",
            "message_body" => "required|string",
            "attachment_type" => [
                'required',
                'max:50',
                Rule::in(['none', 'img', 'pdf']),
            ],
            "attachment" => "nullable|string",
            "notification_date_from" => "required|max:50|string",
            "notificaion_date_to" => "required|max:50|string",
            "send_by_email" => [
                'required',
                'max:50',
                Rule::in([0, 1]),
                Rule::regex('/^[0-1]+$/')
            ],
            
        ]);
             
      /*  $host = env('BASEUR_ER_WS_TOKEN');
               
        $response = Http::post($host, [
            '_tk' => $request['token'],
        ]);

        $responseBody = $response->body();

        if($responseBody == 1) {*/

            if ($request->has('attachment')) {

                $table_name = "NOTIFICATIONS";
                $columns = "RECIPIENTS, AGE_FROM, AGE_TO, DEPARTMENT_ID, LOCALITY_ID, MESSAGE_TITLE, MESSAGE_BODY, ATTACHMENT_TYPE,ATTACHMENT, NOTIFICATION_DATE_FROM, NOTIFICATION_DATE_TO, SEND_BY_EMAIL,CREATED_AT";
                $values = "'".$request['recipients']."','".$request['age_from']."','".$request['age_to']."','".$request['department_id']."','".$request['locality_id']."','".$request['message_title']."','".$request['message_body']."','".$request['attachment_type']."','".$request['attachment']."','".$request['notification_date_from']."','".$request['notificaion_date_to']."','".$request['send_by_email']."',sysdate";
                $res= $this->plSqlServices->insertarFila($table_name, $columns, $values);

                if ($res){

                    if ($request['send_by_email'] =='1' || $request['send_by_email'] == 1 ){

                        sendNotificationsEmails($request['recipients'],$request['age_from'],$request['age_to'],$request['department_id'],$request['locality_id'],$request['message_title'],$request['message_body'],$request['attachment_type'],$request['attachment'],$request['notification_date_from'],$request['notificaion_date_to']);
                    
                    }

                    return response()->json([
                        'status' => true,
                        'message' => 'Notification loaded successfully',
                    ], 201);

                }else{

                    return response()->json([
                        'status' => false,
                        'message' => 'Internal server problem, please try again later'
                    ], 503);
                }

                
            }else{
                
                $table_name = "NOTIFICATIONS";
                $columns = "RECIPIENTS, AGE_FROM, AGE_TO, DEPARTMENT_ID, LOCALITY_ID, MESSAGE_TITLE, MESSAGE_BODY, NOTIFICATION_DATE_FROM, NOTIFICATION_DATE_TO, SEND_BY_EMAIL,CREATED_AT";
                $values = "'".$request['recipients']."','".$request['age_from']."','".$request['age_to']."','".$request['department_id']."','".$request['locality_id']."','".$request['message_title']."','".$request['message_body']."','".$request['notification_date_from']."','".$request['notificaion_date_to']."',sysdate";
                $res= $this->plSqlServices->insertarFila($table_name, $columns, $values);

                if ($res){

                    if ($request['send_by_email'] =='1' || $request['send_by_email'] == 1 ){

                        sendNotificationsEmails($request['recipients'],$request['age_from'],$request['age_to'],$request['department_id'],$request['locality_id'],$request['message_title'],$request['message_body'],$request['attachment_type'],$request['notification_date_from'],$request['notificaion_date_to']);
                    
                    }

                    return response()->json([
                        'status' => true,
                        'message' => 'Notification loaded successfully',
                    ], 201);

                }else{

                    return response()->json([
                        'status' => false,
                        'message' => 'Internal server problem, please try again later'
                    ], 503);
                }
                
            }

      /*  }else{

            return response()->json([
                'status' => false,
                'message' => 'Bad token'
            ], 401);

        }*/
        
    }


    public function checkUserNotifications(Request $request){

        $user = Auth::guard('authentication')->user();

		if($user){

            $column_name = "USER_ID";
            $column_value = $user->id;
            $table = "USER_CONTACT";
            $user_data = $this->plSqlServices->getRow($table, $column_name, $column_value);

            if (!empty($user_data)) {

                //$fecha_val, $departamento_val, $localidad_val, $edad_val, $destinatario_val
                $fechaActual = Carbon::now()->format('dd-mm-yyyy');
                $fechaCumpleanos = Carbon::parse($user_data->BIRTHDAY);
                // Calcular la edad
                $edad = $fechaCumpleanos->age;

                $table = "USER_ACTORS";
                $column_name = "USER_ID";
                $column_value = $user->id;
				$user_actor = $this->plSqlServices->getRow($table, $column_name, $column_value);
				$is_actor_empty = empty($user_actor); // Verificar si $user_actor es una cadena vacía ('')
				$is_actor = ($is_empty) ? 'citizen' : 'actor';

                $res_notifications = $this->plSqlServices->getNotifications($fechaActual,$user_data->DEPARTMENT_ID, $user_data->LOCALITY_ID, $edad, $is_actor  );

                if (empty($res_notifications)) {

                    return response()->json([
                        'status' => false,
                        'notifications' => "without new notifications"
                    ], 204);

                } else {

                    return response()->json([
                        'status' => true,
                        'notifications' => $res_notifications
                    ], 200);
                }
                

            }else{

                return response()->json([
                    'status' => false,
                    'message' => 'User contact data not found'
                ], 404);

            }


        }else{

            return response()->json([
				'status' => false,
				'message' => 'User not found'
			], 404);

        }


    }

}
