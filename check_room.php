<?php
 //1
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
        if($count_row > 0){
            $time_of_free = strtotime($result['time_of_free']);
            $client = $result['client'];
            $time_off = $result['time_of_free'];
            $error = [0, $client, $time_off];
            if($time_of_free < $start_time){
                return 1; //данный период комната свободна можно бронировать
            }
            else {
                echo json_encode($error);
                //print_r($error); //данный период комната занята нельзя бронировать
            }
        } else {
            return 2; //данная комната свободна можно бронировать
        }
    }
}


$room_status = check_room($_POST['room_number'], $_POST['time_start'], $_POST['time_end']);
echo $room_status;
/*if(in_array($room_status, [1,2])){
    echo 'Комната свободна!';
} else {
    echo 'Комната занята';
}*/