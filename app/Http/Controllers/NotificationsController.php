<?php

namespace App\Http\Controllers;

use App\Http\Requests\Notifications\NewNotificationRequest;
use App\Http\Requests\Notifications\getNotificationsAttachmentsRequest;
use App\Http\Requests\Notifications\notificationReadRequest;
use App\Http\Requests\Notifications\getNotificationsAttachmentNameRequest;
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

        if ($attachment_type!='none'){

            $usuarios_unicos = array_unique($usuarios);

            foreach ($usuarios_unicos as $usuario) {
                
                //$user = User::where('cuil', $usuario)->first();

                Mail::to($usuario)
                ->queue((new NotificationEmail( $message_title, $message_body))
                    ->from('ciudadanodigital@entrerios.gov.ar', 'Ciudadano Digital - Provincia de Entre Ríos')
                    ->subject($message_title)
                    ->attachData($attachment->getPathname(), $attachment->originalName(), ['mime' => $attachment_type]));
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

             
            if ($request->has('attachment')) {

                $table_name = "NOTIFICATIONS";
                $file_type=""; 

                $tipoArchivo = $validated['attachment']->getMimeType();
                #puede ser application/pdf o image/png así que tengo que hacer un explode de aca para abaja
                #el word es application/vnd.openxmlformats-officedocument.wordprocessingml.document y el doc comun es application/msword
                
                $tipoArchivo= explode('/', $tipoArchivo)[1];

                if ($tipoArchivo == 'png' || $tipoArchivo == 'jpg' || $tipoArchivo == 'jpeg'){

                    $file_type="IMG";                    

                }else{

                    $file_type="DOC";
                }


                $send_email_validation='0';
    
                if ($validated['send_by_email']=="true"){
                    $send_email_validation='1';
                }
               
                $columns = "RECIPIENTS, AGE_FROM, AGE_TO, DEPARTMENT_ID, LOCALITY_ID, MESSAGE_TITLE, MESSAGE_BODY, ATTACHMENT_TYPE, MULTIMEDIA_ID, NOTIFICATION_DATE_FROM, NOTIFICATION_DATE_TO, SEND_BY_EMAIL,CREATED_AT";
                $values = "'".$validated['recipients']."',".$validated['age_from'].",".$validated['age_to'].",".$validated['department_id'].",".$validated['locality_id'].",'".$validated['message_title']."','".$validated['message_body']."','".$tipoArchivo."', 0 ,(TO_DATE('".$validated['notification_date_from']."', 'DD/MM/YYYY')),"."(TO_DATE('".$validated['notification_date_to']."', 'DD/MM/YYYY'))".",'".$send_email_validation."',sysdate";
                $insert_notification_row = $this->plSqlServices->insertarFila($table_name, $columns, $values);

                if ($insert_notification_row){

                    $last_id = $this->plSqlServices->getLastId($table_name); 
                    
                    if ($last_id!=null){
    
                        $file_name = $validated['attachment']->getClientOriginalName();
    
                        $notification_attachment = $this->plSqlServices->notificationAttachment($validated['attachment'], $validated['attachment']->getSize(), $file_type, $tipoArchivo, intval($last_id), $file_name); 
                            
                        if ($notification_attachment!= null) {
    
                            if ($validated['send_by_email']=="true"){

                                self::sendNotificationsEmails($validated['recipients'],$validated['age_from'],$validated['age_to'],$validated['department_id'],$validated['locality_id'],$validated['message_title'],$validated['message_body'],$validated['attachment']->getMimeType(),$validated['attachment'],$validated['notification_date_from'],$validated['notification_date_to']);
                                        
                            }
        
                            return response()->json([
                                'status' => true,
                                'message' => 'Notification loaded successfully',
                            ], 201);
    
                        }else{
    
                            return response()->json([
                                'status' => false,
                                'message' => 'No se pudo adjuntar la imagen',
                            ], 400);
                        }
                    }else{

                            return $this->errorService->databaseReadError();


                        }
                }else{

                    return $this->errorService->databaseWriteError();

                }
               
            }else{
                
                $table_name = "NOTIFICATIONS";
                $columns = "RECIPIENTS, AGE_FROM, AGE_TO, DEPARTMENT_ID, LOCALITY_ID, MESSAGE_TITLE, MESSAGE_BODY, ATTACHMENT_TYPE, NOTIFICATION_DATE_FROM, NOTIFICATION_DATE_TO, SEND_BY_EMAIL,CREATED_AT";
                
                $send_email_validation='0';

                if ($validated['send_by_email']=="true"){
                    $send_email_validation='1';
                }
                $values = "'".$validated['recipients']."',".$validated['age_from'].",".$validated['age_to'].",".$validated['department_id'].",".$validated['locality_id'].",'".$validated['message_title']."','".$validated['message_body']."','"."none"."',"."(TO_DATE('".$validated['notification_date_from']."', 'DD/MM/YYYY')),"."(TO_DATE('".$validated['notification_date_to']."', 'DD/MM/YYYY'))".",'".$send_email_validation."',sysdate";

                $res= $this->plSqlServices->insertarFila($table_name, $columns, $values);

                if ($res){

                    if ($validated['send_by_email']=="true"){

                        self::sendNotificationsEmails($validated['recipients'],$validated['age_from'],$validated['age_to'],$validated['department_id'],$validated['locality_id'],$validated['message_title'],$validated['message_body'],"none","none",$validated['notification_date_from'],$validated['notification_date_to']);
                    
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


    public function checkUserNewNotifications(Request $request){

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

                $res_notifications = $this->plSqlServices->getNewNotifications($user->id, $fechaActual,$user_data->DEPARTMENT_ID, $user_data->LOCALITY_ID, $edad, $is_actor  );

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


    public function checkUserAllNotifications(Request $request){

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

                $res_notifications = $this->plSqlServices->getAllNotifications($user->id, $fechaActual,$user_data->DEPARTMENT_ID, $user_data->LOCALITY_ID, $edad, $is_actor  );

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


    public function checkAllNotifications(Request $request){

        $user = Auth::guard('authentication')->user();

        if ($user){

            $table = "USER_ACTORS";
            $column_name = "USER_ID";
            $column_value = $user->id;
            $user_actor = $this->plSqlServices->getRow($table, $column_name, $column_value);
            $is_actor_empty = empty($user_actor); // Verificar si $user_actor es una cadena vacía ('')
            $is_actor = ($is_actor_empty) ? false : true;

            if ($is_actor){

                $fechaActual = Carbon::now()->format('d/m/Y');

                $res_all_active_notifications = $this->plSqlServices->getAllActiveNotifications($fechaActual);
                
                if (empty($res_all_active_notifications) || $res_all_active_notifications=='[]') {

                    return response()->json([
                        'status' => false,
                        'notifications' => "without new notifications"
                    ], 204);

                } else {

                    return response()->json([
                        'status' => true,
                        'notifications' => $res_all_active_notifications
                    ], 200);
                }

            }else{

                return response()->json([
                        'status' => false,
                        'message' => "Access denied"
                    ], 403);

            }
            

        }else{

            return $this->errorService->noUser();

        }
        

    }

    public function getNotificationAttachmentName (getNotificationsAttachmentNameRequest $request){

        $validated = $request->validated();

        $attachment_name = $this->plSqlServices->getgetAttachmentFileNameRow('NOTIFICATIONS_DOC', $validated['multimedia_id'];

        if ($attachment_name){
            return response()->json([
                'status' => true,
                'attachment_name' => $attachment_name
            ], 200);
        }else{

            return $this->errorService->databaseReadError();


        }
    }


    public function getNotificationsAttachments(getNotificationsAttachmentsRequest $request){

        $validated = $request->validated();

        $attachment_file = $this->plSqlServices->getUploadedFile('NOTIFICATIONS_DOC', $validated['multimedia_id'];

        if ($attachment_file){
            return response()->json([
                'status' => true,
                'attachment_file' => $attachment_file
            ], 200);
        }else{

            return $this->errorService->databaseReadError();
        

    }

    public function userNotificationRead ( notificationReadRequest $request){
       
        $validated = $request->validated();

        $user = Auth::guard('authentication')->user();

		if($user){

            $column_name = "ID";
            $column_value = $validated['notification_id'];
            $table = "NOTIFICATIONS";
            $notification = $this->plSqlServices->getRow($table, $column_name, $column_value);

            if (!empty($notification)){

                $notification_id=$notification->ID; 

                $table_name = "USER_NOTIFICATIONS";
				$columns = "USER_ID, NOTIFICATION_ID, CREATED_AT";
				$values = $user->id.','.$notification_id.',sysdate';
				$result = $this->plSqlServices->insertarFila($table_name, $columns, $values);
                
                if ($result) {

                    return response()->json([
                        'status' => true,
                        'message' => "Notification read"
                    ], 200);


                }else{

                    return $this->errorService->databaseWriteError();
                    
                }

            }else{

                return $this->errorService->databaseReadError();
            }
        }else{

            return $this->errorService->noUser();
        }

    }

    public function checkNotificationScope ( checkNotificationScopeRequest $request){

        $validated = $request->validated();

        $res_notifications = $this->plSqlServices->checkNotificationScope($validated['recipients'],  $validated['age_from'], $validated['age_to'], $validated['department_id'], $validated['locality_id'] );


    }


}