document.addEventListener("DOMContentLoaded", () => {
    const video = document.getElementById("video");
    const videoContainer = document.querySelector(".videoContainer");
    const startButton = document.getElementById("startButton");
    let cameraStarted = false;
    let modelsLoaded = false;

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

    // Start button event listener
    startButton.addEventListener("click", async () => {
        videoContainer.style.display = "flex";
        if (!cameraStarted && modelsLoaded) {
            startCamera();
            cameraStarted = true;
        }
    });

    // Start camera function
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

    // Load labels for face recognition
    async function getLabels() {
        const labeledDescriptors = [];
        const res = await (await fetch("resources/api/mahasiswa.php")).json();

        const labels = res.data.map(npms => npms.npm.toString());
        for (const label of labels) {
            const descriptions = [];

            for (let i = 1; i <= 5; i++) {
                try {
                    const img = await faceapi.fetchImage(
                        `resources/labels/${label}/${i}_${label}.png`
                    );
                    console.log( `resources/labels/${label}/${i}_${label}.png`)
                    
                    const detections = await faceapi
                        .detectSingleFace(img)
                        .withFaceLandmarks()
                        .withFaceDescriptor();

                    if (detections) {
                        descriptions.push(detections.descriptor);
                    } else {
                        console.log(`Wajah tidak terdaftar: ${label}/${i}.png`);
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

    // Detect faces and recognize
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

            const dataMahasiswa = results.map(result => {
                if (result.label != "unknown"){
                    return result._label
                }
            })

            console.log(JSON.stringify(dataMahasiswa))
            // console.log(JSON.stringify(results))

            results.forEach((result, i) => {
                const box = resizedDetections[i].detection.box;
                const drawBox = new faceapi.draw.DrawBox(box, {
                    label: result.toString(),
                });
                drawBox.draw(canvas);
            });
        }, 100);
    });
});
