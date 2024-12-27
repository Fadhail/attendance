<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Presensi</title>
    <script src="resources/assets/javascript/presensi.js" defer></script>
    <script src="resources/assets/javascript/face-api.min.js" defer></script>
</head>

<body>
    <?php include 'includes/sidebar.php'; ?>
    <div class="p-4 sm:ml-64">
        <div class="overflow-x-auto relative shadow-md sm:rounded-lg bg-white p-6 rounded-lg">
            <div class="videoContainer" style="display: none;">
                <video id="video" width="600" height="450" autoplay></video>
                <canvas id="overlay"></canvas>
            </div>
            <button id="startButton">Start</button>
        </div>
    </div>
</body>

</html>