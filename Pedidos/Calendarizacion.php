<?PHP
include("../bd/inicia_conexion.php");
include("../includes/header.php");
?>

<!-- CSS -->
<link rel="stylesheet" type="text/css" href="../evo-calendar/css/evo-calendar.min.css">
<link rel="stylesheet" type="text/css" href="../evo-calendar/css/evo-calendar.orange-coral.min.css">
<link rel="stylesheet" type="text/css" href="../evo-calendar/css/evo-calendar.midnight-blue.min.css">
<link rel="stylesheet" type="text/css" href="../evo-calendar/css/evo-calendar.royal-navy.min.css">

<!-- Fonts -->
<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Fira+Mono&display=swap" rel="stylesheet">
<div style="position: sticky;">
    <div class="container">
        <div class="col-xxl-12 col-xl-12 mb-12">
            <div class="card h-100">
                <div id="calendario"></div>
            </div>
        </div>
    </div>
</div>
<br>
<div class="row justify-content-center">
    <button  type="button" class="btn btn-outline-danger" onclick="clickBotonDefault()">
        Default
    </button>
    <div class="p-2"></div>
    <button type="button" class="btn btn-outline-secondary" onclick="clickBotonMidnight()">Midnight Blue</button>
    <div class="p-2"></div>
    <button type="button" class="btn btn-outline-warning" onclick="clickBotonRoyal()">Royal Navy</button>
    <div class="p-2"></div>
    <button type="button" class="btn btn-outline-primary" onclick="clickBotonCoral()">Blue Coral</button>

</div>


<?PHP
include("../includes/footer_calendarizacion.php");
include("../bd/fin_conexion.php");
?>