<?php

/**
 * Created by PhpStorm.
 * User: imyuvii
 * Date: 27/10/16
 * Time: 5:06 PM
 */
class NotificationHelper
{
    public static function AddNotitication($title, $body, $notiticationType, $sender, $isAdmin,$url) {
        $notitication =  new NotificationManager();
        $notitication->title_html = $title;
        $notitication->body_html = $body;
        $notitication->type_of_notification = $notiticationType;
        $notitication->sender_Id = $sender;
        $notitication->isAdmin = $isAdmin;
        $notitication->url = $url;
        $notitication->is_unread = 1;
        $notitication->is_delete = 0;
        $notitication->created_at = date('Y-m-d H:i:s');
        if ($notitication->validate()){
            if ($notitication->save()){
                return $notitication->id;
            }
        }else{
            return $notitication->getErrors();
        }
    }

    public static function ShowNotitication() {
        $notitication =  NotificationManager::model()->findAll(array('order' => 'is_unread desc','condition' => 'is_delete = 0'));
        return $notitication;
    }

    public static function time_elapsed_string($datetime, $full = false) {
        $now = new DateTime;
        $ago = new DateTime($datetime);
        $diff = $now->diff($ago);

        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;

        $string = array(
            'y' => 'year',
            'm' => 'month',
            'w' => 'week',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second',
        );
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
            } else {
                unset($string[$k]);
            }
        }

        if (!$full) $string = array_slice($string, 0, 1);
        return $string ? implode(', ', $string) . ' ago' : 'just now';
    }

    public static function getNewNotification(){
        $notiticationCount =  NotificationManager::model()->findAll(array('condition' => 'is_unread = 1 AND is_delete = 0'));
        return count($notiticationCount);
    }
}