<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require './vendor/phpmailer/phpmailer/src/Exception.php';
require './vendor/phpmailer/phpmailer/src/PHPMailer.php';
require './vendor/phpmailer/phpmailer/src/SMTP.php';

$GLOBALS['conn'] = mysqli_connect('localhost','root','123','alif_test');

function check_room($room_id,$time_start, $time_end){
    $sql_bron = 'SELECT b.*, c.name as client  FROM bron b left join client c on c.id = b.client_id where room_id = '.$room_id.' order by b.time_of_free DESC LIMIT 1';
    $query = mysqli_query($GLOBALS['conn'], $sql_bron);
    $result = $query->fetch_assoc();
    $count_row = $query->num_rows;
    $start_time = strtotime($time_start);
    $end_time = strtotime($time_end);

    if ($end_time <= $start_time){
        return 3; //Выберите правильный период
    }
    else {
        if ($count_row > 0) {
            $time_of_free = strtotime($result['time_of_free']);
            $client = $result['client'];
            $time_off = $result['time_of_free'];
            $error = [0, $client, $time_off];
            if ($time_of_free < $start_time) {
                return 1; //данный период комната свободна можно бронировать
            } elseif ($end_time <= $start_time) {
                return 3; //Выберите правильный период
            } else {
                echo json_encode($error);
                //return 0; //данный период комната занята нельзя бронировать
            }
        } else {
            return 2; //данная комната свободна можно бронировать
        }
    }
}


$start_time = date('Y-m-d H:s:i',strtotime($_POST['time_start']));
$end_time = date('Y-m-d H:s:i',strtotime($_POST['time_end']));
$room_id = $_POST['room_number'];
$client_id = $_POST['client'];

$room_status = check_room($_POST['room_number'], $_POST['time_start'],$_POST['time_end']);
if(in_array($room_status, [1,2])){
    $sql_get_mail = 'select `name` as client_name, email from client where id = '.$client_id.' ';
    $mail_query = mysqli_query($GLOBALS['conn'],$sql_get_mail);
    $res = $mail_query->fetch_assoc();

    $insert_result = $GLOBALS['conn']->query('INSERT INTO bron (room_id, time_of_bron, time_of_free, client_id) 
                                    values ('.$room_id.',"'.$start_time.'","'.$end_time.'", '.$client_id.') ');

    if (filter_var($res['email'], FILTER_VALIDATE_EMAIL)) {

        try {
            $email = new PHPMailer();
            $email->AddReplyTo('noreply@imon.tj', 'Alif Test');
            $email->AddAddress($res['email'], $res['client_name']);
            $email->SetFrom('noreply@imon.tj', 'Alif Test');
            $email->CharSet = 'UTF-8';
            $email->Subject = 'Бронирование комнат онлайн';
            $email->Body = 'Уважаемый '.$res['client_name'].' вами забронирована комната с номером '.$room_id.' период с '.$start_time.'  до '.$end_time.' ';
            $email->Send();
        } catch (phpmailerException $e) {
            echo $e->errorMessage(); //Pretty error messages from PHPMailer
        } catch (Exception $e) {
            echo $e->getMessage(); //Boring error messages from anything else!
        }
    } else{
        return 'Неверный адресс почты!';
    }

    //echo $insert_result ? 'Комната успешно забронирована!' : 'Не удалось забронировать ошибка базы!';
} else {
    //echo 'Комната занята, невозможно забронировать!';
}

echo $room_status;