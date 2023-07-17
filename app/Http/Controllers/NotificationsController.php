<?php

namespace App\Http\Controllers;

use App\Http\Requests\Notifications\NewNotificationRequest;
use App\Http\Requests\Notifications\CheckNotificationScopeRequest;
use Illuminate\Http\Request;
use App\Repositories\PLSQL\GenericRepository;
use App\Repositories\PLSQL\NotificationRepository;
use App\Http\Services\ErrorService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Mail;
use App\Mail\NotificationEmail;
use App\Models\User;

class NotificationsController extends Controller
{

    protected GenericRepository $genericRepository;
    protected NotificationRepository $notificationRepository;
    private ErrorService $errorService;

    public function __construct(GenericRepository $genericRepository, NotificationRepository $notificationRepository, ErrorService $errorService)
    {
        $this->genericRepository = $genericRepository;
        $this->notificationRepository=$notificationRepository;
        $this->errorService = $errorService;
    }

    public function sendNotificationsEmails ($recipients, $age_from, $age_to, $department_id, $locality_id, $message_title, $message_body, $attachments = null, $notification_date_from, $notificaion_date_to)
    {
        $uniqueUsers = $this->getUniqueUsers($age_from, $age_to, $locality_id, $department_id, $recipients);

        if (count($uniqueUsers) > 0) {
            $this->sendMails($uniqueUsers, $message_title, $message_body, $attachments);
        }
    }

    public function newNotification(NewNotificationRequest $request){
        $validated = $request->validated();
        $user = Auth::guard('authentication')->user();

        $send_email_validation = $validated['send_by_email']=="true" ? '1' : '0';

        $table_name = "NOTIFICATIONS";
        $columns = "RECIPIENTS, AGE_FROM, AGE_TO, DEPARTMENT_ID, LOCALITY_ID, MESSAGE_TITLE, MESSAGE_BODY, NOTIFICATION_DATE_FROM, NOTIFICATION_DATE_TO, SEND_BY_EMAIL, CREATED_BY, CREATED_AT";
        $values = "'".$validated['recipients']."',".$validated['age_from'].",".$validated['age_to'].",".$validated['department_id'].",".$validated['locality_id'].",'".$validated['message_title']."','".$validated['message_body']."',"."(TO_DATE('".$validated['notification_date_from']."', 'DD/MM/YYYY')),"."(TO_DATE('".$validated['notification_date_to']."', 'DD/MM/YYYY'))".",'".$send_email_validation."',".$user->id.",sysdate";

        $insert_notification_row = $this->genericRepository->insertarFila($table_name, $columns, $values);

        if ($insert_notification_row){
            if ($request->has('attachment')) {
                $last_id = $this->genericRepository->getLastId($table_name);
                if ($last_id!=null){
                    $attachments = $request->file('attachment');
                    $multimedia_ids = $this->handleAttachments($last_id, $attachments);

                    if (count($multimedia_ids ) === 0) {
                        return response()->json([
                            'status' => false,
                            'message' => 'No se pudo adjuntar la imagen',
                        ], 400);
                    }
                }
                else{
                    return $this->errorService->databaseReadError();
                }
            }
            
            if ($validated['send_by_email']=="true"){
                self::sendNotificationsEmails($validated['recipients'],$validated['age_from'],$validated['age_to'],$validated['department_id'],$validated['locality_id'],$validated['message_title'],$validated['message_body'], $attachments ?? "none",$validated['notification_date_from'],$validated['notification_date_to']);
            }

            return response()->json([
                'status' => true,
                'message' => 'Notification loaded successfully',
            ], 201);

        }else{
            return $this->errorService->databaseWriteError();
        }
    }

    private function getUniqueUsers($age_from, $age_to, $locality_id, $department_id, $recipients){
        $minFechaNacimiento = Carbon::now()->subYears($age_from)->format('d/m/Y');
        $maxFechaNacimiento = Carbon::now()->subYears($age_to)->format('d/m/Y');

        $usuarios = explode(",", $this->notificationRepository->getEmailsForNotification($minFechaNacimiento, $maxFechaNacimiento, $locality_id, $department_id,$recipients));
        
        return array_unique($usuarios);
    }

    private function sendMails($uniqueUsers, $message_title, $message_body, $attachments){
        foreach ($uniqueUsers as $user) {
            $mail = (new NotificationEmail($message_title, $message_body))
                ->from('ciudadanodigital@entrerios.gov.ar', 'Ciudadano Digital - Provincia de Entre Ríos')
                ->subject($message_title);

            if (is_array($attachments) && count($attachments) !== 0 && $attachments !== 'none'){
                foreach ($attachments as $attachment) {
                    $mail->attach($attachment->getPathname(), [
                        'as' => $attachment->getClientOriginalName(),
                        'mime' => $attachment->getClientMimeType(),
                    ]);
                }
            }
        
            Mail::to($user)->queue($mail);
        }
    }

    private function handleAttachments($last_id, $attachments){
        $totalAttachments = count($attachments);
        $multimedia_ids = [];
        
        for ($i = 0; $i < $totalAttachments; $i++) {
            $attachment = $attachments[$i];
            $file_name = $attachment->getClientOriginalName();
            $file_type = $this->getFileType($attachment);
            $multimedia_ids [] = $this->notificationRepository->notificationAttachment($attachment, $attachment->getSize(), $file_type, $tipoArchivo, intval($last_id), $file_name);
        }

        return $multimedia_ids;
    }

    private function getFileType($attachment){
        $tipoArchivo = $attachment->getMimeType();
        $tipoArchivo= explode('/', $tipoArchivo)[1];

        return ($tipoArchivo == 'png' || $tipoArchivo == 'jpg' || $tipoArchivo == 'jpeg') ? "IMG" : "DOC";
    }


    public function checkUserNewNotifications(Request $request)
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->errorService->noUser();
        }

        $userData = $this->getUserData($user->id);
        if (empty($userData)) {
            return $this->errorService->userDataNotFound();
        }

        $isActor = $this->isUserActor($user->id);
        $edad = $this->calculateAge($userData->BIRTHDAY);
        $fechaActual = Carbon::now()->format('d/m/Y');

        $notifications = $this->notificationRepository->getNewNotifications($user->id, $fechaActual, $userData->DEPARTMENT_ID, $userData->LOCALITY_ID, $edad, $isActor);

        return $this->prepareNotificationResponse($notifications);
    }

    public function checkUserAllNotifications(Request $request)
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->errorService->noUser();
        }

        $userData = $this->getUserData($user->id);
        if (empty($userData)) {
            return $this->errorService->userDataNotFound();
        }

        $isActor = $this->isUserActor($user->id);
        $edad = $this->calculateAge($userData->BIRTHDAY);
        $fechaActual = Carbon::now()->format('d/m/Y');

        $notifications = $this->notificationRepository->getAllNotifications($user->id, $fechaActual, $userData->DEPARTMENT_ID, $userData->LOCALITY_ID, $edad, $isActor);

        return $this->prepareNotificationResponse($notifications);
    }

    public function checkAllNotifications(Request $request)
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->errorService->noUser();
        }

        $isActor = $this->isUserActor($user->id);
        if (!$isActor) {
            return response()->json(['status' => false, 'message' => "Access denied"], 403);
        }

        $fechaActual = Carbon::now()->format('d/m/Y');
        $notifications = $this->notificationRepository->getAllActiveNotifications($fechaActual);

        return $this->prepareNotificationResponse($notifications);
    }

    private function getUser()
    {
        return Auth::guard('authentication')->user();
    }

    private function getUserData($userId)
    {
        return $this->genericRepository->getRow("USER_CONTACT", "USER_ID", $userId);
    }

    private function isUserActor($userId)
    {
        $userActor = $this->genericRepository->getRow("USER_ACTORS", "USER_ID", $userId);
        return empty($userActor) ? 'citizen' : 'actor';
    }

    private function calculateAge($birthday)
    {
        return Carbon::parse($birthday)->age;
    }

    private function prepareNotificationResponse($notifications)
    {
        if (empty($notifications) || $notifications == '[]') {
            return response()->json(['status' => false, 'notifications' => "without new notifications"], 204);
        } else {
            return response()->json(['status' => true, 'notifications' => $notifications], 200);
        }
    }
    public function getNotificationAttachmentName(Request $request)
    {
        $request->validate(["multimedia_id" => "required|numeric"]);
        $attachment_name = $this->getAttachment('NOTIFICATIONS_DOC', $request['multimedia_id']);
        return $this->prepareAttachmentResponse($attachment_name);
    }

    public function getNotificationsAttachments(Request $request)
    {
        $request->validate(["multimedia_id" => "required|numeric"]);
        $attachment_file = $this->getAttachment('NOTIFICATIONS_DOC', $request['multimedia_id']);
        return $this->prepareAttachmentResponse($attachment_file);
    }

    public function deleteNotificationsAttachments(Request $request)
    {
        $request->validate(["multimedia_id" => "required|numeric"]);
        $attachment_file_deleted = $this->deleteAttachment('NOTIFICATIONS_DOC', $request['multimedia_id']);
        return $this->prepareDeletedAttachmentResponse($attachment_file_deleted);
    }

    public function userNotificationRead(Request $request)
    {
        $request->validate(["notification_id" => "required|numeric"]);
        $user = $this->getUser();
        if (!$user) {
            return $this->errorService->noUser();
        }
        $notification = $this->getRow('NOTIFICATIONS', 'ID', $request['notification_id']);
        if (empty($notification)) {
            return $this->errorService->databaseReadError();
        }
        $result = $this->notificationRepository->readNotification($user->id, $notification->ID);
        return $this->prepareReadNotificationResponse($result);
    }

    public function checkNotificationScope(CheckNotificationScopeRequest $request)
    {
        $validated = $request->validated();
        $min_fecha_nacimiento = Carbon::now()->subYears($validated['age_from'])->format('d/m/Y');
        $max_fecha_nacimiento = Carbon::now()->subYears($validated['age_to'])->format('d/m/Y');
        $res_notifications_scope = $this->notificationRepository->checkNotificationScope($min_fecha_nacimiento, $max_fecha_nacimiento, $validated['locality_id'], $validated['department_id'], $validated['recipients']);
        return $this->prepareNotificationScopeResponse($res_notifications_scope);
    }

    public function deleteNotification(Request $request)
    {
        $request->validate(["notification_id" => "required|numeric"]);
        $notification = $this->getRow('NOTIFICATIONS', 'ID', $request['notification_id']);
        if ($notification) {
            return $this->handleNotificationDelete($notification);
        }
        return $this->errorService->databaseReadError();
    }

    private function getAttachment($tableName, $multimedia_id)
    {
        return $this->notificationRepository->getAttachmentFileName($tableName, $multimedia_id);
    }

    private function deleteAttachment($tableName, $multimedia_id)
    {
        return $this->notificationRepository->deleteUploadedFile($tableName, $multimedia_id);
    }

    private function prepareAttachmentResponse($attachment)
    {
        if ($attachment) {
            return response()->json(['status' => true, 'attachment_name' => $attachment], 200);
        }
        return $this->errorService->databaseReadError();
    }

    private function prepareDeletedAttachmentResponse($attachment_deleted)
    {
        if ($attachment_deleted) {
            return response()->json(['status' => true, 'message' => 'attachment file deleted'], 200);
        }
        return $this->errorService->databaseReadError();
    }

    private function prepareReadNotificationResponse($result)
    {
        if ($result) {
            return response()->json(['status' => true, 'message' => "Notification read"], 200);
        }
        return $this->errorService->databaseWriteError();
    }

    private function prepareNotificationScopeResponse($res_notifications_scope)
    {
        if ($res_notifications_scope != null) {
            return response()->json(['status' => true, 'notification_scope' => $res_notifications_scope], 200);
        }
        return $this->errorService->databaseReadError();
    }

    private function getRow($table, $column_name, $column_value)
    {
        return $this->genericRepository->getRow($table, $column_name, $column_value);
    }

    private function handleNotificationDelete($notification)
    {
        if ($notification->MULTIMEDIA_ID != null) {
            $multimediaIDs = explode(",", $notification->MULTIMEDIA_ID);
            foreach ($multimediaIDs as $elemento) {
                $attachment_file_deleted = $this->deleteAttachment('NOTIFICATIONS_DOC', $elemento);
                if (!$attachment_file_deleted) {
                    return response()->json(['status' => true, 'message' => "delete file error"], 400);
                }
            }
        }
        $delete_notification = $this->notificationRepository->deleteNotification($notification->ID);
        if ($delete_notification) {
            return response()->json(['status' => true, 'notification_deleted' => true], 200);
        }
        return $this->errorService->databaseWriteError();
    }

    public function notificationReached(Request $request)
    {
        $request->validate(["notification_id" => "required|numeric"]);
        $users_notification_reached = $this->notificationRepository->notificationUsersReached($request['notification_id']);
        $users_notification_reached = $users_notification_reached > 0 ? $users_notification_reached : 0;
        return response()->json(['status' => true, 'notification_reached' => $users_notification_reached], 200);
    }
}