<?php

$GLOBALS['conn'] = mysqli_connect('localhost','root','123','alif_test');

if($GLOBALS['conn']){
    $rooms_option = '';
    $client_option = '';
    $sql = 'select * from room where status = 1'; //выбрать только исправные помещение
    $sql_cl = 'select * from client where status = 1'; //выбор существующих клиентов
    $query = mysqli_query($GLOBALS['conn'],$sql);
    $query2 = mysqli_query($GLOBALS['conn'],$sql_cl);
    while ($row = $query->fetch_assoc()){
        $rooms_option .= '<option value="'.$row['id'].'">'.$row['name'].'</option>';
    }
    while ($row2 = $query2->fetch_assoc()){
        $client_option .= '<option value="'.$row2['id'].'">'.$row2['name'].'</option>';
    }

} else {
    echo 0;
}


/*if(isset($_POST['bron_room_btn'])){
    $start_time = date('Y-m-d H:s:i',strtotime($_POST['time_start']));
    $end_time = date('Y-m-d H:s:i',strtotime($_POST['time_end']));
    $room_status = check_room($_POST['room_number'], $_POST['time_start'],$_POST['time_end']);
    if(in_array($room_status, [1,2])){
        $insert_result = $GLOBALS['conn']->query('INSERT INTO bron (room_id, time_of_bron, time_of_free, client_id, room_stat) 
                                    values ('.$_POST['room_number'].',"'.$start_time.'","'.$end_time.'", 1, 1) ');
        echo $insert_result ? 'Комната успешно забронирована!' : 'Не удалось забронировать ошибка базы!';
    } else {
        echo 'Комната занята, невозможно забронировать!';
    }
}*/

/*if(isset($_POST['check_room_btn'])) {
    $room_status = check_room($_POST['room_number'], $_POST['time_start'], $_POST['time_end']);
    if(in_array($room_status, [1,2])){
        echo 'Комната свободна!';
    } else {
        echo 'Комната занята';
    }
}*/

?>

<link rel="stylesheet" href="css/bootstrap3.3.7.min.css">
<script src="js/jquery.js"></script>
<script src="js/jquery.validate.min.js"></script>
<script src="js/sweetalert.js"></script>
<link href="css/sweetalert.css" rel="stylesheet"/>
<style>
    .error {
       color: red;
    }
</style>

<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <h3 class="text-center text-primary text-bold">Бронирование комнат</h3>
            <hr>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <form action="" method="post" id="room_bron_frm">
                <div class="col-lg-3">
                    <div class="form-group">
                        <label for="room_number" class="control-label">Номер комнаты</label>
                        <select name="room_number" id="room_number" class="form-control">
                            <option value="">Не выбрано</option>
                            <?= $rooms_option != '' ? $rooms_option : ''?>
                        </select>
                    </div>
                </div>

                <div class="col-lg-3">
                    <div class="form-group">
                        <label for="client" class="control-label">Клиент</label>
                        <select name="client" id="client" class="form-control">
                            <option value="">Не выбрано</option>
                            <?= $client_option != '' ? $client_option : ''?>
                        </select>
                    </div>
                </div>

                <div class="col-lg-3">
                    <div class="form-group">
                        <label for="time_start" class="control-label">Время старта бронирования</label>
                        <input type="datetime-local" id="time_start" name="time_start" class="form-control">
                    </div>
                </div>

                <div class="col-lg-3">
                    <div class="form-group">
                        <label for="time_end" class="control-label">Время конец бронирования</label>
                        <input type="datetime-local" id="time_end" name="time_end" class="form-control">
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="form-group">
                        <label class="control-label">Проверка комнаты</label>
                        <button type="button" name="check_room_btn" class="form-control btn btn-primary" onclick="check_room()">проверить комнату</button>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <label class="control-label">Бронировать</label>
                        <button type="button" name="bron_room_btn" class="form-control btn btn-success" onclick="bron_room()">Бронировать</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    function show_scoring_form(result, type) {
        swal({
            title: (type == 0 || type == 3) ? "Ошибка!" : "Все готово!",
            text: result,
            type: (type == 0 || type == 3) ? 'error' : 'success'
        },
        function(isConfirm) {
            if (isConfirm) {
                location.reload();
            }
        });
    }

    function check_room() {
        $('#room_bron_frm').validate({
            rules: {
                room_number: {
                    required: true
                },
                time_start: {
                    required: true
                },
                time_end: {
                    required: true
                }
            },
            messages: {
                room_number: {
                    required: "Пожалуйста укажите номер комнаты"
                },
                time_start: {
                    required: "Пожалуйста выберите дату начало брона"
                },
                time_end: {
                    required: "Пожалуйста выберите дату окончание брона"
                }
            }
        });
        var isValid = $('#room_bron_frm').valid();

        if(isValid) {
            $.ajax({
                url: 'check_room.php',
                type: 'POST',
                data: {
                    room_number: $('#room_number').val(),
                    time_start: $('#time_start').val(),
                    time_end: $('#time_end').val()
                },
                success: function (data) {
                    var data = JSON.parse(data);
                    if($.isArray(data)){
                        show_scoring_form('Данный период комната занята (' + data[1] + ') - ом До ,(' + data[2] + ') нельзя бронировать', data[0]);
                    }
                    switch (data) {
                        case 1:
                            show_scoring_form('Данный период комната свободна можно бронировать', 1);
                            break;
                        case 2:
                            show_scoring_form('Данная комната свободна можно бронировать, ранее не была в броне', 2);
                            break;
                        case 3:
                            show_scoring_form('Выберите правильный период!', 3);
                            break;
                    }
                },
                error: function (data) {
                    alert('Ошибка сервера: ' + data);
                }
            });
        } else {
            return false;
        }
    }

    function bron_room() {
        $('#room_bron_frm').validate({
            rules: {
                room_number: {
                    required: true
                },
                time_start: {
                    required: true
                },
                time_end: {
                    required: true
                },
                client: {
                    required: true
                }
            },
            messages: {
                room_number: {
                    required: "Пожалуйста укажите номер комнаты"
                },
                time_start: {
                    required: "Пожалуйста выберите дату начало брона"
                },
                time_end: {
                    required: "Пожалуйста выберите дату окончание брона"
                },
                client: {
                    required: "Выберите Клиента"
                }
            }
        });
        var isValid = $('#room_bron_frm').valid();

        if(isValid) {
            $.ajax({
                url: 'bron_room.php',
                type: 'POST',
                data: {
                    room_number: $('#room_number').val(),
                    client: $('#client').val(),
                    time_start: $('#time_start').val(),
                    time_end: $('#time_end').val()
                },
                success: function (data) {
                    var data = JSON.parse(data);
                    if($.isArray(data)){
                        show_scoring_form('Данный период комната занята (' + data[1] + ') - ом До ,(' + data[2] + ') нельзя бронировать', data[0]);
                    }

                    switch (data) {
                        case 1:
                            show_scoring_form('Комната успешно забронирована', 1);
                            break;
                        case 2:
                            show_scoring_form('Комната успешно забронирована, и ранее никогда не была в броне', 2);
                            break;
                        case 3:
                            show_scoring_form('Выберите правильный период', 3);
                            break;
                    }
                },
                error: function (data) {
                    alert('Ошибка сервера: ' + data);
                }
            });
        } else {
            return false;
        }
    }



</script>

