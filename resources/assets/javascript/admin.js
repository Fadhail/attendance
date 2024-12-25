function toggleForm() {
    const form = document.getElementById('tambahMahasiswa');
    form.classList.toggle('hidden');
}

let capturedImages = [];
function captureImage() {
    const video = document.getElementById('camera');
    const canvas = document.getElementById('canvas');
    const context = canvas.getContext('2d');
    const imagesContainer = document.getElementById('images');

    if (capturedImages.length >= 5) {
        alert('You can only capture up to 5 images.');
        return;
    }

    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
    context.drawImage(video, 0, 0, canvas.width, canvas.height);
    const dataURL = canvas.toDataURL('image/png');
    capturedImages.push(dataURL);

    const img = document.createElement('img');
    img.src = dataURL;
    img.style.width = '100px';
    img.style.margin = '5px';
    imagesContainer.appendChild(img);

    document.getElementById(`capturedImage${capturedImages.length}`).value = dataURL;

    if (capturedImages.length === 5) {
        document.getElementById('submitBtn').disabled = false;
    }
}

// Access camera
function enableCamera() {
    navigator.mediaDevices.getUserMedia({ video: true }).then((stream) => {
        const video = document.getElementById('camera');
        video.srcObject = stream;
    }).catch((err) => {
        console.error('Error accessing camera:', err);
    });
}

window.onload = enableCamera;