<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Services\PlSqlService;


class NotificationsController extends Controller
{

    protected PlSqlService $plSqlServices;

	public function __construct(PlSqlService $plSqlServices)
	{

		$this->plSqlServices = $plSqlServices;
	}


    public function newNotification(Request $request){

        $request->validate([
            "token" => "required|string",

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
            "attachments" => "nullable|string",
            "notification_date_from" => "required|max:50|string",
            "notificaion_date_to" => "required|max:50|string",
        ]);
             
        $host = env('BASEUR_ER_WS_TOKEN');
               
        $response = Http::post($host, [
            '_tk' => $request['token'],
        ]);

        $responseBody = $response->body();

        if($responseBody == 1) {

            if ($request->has('attachments')) {

                $table_name = "NOTIFICATIONS";
                $columns = "RECIPIENTS, AGE_FROM, AGE_TO, DEPARTMENT_ID, LOCALITY_ID, MESSAGE_TITLE, MESSAGE_BODY, ATTACHMENTS, NOTIFICATION_DATE_FROM, NOTIFICATION_DATE_TO, SEND_BY_EMAIL,CREATED_AT";
                $values = "'".$request['recipients']."','".$request['age_from']."','".$request['age_to']."','".$request['department_id']."','".$request['locality_id']."','".$request['message_title']."','".$request['message_body']."','".$request['attachments']."','".$request['notification_date_from']."','".$request['notificaion_date_to']."',sysdate";
                $res= $this->plSqlServices->insertarFila($table_name, $columns, $values);

                if ($res){

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

        }else{

            return response()->json([
                'status' => false,
                'message' => 'Bad token'
            ], 401);

        }
        
    }


    public function checkUserNotifications(Request $request){

        //aca pueden darse varios casos. Si el user es actor, si es ciudadano. Si ha completado su información personal, si no la ha completado solo se ven los broadcast.

        //Lo primero que hay que hacer es obtener todas las notificaciones que no se vencieron
    }

}
