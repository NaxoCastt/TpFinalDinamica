<?php
include_once "../configuracion.php";
$objSession = new Session();
$objSession->asignarSerVisitante();
?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>IUPI material didactico</title>
    <link rel="icon" type="image/png" href="/tpfinaldinamica/util/logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body class="bg-light">

    <?php include_once '../estructura/header.php' ?>
    <div class="container py-5" style="min-height: 72vh;">
        <div class="p-5 rounded-4 shadow-lg d-flex flex-column gap-5 position-relative" style="background: linear-gradient(135deg, #fbc2eb, #a6c1ee); border: 4px dashed #fff; box-shadow: 0 0 30px rgba(0,0,0,0.2);">

            <h1 class="text-center text-white display-3 fw-bold animate__animated animate__bounceIn">
                🎉 ¡Bienvenidos a <span style="color: #ffe066;">IUPI</span>! 🎉
            </h1>

            <div class="bg-white rounded-4 p-4 shadow-sm border-start border-5 border-warning">
                <h2 class="text-center text-warning fw-bold mb-3">
                    🧠 ¿Qué es IUPI?
                </h2>
                <p class="text-dark fs-5 lh-lg">
                    <strong>IUPI</strong> no es solo un microemprendimiento… ¡es una fábrica de ideas divertidas! 🎨
                    <br><br>
                    Nacimos para acompañar a <strong>padres, docentes y profesionales</strong> en el mágico desafío de enseñar. Creamos materiales que no solo educan, sino que también hacen reír, pensar y soñar. Desde juegos sensoriales hasta recursos visuales, todo está pensado para que el aprendizaje sea una aventura.
                    <br><br>
                    🎓 En casa, en el aula o en un espacio terapéutico, nuestros materiales son como superhéroes: ayudan a construir conocimientos, valores y emociones. Y lo mejor... ¡vienen con una pizca de magia y toneladas de amor!
                </p>
            </div>

            <div class="bg-white rounded-4 p-4 shadow-sm border-start border-5 border-success">
                <h2 class="text-center text-success fw-bold mb-3">
                    🧩 ¿Qué materiales usamos?
                </h2>
                <p class="text-dark fs-5 lh-lg">
                    En <strong>IUPI</strong> tenemos un universo de materiales tan variado como divertido:
                    <br><br>
                    🪵 Juegos de madera que suenan al tocarlos, <br>
                    💧 Recursos plastificados que sobreviven a meriendas y aventuras, <br>
                    🎲 Propuestas que invitan a construir, clasificar, imaginar y explorar.
                    <br><br>
                    Cada pieza está pensada para ser tocada, mirada y disfrutada. Porque aprender con las manos, los ojos y el corazón... ¡es mucho más divertido!
                </p>
            </div>

            <div class="bg-white rounded-4 p-4 shadow-sm border-start border-5 border-danger">
                <h2 class="text-center text-danger fw-bold mb-3">
                    🎨 ¿Querés tu propio juego?
                </h2>
                <p class="text-dark fs-5 lh-lg">
                    ¡Obvio que sí! En <strong>IUPI</strong> nos encanta crear cosas únicas. Si tenés una idea loca, brillante o simplemente genial para un juego didáctico, ¡contanos!
                    <br><br>
                    💌 Nos escribís, charlamos, soñamos juntos y lo hacemos realidad. Porque cada niño, cada grupo y cada espacio tiene su propia magia… y nosotros queremos ayudarte a encenderla.
                    <br><br>
                    💡 Tu juego ideal está a un mensaje de distancia. ¡Animate a crear con nosotros!
                </p>
            </div>

        </div>
    </div>
    <?php include_once '../estructura/footer.php' ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>