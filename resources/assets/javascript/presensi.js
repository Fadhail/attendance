document.addEventListener("DOMContentLoaded", async () => {
  const video = document.getElementById("video");
  const videoContainer = document.querySelector(".videoContainer");
  const startButton = document.getElementById("startButton");
  let cameraStarted = false;
  let modelsLoaded = false;

  const labels = await fetchDataMahasiswa();
  const dataMahasiswa = [];
  labels.forEach((label) => {
    dataMahasiswa.push({
      npm: label,
      status: "alpha",
      oldStatus: "alpha",
    });
  });

  setInterval(() => {
    dataMahasiswa.forEach((mahasiswa, idx) => {
      if (mahasiswa.oldStatus != mahasiswa.status) {
        const data = {
          npm: mahasiswa.npm,
          status: mahasiswa.status,
        };
        sendData(data);
        dataMahasiswa[idx].oldStatus = mahasiswa.status;
      }
    });
  }, 10);

  // Store recognized students to avoid duplicate entries
  const markedAttendance = new Set();

  // Load face-api models
  Promise.all([
    faceapi.nets.ssdMobilenetv1.loadFromUri("models"),
    faceapi.nets.faceRecognitionNet.loadFromUri("models"),
    faceapi.nets.faceLandmark68Net.loadFromUri("models"),
  ])
    .then(() => {
      modelsLoaded = true;
      console.log("Models berhasil dimuat");
    })
    .catch(() => {
      alert("Models gagal dimuat");
    });

  startButton.addEventListener("click", async () => {
    videoContainer.style.display = "flex";
    if (!cameraStarted && modelsLoaded) {
      startCamera();
      cameraStarted = true;
    }
  });

  function startCamera() {
    navigator.mediaDevices
      .getUserMedia({
        video: true,
        audio: false,
      })
      .then((stream) => {
        video.srcObject = stream;
      })
      .catch((error) => {
        console.error("Error accessing camera:", error);
      });
  }

  async function getLabels() {
    const labeledDescriptors = [];

    for (const label of labels) {
      const descriptions = [];

      for (let i = 1; i <= 5; i++) {
        try {
          const imgPath = `resources/labels/${label}/${i}_${label}.png`;
          console.log("Fetching image from path:", imgPath);

          const img = await faceapi.fetchImage(imgPath);

          const detections = await faceapi
            .detectSingleFace(img)
            .withFaceLandmarks()
            .withFaceDescriptor();

          if (detections) {
            descriptions.push(detections.descriptor);
          } else {
            console.warn(`Face not detected: ${imgPath}`);
          }
        } catch (error) {
          console.error(`Error processing ${label}/${i}_${label}.png:`, error);
        }
      }

      if (descriptions.length > 0) {
        labeledDescriptors.push(
          new faceapi.LabeledFaceDescriptors(label, descriptions)
        );
      }
    }

    return labeledDescriptors;
  }

  video.addEventListener("play", async () => {
    const labeledFaceDescriptors = await getLabels();
    const faceMatcher = new faceapi.FaceMatcher(labeledFaceDescriptors);

    const canvas = faceapi.createCanvasFromMedia(video);
    canvas.style.position = "absolute";
    videoContainer.appendChild(canvas);

    const displaySize = { width: video.width, height: video.height };
    faceapi.matchDimensions(canvas, displaySize);

    setInterval(async () => {
      const detections = await faceapi
        .detectAllFaces(video)
        .withFaceLandmarks()
        .withFaceDescriptors();

      const resizedDetections = faceapi.resizeResults(detections, displaySize);

      canvas.getContext("2d").clearRect(0, 0, canvas.width, canvas.height);

      const results = resizedDetections.map((d) => {
        return faceMatcher.findBestMatch(d.descriptor);
      });

      const newRecognized = results.map((result) => {
        return result.label;
      });

      if (newRecognized.length > 0) {
        markAttendance(newRecognized);
      }

      results.forEach((result, i) => {
        const data = dataMahasiswa.find(
          (mahasiswa) => mahasiswa.npm == result._label
        );
        const box = resizedDetections[i].detection.box;
        const info = data ? `${data.npm} | ${data.status}` : result.toString();
        const drawBox = new faceapi.draw.DrawBox(box, {
          label: info,
        });
        drawBox.draw(canvas);
      });
    }, 100);
  });

  function markAttendance(detectedFaces) {
    dataMahasiswa.forEach((mahasiswa, idx) => {
      if (detectedFaces.includes(mahasiswa.npm)) {
        dataMahasiswa[idx].status = "hadir";
      }
    });
  }

  function sendData(data) {
    fetch("resources/api/sendData.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({ recognizedData: data }),
    })
      .then((response) => response.json())
      .then((result) => {
        console.log("Data sent successfully:", result);
      })
      .catch((error) => {
        console.error("Error sending data:", error);
      });
  }
});

function fetchDataMahasiswa() {
  return new Promise((resolve, reject) => {
    const json = fetch("resources/api/mahasiswa.php")
      .then((res) => res.json())
      .then((json) => {
        console.log(json);
        const labels = json.data.map((mahasiswa) =>
          mahasiswa.status.toString()
        );
        resolve(labels);
      })
      .catch((err) => resolve(err.message));
  });
}
