<?php

namespace App\Http\Controllers;

use App\Http\Requests\Notifications\NewNotificationRequest;
use App\Http\Requests\Notifications\CheckNotificationScopeRequest;
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

    
    public function sendNotificationsEmails ($recipients,$age_from,$age_to,$department_id,$locality_id,$message_title,$message_body,$attachments,$notification_date_from,$notificaion_date_to)
    {

        $birthday = Carbon::now()->subYears($age_from);
        $min_fecha_nacimiento = $birthday->format('d/m/Y');

        $birthday = Carbon::now()->subYears($age_to);
        $max_fecha_nacimiento = $birthday->format('d/m/Y');

        $usuarios= explode(",", $this->plSqlServices->getEmailsForNotification($min_fecha_nacimiento, $max_fecha_nacimiento, $locality_id, $department_id,$recipients));
        
        $usuarios_unicos = array_unique($usuarios);
        if (count($usuarios_unicos) > 0 ){

            if (count($attachments) !== 0 && $attachments !== 'none'){

                foreach ($usuarios_unicos as $usuario) {
                    $mail = (new NotificationEmail($message_title, $message_body))
                        ->from('ciudadanodigital@entrerios.gov.ar', 'Ciudadano Digital - Provincia de Entre Ríos')
                        ->subject($message_title);
                
                    foreach ($attachments as $attachment) {
                        $mail->attach($attachment->getPathname(), [
                            'as' => $attachment->getClientOriginalName(),
                            'mime' => $attachment->getClientMimeType(),
                        ]);
                    }
                
                    Mail::to($usuario)->queue($mail);
                }

            }else{

            
                foreach ($usuarios_unicos as $usuario) {
                
                    Mail::to($usuario)
                        ->queue((new NotificationEmail( $message_title, $message_body))
                            ->from('ciudadanodigital@entrerios.gov.ar', 'Ciudadano Digital - Provincia de Entre Ríos')
                            ->subject($message_title));
                }

            }


        }

    }

    public function newNotification(NewNotificationRequest $request){

        $validated = $request->validated();

        $user = Auth::guard('authentication')->user();

        if ($request->has('attachment')) {

                $table_name = "NOTIFICATIONS";
                $file_type=""; 

                $send_email_validation='0';
    
                if ($validated['send_by_email']=="true"){
                    $send_email_validation='1';
                }
               
                //aca elimino el attachment type y debo eliminarlo del json
                $columns = "RECIPIENTS, AGE_FROM, AGE_TO, DEPARTMENT_ID, LOCALITY_ID, MESSAGE_TITLE, MESSAGE_BODY, NOTIFICATION_DATE_FROM, NOTIFICATION_DATE_TO, SEND_BY_EMAIL, CREATED_BY, CREATED_AT";
                $values = "'".$validated['recipients']."',".$validated['age_from'].",".$validated['age_to'].",".$validated['department_id'].",".$validated['locality_id'].",'".$validated['message_title']."','".$validated['message_body']."',(TO_DATE('".$validated['notification_date_from']."', 'DD/MM/YYYY')),"."(TO_DATE('".$validated['notification_date_to']."', 'DD/MM/YYYY'))".",'".$send_email_validation."',".$user->id.",sysdate";
                $insert_notification_row = $this->plSqlServices->insertarFila($table_name, $columns, $values);

                if ($insert_notification_row){

                    $last_id = $this->plSqlServices->getLastId($table_name); 
                    
                    if ($last_id!=null){

                        /////////////////////////////////////////////////////////////////////////////
                        // esto es nuevo
                        $attachments = $request->file('attachment');
                        $totalAttachments = count($attachments);
                        $multimedia_ids = [];
                        
                        for ($i = 0; $i < $totalAttachments; $i++) {
                            
                            $attachment = $attachments[$i];

                            $file_name = $attachments[$i]->getClientOriginalName();
                            
                            $tipoArchivo = $attachments[$i]->getMimeType();
                            $tipoArchivo= explode('/', $tipoArchivo)[1];
                            if ($tipoArchivo == 'png' || $tipoArchivo == 'jpg' || $tipoArchivo == 'jpeg'){
            
                                $file_type="IMG";                    
            
                            }else{
            
                                $file_type="DOC";
                            }

                            $multimedia_ids [] = $this->plSqlServices->notificationAttachment($attachments[$i], $attachments[$i]->getSize(), $file_type, $tipoArchivo, intval($last_id), $file_name); 


                        }

                        if (count($multimedia_ids ) === 0) {

                            return response()->json([
                                'status' => false,
                                'message' => 'No se pudo adjuntar la imagen',
                            ], 400);

                        }else{

                            if ($validated['send_by_email']=="true"){

                                self::sendNotificationsEmails($validated['recipients'],$validated['age_from'],$validated['age_to'],$validated['department_id'],$validated['locality_id'],$validated['message_title'],$validated['message_body'],$validated['attachment'],$validated['notification_date_from'],$validated['notification_date_to']);
                                        
                            }
        
                            return response()->json([
                                'status' => true,
                                'message' => 'Notification loaded successfully',
                            ], 201);

                        }
                        

                        /////////////////////////////////////////////////////////////////////////////////////

                    }else{

                        return $this->errorService->databaseReadError();

                        }
                }else{

                    return $this->errorService->databaseWriteError();

                }
               
            }else{
                

                $table_name = "NOTIFICATIONS";
                $columns = "RECIPIENTS, AGE_FROM, AGE_TO, DEPARTMENT_ID, LOCALITY_ID, MESSAGE_TITLE, MESSAGE_BODY, NOTIFICATION_DATE_FROM, NOTIFICATION_DATE_TO, SEND_BY_EMAIL, CREATED_BY, CREATED_AT";
                
                $send_email_validation='0';

                if ($validated['send_by_email']=="true"){
                    $send_email_validation='1';
                }
                $values = "'".$validated['recipients']."',".$validated['age_from'].",".$validated['age_to'].",".$validated['department_id'].",".$validated['locality_id'].",'".$validated['message_title']."','".$validated['message_body']."',"."(TO_DATE('".$validated['notification_date_from']."', 'DD/MM/YYYY')),"."(TO_DATE('".$validated['notification_date_to']."', 'DD/MM/YYYY'))".",'".$send_email_validation."',".$user->id.",sysdate";

                $res= $this->plSqlServices->insertarFila($table_name, $columns, $values);

                if ($res){

                    if ($validated['send_by_email']=="true"){

                        self::sendNotificationsEmails($validated['recipients'],$validated['age_from'],$validated['age_to'],$validated['department_id'],$validated['locality_id'],$validated['message_title'],$validated['message_body'],"none",$validated['notification_date_from'],$validated['notification_date_to']);
                   
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

    public function getNotificationAttachmentName (Request $request){

        $request->validate([
            "multimedia_id" => "required|numeric",
        ]);


        $attachment_name = $this->plSqlServices->getAttachmentFileName('NOTIFICATIONS_DOC', $request['multimedia_id']);

        if ($attachment_name){

            return response()->json([
                'status' => true,
                'attachment_name' => $attachment_name
            ], 200);

        }else{

            return $this->errorService->databaseReadError();


        }
    }


    public function getNotificationsAttachments(Request $request){

        $request->validate([
            "multimedia_id" => "required|numeric",
        ]);

        $attachment_file = $this->plSqlServices->getUploadedFile('NOTIFICATIONS_DOC', $request['multimedia_id']);

        if ($attachment_file){

            return $attachment_file;

        }else{

            return $this->errorService->databaseReadError();
        }

    }

    public function deleteNotificationsAttachments(Request $request){

        $request->validate([
            "multimedia_id" => "required|numeric",
        ]);

        $attachment_file_deleted = $this->plSqlServices->deleteUploadedFile('NOTIFICATIONS_DOC', $request['multimedia_id']);

        if ($attachment_file_deleted){

            return response()->json([
                'status' => true,
                'message' => 'attachment file deleted'
            ], 200);

        }else{

            return $this->errorService->databaseReadError();
        }

    }

    public function userNotificationRead (Request $request){
       
        $request->validate([

            "notification_id" => "required|numeric",
        ]);


        $user = Auth::guard('authentication')->user();

		if($user){

            $column_name = "ID";
            $column_value = $request['notification_id'];
            $table = "NOTIFICATIONS";
            $notification = $this->plSqlServices->getRow($table, $column_name, $column_value);

            if (!empty($notification)){

                $notification_id=$notification->ID; 

				$result = $this->plSqlServices->readNotification($user->id, $notification_id);
                
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

    public function checkNotificationScope ( CheckNotificationScopeRequest $request){

        $validated = $request->validated();

        $birthday = Carbon::now()->subYears($validated['age_from'] );
        $min_fecha_nacimiento = $birthday->format('d/m/Y');

        $birthday = Carbon::now()->subYears($validated['age_to']  );
        $max_fecha_nacimiento = $birthday->format('d/m/Y');

        $res_notifications_scope = $this->plSqlServices->checkNotificationScope($min_fecha_nacimiento, $max_fecha_nacimiento, $validated['locality_id'], $validated['department_id'], $validated['recipients'] );
       
        if ($res_notifications_scope!=null){

            return response()->json([
                'status' => true,
                'notification_scope' => $res_notifications_scope
            ], 200);

        }else{

            return $this->errorService->databaseReadError();


        }

    }

    public function deleteNotification  (Request $request){
       
        $request->validate([
            "notification_id" => "required|numeric",
        ]);

        $column_name = "ID";
        $column_value = $request['notification_id'];
        $table = "NOTIFICATIONS";
        $notification = $this->plSqlServices->getRow($table, $column_name, $column_value);

        if ($notification){

            if ($notification->MULTIMEDIA_ID !=null  ){

                $multimediaIDs = explode(",", $notification->MULTIMEDIA_ID);

                foreach ($multimediaIDs as $elemento) {

                    $attachment_file_deleted = $this->plSqlServices->deleteUploadedFile('NOTIFICATIONS_DOC', $elemento);

                    if (!$attachment_file_deleted ){

                        return response()->json([
                            'status' => true,
                            'message' => "delete file error"
                        ], 400);
    
                    }
                }

            }
              //borrar implica un softdelete
              $delete_notification = $this->plSqlServices->deleteNotification($notification->ID);
              if ($delete_notification){

                  return response()->json([
                      'status' => true,
                      'notification_deleted' => true
                  ], 200);

              }else{

                  return $this->errorService->databaseWriteError();

              }

        }else{

            return $this->errorService->databaseReadError();

        }

    }

    public function notificationReached (Request $request){
       
        $request->validate([
            "notification_id" => "required|numeric",
        ]);

        $users_notification_reached = $this->plSqlServices->notificationUsersReached($request['notification_id']);

        if ($users_notification_reached > 0){

            return response()->json([
                'status' => true,
                'notification_reached' => $users_notification_reached
            ], 200);

        }else{

            return response()->json([
                'status' => true,
                'notification_reached' => 0
            ], 200);

        }

    }

}