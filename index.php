<!DOCTYPE html>
<html lang="pt_br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Recluso - Tirar Fotos</title>
    <!-- Adicionando Bootstrap e Font Awesome -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background-color: #f8f9fa;
        }
        h1 {
            margin-bottom: 20px;
        }
        #videoElement {
            border: 2px solid #dee2e6;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .btn-custom {
            margin: 5px;
        }
        .hidden {
            display: none;
        }
        #capturedImages img {
            margin-top: 20px;
            border: 2px solid #dee2e6;
            border-radius: 5px;
            max-width: 200px;
            max-height: 200px;
        }
        #capturedImages {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <h1>Cadastro de Recluso - Tirar Fotos</h1>
    
    <!-- Botões de controle da câmera -->
    <div>
        <button id="startButton" class="btn btn-success btn-custom"><i class="fas fa-video"></i> Ligar Câmera</button>
        <button id="stopButton" class="btn btn-danger btn-custom hidden"><i class="fas fa-video-slash"></i> Desligar Câmera</button>
    </div>

    <!-- Botões de captura de fotos -->
    <div id="captureButtons" class="hidden">
        <button id="captureFrontButton" class="btn btn-primary btn-custom"><i class="fas fa-camera"></i> Tirar Foto Frontal</button>
        <button id="captureLeftButton" class="btn btn-primary btn-custom"><i class="fas fa-camera"></i> Tirar Foto Lateral Esquerda</button>
        <button id="captureRightButton" class="btn btn-primary btn-custom"><i class="fas fa-camera"></i> Tirar Foto Lateral Direita</button>
        <button id="saveButton" class="btn btn-info btn-custom hidden"><i class="fas fa-save"></i> Salvar Fotos</button>
        <button id="retryButton" class="btn btn-warning btn-custom hidden"><i class="fas fa-redo"></i> Repetir Fotos</button>
    </div>

    <!-- Visualização da câmera -->
    <video id="videoElement" autoplay width="640" height="480"></video>
    <canvas id="canvasElement" style="display: none;"></canvas>

    <!-- Imagens capturadas -->
    <div id="capturedImages"></div>

    <script>
        const video = document.getElementById('videoElement');
        const canvas = document.getElementById('canvasElement');
        const startButton = document.getElementById('startButton');
        const stopButton = document.getElementById('stopButton');
        const captureButtons = document.getElementById('captureButtons');
        const captureFrontButton = document.getElementById('captureFrontButton');
        const captureLeftButton = document.getElementById('captureLeftButton');
        const captureRightButton = document.getElementById('captureRightButton');
        const saveButton = document.getElementById('saveButton');
        const retryButton = document.getElementById('retryButton');
        const capturedImages = document.getElementById('capturedImages');

        let mediaStream = null;

        // Ligar a câmera
        startButton.addEventListener('click', function() {
            navigator.mediaDevices.getUserMedia({ video: true })
                .then(function(stream) {
                    video.srcObject = stream;
                    mediaStream = stream;
                    startButton.disabled = true;
                    startButton.classList.add('hidden');
                    stopButton.disabled = false;
                    stopButton.classList.remove('hidden');
                    captureButtons.classList.remove('hidden');
                    capturedImages.innerHTML = '';
                })
                .catch(function(err) {
                    console.error('Erro ao acessar a câmera: ', err);
                });
        });

        // Desligar a câmera
        stopButton.addEventListener('click', function() {
            if (mediaStream) {
                mediaStream.getTracks().forEach(track => track.stop());
                video.srcObject = null;
                startButton.disabled = false;
                startButton.classList.remove('hidden');
                stopButton.disabled = true;
                stopButton.classList.add('hidden');
                captureButtons.classList.add('hidden');
                saveButton.classList.add('hidden');
                retryButton.classList.add('hidden');
            }
        });

        // Capturar foto
        function capturePhoto(position) {
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            const ctx = canvas.getContext('2d');
            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
            const dataURL = canvas.toDataURL('image/png');

            const img = document.createElement('img');
            img.src = dataURL;
            img.alt = `Foto ${position}`;
            capturedImages.appendChild(img);

            if (capturedImages.childElementCount === 3) {
                saveButton.classList.remove('hidden');
                retryButton.classList.remove('hidden');
            }
        }

        captureFrontButton.addEventListener('click', function() {
            capturePhoto('Frontal');
        });

        captureLeftButton.addEventListener('click', function() {
            capturePhoto('Lateral Esquerda');
        });

        captureRightButton.addEventListener('click', function() {
            capturePhoto('Lateral Direita');
        });

        // Salvar fotos
        saveButton.addEventListener('click', function() {
            for (let i = 0; i < capturedImages.childElementCount; i++) {
                const img = capturedImages.children[i];
                const link = document.createElement('a');
                link.href = img.src;
                link.download = img.alt + '.png';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            }
        });

        // Repetir fotos
        retryButton.addEventListener('click', function() {
            capturedImages.innerHTML = '';
            saveButton.classList.add('hidden');
            retryButton.classList.add('hidden');
        });
    </script>

    <!-- Adicionando Bootstrap e Font Awesome scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
