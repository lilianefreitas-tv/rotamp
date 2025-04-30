<?php
session_start();
#if (!isset($_SESSION['usuario_id']) || !in_array($_SESSION['usuario_tipo'], ['admin', 'fiscal'])) {
   # header("Location: ../login.php");
   # exit;
#}
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Calendário de Agendamentos - RotaMP</title>
    <link href="../../assets/css/bootstrap.min.css" rel="stylesheet">

    <!-- FullCalendar moderno carregado localmente -->
    <link href="../../assets/fullcalendar/index.global.min.css" rel="stylesheet" />
    <script src="../../assets/fullcalendar/index.global.min.js"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');

        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'pt-br',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek'
            },
            events: '../admin/eventos.php', // <- Carrega os eventos dinamicamente
            eventClick: function(info) {
                alert('Solicitação #' + info.event.id + '\n' + info.event.title);
            }
        });

        calendar.render();
    });
    </script>
</head>

<body class="bg-light">

<?php include '../menu.php'; ?>

<div class="container mt-5">
    <h1 class="mb-4">Calendário de Agendamentos</h1>
    <div id="calendar"></div>
</div>

<script src="../assets/js/bootstrap.bundle.min.js"></script>
</body>
<br>
<?php include '../../includes/footer.php'; ?>
</html>
