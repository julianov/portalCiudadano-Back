<?php

namespace App\Http\Controllers;

use App\Http\Requests\Notifications\NewNotificationRequest;
use Illuminate\Http\Request;
use App\Http\Services\PlSqlService;
use App\Http\Services\ErrorService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Mail;
use App\Mail\NotificationEmail;
use App\Models\User;


class NotificationsController extends Controller
{

    protected PlSqlService $plSqlServices;
    private ErrorService $errorService;


	public function __construct(PlSqlService $plSqlServices, ErrorService $errorService)
	{

		$this->plSqlServices = $plSqlServices;
        $this->errorService = $errorService;

	}

    


    public function sendNotificationsEmails ($recipients,$age_from,$age_to,$department_id,$locality_id,$message_title,$message_body,$attachment_type,$attachment,$notification_date_from,$notificaion_date_to)
    {


        $birthday = Carbon::now()->subYears($age_from);
        $min_fecha_nacimiento = $birthday->format('d/m/Y');

        $birthday = Carbon::now()->subYears($age_to);
        $max_fecha_nacimiento = $birthday->format('d/m/Y');

        $usuarios= explode(",", $this->plSqlServices->getEmailsForNotification($min_fecha_nacimiento, $max_fecha_nacimiento, $locality_id, $department_id,$recipients));

        $result_code=1; //es solo para prueba

        if ($attachment_type=='img'){

            $usuarios_unicos = array_unique($usuarios);

            foreach ($usuarios_unicos as $usuario) {

                //$user = User::where('cuil', $usuario)->first();

                Mail::to($usuario)
                    ->queue((new NotificationEmail( $message_title, $message_body))
                        ->from('ciudadanodigital@entrerios.gov.ar', 'Ciudadano Digital - Provincia de Entre Ríos')
                        ->subject($message_title)
                        ->attachData($attachment, 'nombre_imagen.png', ['mime' => 'image/png']));
            
                }
        }elseif ($attachment_type=='pdf'){

            $usuarios_unicos = array_unique($usuarios);

            foreach ($usuarios_unicos as $usuario) {
                
                //$user = User::where('cuil', $usuario)->first();

                Mail::to($usuario)
                ->queue((new NotificationEmail( $message_title, $message_body))
                    ->from('ciudadanodigital@entrerios.gov.ar', 'Ciudadano Digital - Provincia de Entre Ríos')
                    ->subject($message_title)
                    ->attachData($base64File, 'nombre_archivo.pdf', ['mime' => 'application/pdf']));
                }

        }else{

            $usuarios_unicos = array_unique($usuarios);
            
            foreach ($usuarios_unicos as $usuario) {

                //$user = User::where('cuil', $usuario)->first();
               
                Mail::to($usuario)
                    ->queue((new NotificationEmail( $message_title, $message_body))
                        ->from('ciudadanodigital@entrerios.gov.ar', 'Ciudadano Digital - Provincia de Entre Ríos')
                        ->subject($message_title));
            }

        }
      

    }

    public function newNotification(NewNotificationRequest $request){

        $validated = $request->validated();

             
      /*  $host = env('BASEUR_ER_WS_TOKEN');
               
        $response = Http::post($host, [
            '_tk' => $request['token'],
        ]);

        $responseBody = $response->body();

        if($responseBody == 1) {*/

            if ($request->has('attachment')) {

                $table_name = "NOTIFICATIONS";
                $file_type=""; 

                if ($validated['attachment_type'] == 'img'){

                    $file_type="IMG";

                }else{

                    $file_type="DOC";

                }

                $res= $this->plSqlServices->insertFile($table_name, $file_type, $validated['attachment_type'], $validated['message_title'] , $validated['attachment_type']); 

                if ($res != -1){

                    $columns = "RECIPIENTS, AGE_FROM, AGE_TO, DEPARTMENT_ID, LOCALITY_ID, MESSAGE_TITLE, MESSAGE_BODY, ATTACHMENT_TYPE, MULTIMEDIA_ID, NOTIFICATION_DATE_FROM, NOTIFICATION_DATE_TO, SEND_BY_EMAIL,CREATED_AT";
                    $values = "'".$validated['recipients']."',".$validated['age_from'].",".$validated['age_to'].",".$validated['department_id'].",".$validated['locality_id'].",'".$validated['message_title']."','".$validated['message_body']."','".$validated['attachment_type']."',".res.",(TO_DATE('".$validated['notification_date_from']."', 'DD/MM/YYYY')),"."(TO_DATE('".$validated['notification_date_to']."', 'DD/MM/YYYY'))".",'".$validated['send_by_email']."',sysdate";

                    $res= $this->plSqlServices->insertarFila($table_name, $columns, $values);

                    if ($res){

                        if ($validated['send_by_email'] =='1' || $validated['send_by_email'] == 1 ){

                            self::sendNotificationsEmails($validated['recipients'],$validated['age_from'],$validated['age_to'],$validated['department_id'],$validated['locality_id'],$validated['message_title'],$validated['message_body'],$validated['attachment_type'],"none",$validated['notification_date_from'],$validated['notification_date_to']);
                        
                    }

                        return response()->json([
                            'status' => true,
                            'message' => 'Notification loaded successfully',
                        ], 201);

                    }else{

                        return $this->errorService->databaseWriteError();

                    }

                }else{

                    return $this->errorService->databaseWriteError();

                }

                
            }else{
                
                $table_name = "NOTIFICATIONS";
                $columns = "RECIPIENTS, AGE_FROM, AGE_TO, DEPARTMENT_ID, LOCALITY_ID, MESSAGE_TITLE, MESSAGE_BODY, ATTACHMENT_TYPE, NOTIFICATION_DATE_FROM, NOTIFICATION_DATE_TO, SEND_BY_EMAIL,CREATED_AT";
                $values = "'".$validated['recipients']."',".$validated['age_from'].",".$validated['age_to'].",".$validated['department_id'].",".$validated['locality_id'].",'".$validated['message_title']."','".$validated['message_body']."','".$validated['attachment_type']."',"."(TO_DATE('".$validated['notification_date_from']."', 'DD/MM/YYYY')),"."(TO_DATE('".$validated['notification_date_to']."', 'DD/MM/YYYY'))".",'".$validated['send_by_email']."',sysdate";

                $res= $this->plSqlServices->insertarFila($table_name, $columns, $values);

                if ($res){

                    if ($validated['send_by_email'] =='1' || $validated['send_by_email'] == 1 ){

                        self::sendNotificationsEmails($validated['recipients'],$validated['age_from'],$validated['age_to'],$validated['department_id'],$validated['locality_id'],$validated['message_title'],$validated['message_body'],$validated['attachment_type'],"none",$validated['notification_date_from'],$validated['notification_date_to']);
                    
                   }

                    return response()->json([
                        'status' => true,
                        'message' => 'Notification loaded successfully',
                    ], 201);

                }else{

                    return $this->errorService->databaseWriteError();

                }
                
            }
        
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
                $fechaActual = Carbon::now()->format('d/m/Y');
                $fechaCumpleanos = Carbon::parse($user_data->BIRTHDAY);
                // Calcular la edad
                $edad = $fechaCumpleanos->age;

                $table = "USER_ACTORS";
                $column_name = "USER_ID";
                $column_value = $user->id;
				$user_actor = $this->plSqlServices->getRow($table, $column_name, $column_value);
				$is_actor_empty = empty($user_actor); // Verificar si $user_actor es una cadena vacía ('')
				$is_actor = ($is_actor_empty) ? 'citizen' : 'actor';

                $res_notifications = $this->plSqlServices->getNotifications($fechaActual,$user_data->DEPARTMENT_ID, $user_data->LOCALITY_ID, $edad, $is_actor  );

                if (empty($res_notifications) || $res_notifications=='[]') {

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

                return $this->errorService->userDataNotFound();


            }


        }else{

            return $this->errorService->noUser();


        }


    }
}
