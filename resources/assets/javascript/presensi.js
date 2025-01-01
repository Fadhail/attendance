var labels = [];
let detectedFaces = [];
let sendingData = false;

function updateTable() {
  var selectedFakultas = document.getElementById("fakultas").value;
  var selectedKelas = document.getElementById("kelas").value;
  var xhr = new XMLHttpRequest();
  xhr.open("POST", "resources/pages/dosen/validate.php", true);
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

  xhr.onreadystatechange = function () {
    if (xhr.readyState === 4) {
      if (xhr.status === 200) {
        try {
          var response = JSON.parse(xhr.responseText);
          if (response.status === "success") {
            labels = response.data;

            if (selectedFakultas && selectedKelas) {
              updateOtherElements();
            }
            document.getElementById("tabelMahasiswa").innerHTML = response.html;
          }
        } catch (e) {
          console.error("Failed to parse JSON:", e);
        }
      } else {
        console.error("Error:", xhr.statusText);
      }
    }
  };
  xhr.send("id_fakultas=" + encodeURIComponent(selectedFakultas) + "&id_kelas=" + encodeURIComponent(selectedKelas));
}

function markAttendance(detectedFaces) {
  document.querySelectorAll("#tabelMahasiswa tr").forEach((row) => {
    const npm = row.cells[0].innerText.trim();
    if (detectedFaces.includes(npm)) {
      row.cells[4].innerText = "present";
    }
  });
}

function updateOtherElements() {
  const video = document.getElementById("video");
  const videoContainer = document.querySelector(".video-container");
  const startButton = document.getElementById("startButton");
  let webcamStarted = false;
  let modelsLoaded = false;

  Promise.all([
    faceapi.nets.ssdMobilenetv1.loadFromUri("models"),
    faceapi.nets.faceRecognitionNet.loadFromUri("models"),
    faceapi.nets.faceLandmark68Net.loadFromUri("models"),
  ])
    .then(() => {
      modelsLoaded = true;
      console.log("models loaded successfully");
    })
    .catch(() => {
      alert("models not loaded, please check your model folder location");
    });

  startButton.addEventListener("click", async () => {
    videoContainer.style.display = "flex";
    if (!webcamStarted && modelsLoaded) {
      startWebcam();
      webcamStarted = true;
    }
  });

  function startWebcam() {
    navigator.mediaDevices
      .getUserMedia({
        video: true,
        audio: false,
      })
      .then((stream) => {
        video.srcObject = stream;
        videoStream = stream;
      })
      .catch((error) => {
        console.error(error);
      });
  }

  async function getLabeledFaceDescriptions() {
    const labeledDescriptors = [];

    for (const label of labels) {
      const descriptions = [];

      for (let i = 1; i <= 5; i++) {
        try {
          const npm = String(label);
          const img = await faceapi.fetchImage(`resources/labels/${npm}/${i}_${npm}.png`);
          const detections = await faceapi.detectSingleFace(img).withFaceLandmarks().withFaceDescriptor();

          if (detections) {
            descriptions.push(detections.descriptor);
          } else {
            console.log(`${npm}/${i}_${npm}.png tidak terdeteksi`);
          }
        } catch (error) {
          console.error(`${npm}/${i}_${npm}.png`, error);
        }
      }

      if (descriptions.length > 0) {
        labeledDescriptors.push(new faceapi.LabeledFaceDescriptors(String(label), descriptions));
      }
    }

    return labeledDescriptors;
  }

  video.addEventListener("play", async () => {
    const labeledFaceDescriptors = await getLabeledFaceDescriptions();

    if (labeledFaceDescriptors.length === 0) {
      return;
    }

    const faceMatcher = new faceapi.FaceMatcher(labeledFaceDescriptors);

    const canvas = faceapi.createCanvasFromMedia(video);
    canvas.style.position = "absolute";
    videoContainer.appendChild(canvas);

    const displaySize = { width: video.width, height: video.height };
    faceapi.matchDimensions(canvas, displaySize);

    setInterval(async () => {
      const detections = await faceapi.detectAllFaces(video).withFaceLandmarks().withFaceDescriptors();

      const resizedDetections = faceapi.resizeResults(detections, displaySize);
      canvas.getContext('2d').clearRect(0, 0, canvas.width, canvas.height);
      faceapi.draw.drawDetections(canvas, resizedDetections);
      faceapi.draw.drawFaceLandmarks(canvas, resizedDetections);

      const results = resizedDetections.map(d => faceMatcher.findBestMatch(d.descriptor));
      detectedFaces = results.map((result) => result.label);
      markAttendance(detectedFaces);

      results.forEach((result, i) => {
        const box = resizedDetections[i].detection.box;
        const drawBox = new faceapi.draw.DrawBox(box, { label: result.toString() });
        drawBox.draw(canvas);
      });
    }, 100);
  });
}

function sendAttendanceDataToServer() {
  const attendanceData = [];

  document.querySelectorAll("#tabelMahasiswa tr").forEach((row, index) => {
    if (index === 0) return;
    const npm = row.cells[0].innerText.trim();
    const id_fakultas = row.cells[2].innerText.trim();
    const id_kelas = row.cells[3].innerText.trim();
    const status = row.cells[4].innerText.trim();

    attendanceData.push({ npm, id_fakultas, id_kelas, status });
  });

  console.log("Attendance Data:", attendanceData);

  const xhr = new XMLHttpRequest();
  xhr.open("POST", "resources/pages/dosen/sendData.php", true);
  xhr.setRequestHeader("Content-Type", "application/json");

  xhr.onreadystatechange = function () {
    if (xhr.readyState === 4) {
      if (xhr.status === 200) {
        try {
          const response = JSON.parse(xhr.responseText);

          if (response.status === "success") {
            showMessage(response.message || "Kehadiran berhasil dicatat.");
          } else {
            showMessage(response.message || "Gagal mencatat kehadiran.");
          }
        } catch (e) {
          showMessage("Error: Failed to parse the response from the server.");
        }
      } else {
        showMessage("Error: Unable to record attendance. HTTP Status: " + xhr.status);
        console.error("HTTP Error", xhr.status, xhr.statusText);
      }
    }
  };

  xhr.send(JSON.stringify(attendanceData));
}

function showMessage(message) {
  var messageDiv = document.getElementById("messageDiv");
  messageDiv.style.display = "block";
  messageDiv.innerHTML = message;
  console.log(message);
  messageDiv.style.opacity = 1;
  setTimeout(function () {
    messageDiv.style.opacity = 0;
  }, 5000);
}

function stopWebcam() {
  if (videoStream) {
    const tracks = videoStream.getTracks();

    tracks.forEach((track) => {
      track.stop();
    });

    video.srcObject = null;
    videoStream = null;
  }
}

function resetForm() {
  document.getElementById('selectForm').reset();
  document.getElementById('tabelMahasiswa').innerHTML = "";
};

document.getElementById("endAttendance").addEventListener("click", function () {
  sendAttendanceDataToServer();
  resetForm();
  const videoContainer = document.querySelector(".video-container");
  videoContainer.style.display = "none";
  stopWebcam();
});


