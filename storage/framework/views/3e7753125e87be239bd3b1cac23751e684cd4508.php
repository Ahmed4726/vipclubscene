<!--
/*! Copyright Amazon.com, Inc. or its affiliates. All Rights Reserved. SPDX-License-Identifier: Apache-2.0 */
-->

<?php $__env->startSection( 'content' ); ?>

<head>
  <meta charset="UTF-8" />
  <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Broadcast To IVS</title>
  <!-- Google Fonts -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,300italic,700,700italic" />
  <!-- CSS Reset -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.css" />
  <!-- Milligram CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/milligram/1.4.1/milligram.css" />
  <script src="https://web-broadcast.live-video.net/1.14.0/amazon-ivs-web-broadcast.js"></script>

  <style>
    html,
    body {
      width: 100%;
      height: 100vh;
      margin: 0;
    }

    #error {
      color: red;
    }

    table {
      display: table;
    }

    #preview {
      margin-bottom: 1.5rem;
      background: green;
      width: 100%;
      height: 300;
    }

    .comment-box {
            width: 100%;
            height: 150px;
            border: 1px solid #ccc;
            padding: 10px;
            overflow-y: auto;
            margin-bottom: 10px;
        }
        .comment-input {
            width: 100%;
            padding: 10px;
            border: 1px solid #fdcd02;
        }
.change-color{
  background-color:#7776a3;
  border: none;
}
.change-color:hover{
  background-color:#7776a3;
}
.text-h1{
  text-align: center !important;
  color: black;
}
.hide-content{
  display:none;
}
  </style>
</head>

<body>
<div class="white-smoke-bg  pt-4 pb-3">
<div class="container">
<div class="row">
  <div class="container mt-5" id="post">
  <h1 class="text-h1">VIP Club Scene LiveStream</h1>
    <label for="Post Title">Post Title:</label>
    <input type="text" class="col-md-4  " name="text_content" id="text_content">
    <label for="lock_type" class="me-3">Lock Type:</label>
    <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" id="Free" name="lock_type" value="Free" checked>
        <label class="form-check-label" for="Free">Free</label>
    </div>
    <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" id="Paid" name="lock_type" value="Paid">
        <label class="form-check-label" for="Paid">Paid</label>
    </div><br>
    <button class="button change-color" id="start" disabled onclick="startBroadcast()">Start Stream</button>
  </div>


  <hr />
  <div id="stream" class="d-none">
  <!-- Error alert -->
  <section class="container">
    <h3 id="error"></h3>
  </section>

  <!-- Compositor preview -->
  <section class="container">
    <canvas id="preview"></canvas>
  </section>

  <!--  Select -->
  <section class="container">
    <label for="video-devices">Select Webcam</label>
    <select disabled id="video-devices">
      <option selected disabled>Choose Option</option>
    </select>

    <label for="audio-devices">Select Microphone</label>
    <select disabled id="audio-devices">
      <option selected disabled>Choose Option</option>
    </select>

    <label for="stream-config">Select Channel Config</label>
    <select disabled id="stream-config">
      <option selected disabled>Choose Option</option>
    </select>
  </section>

  <!-- Ingest Endpoint input -->
  <section class="container">
    <label for="ingest-endpoint" class="hide-content">Ingest Endpoint</label>
    <input type="hidden" id="ingest-endpoint" value="" />
  </section>

  <!-- Stream Key input -->
  <section class="container">
    <label for="stream-key" class="hide-content">Stream Key</label>
    <input type="hidden" id="stream-key" value="" />
  </section>
  <section class="container">
    <label for="comments">Live Chat</label>
    <div id="commentsBox" class="comment-box"></div>
    <input type="text" id="commentInput" class="comment-input" placeholder="Type your comment...">
    <button id="postComment" class="change-color" onclick="postComment()">Post Comment</button>
</section>
  <!-- Broadcast buttons -->
  <section class="container">
    <button class="button change-color" id="stop" disabled onclick="stopBroadcast()">Stop Broadcast</button>
  </section>
</div>
  <hr />
  <input type="hidden" name="post_id" id="post_id">
  <!-- Data table -->
  <section class="container">
    <table id="data">
      <tbody></tbody>
    </table>
  </section>
</body>
</div><!-- paddin top 5-->
</div><!-- ./container -->
</div><!-- .swhite-smoke -->
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  // Possible configurations
const channelConfigs = [
  ["Basic: Landscape", window.IVSBroadcastClient.BASIC_LANDSCAPE],
  ["Basic: Portrait", window.IVSBroadcastClient.BASIC_PORTRAIT],
  ["Standard: Landscape", window.IVSBroadcastClient.STANDARD_LANDSCAPE],
  ["Standard: Portrait", window.IVSBroadcastClient.STANDARD_PORTRAIT]
];

// Set initial config for our broadcast
const config = {
  ingestEndpoint: "https://g.webrtc.live-video.net:4443",
  streamConfig: window.IVSBroadcastClient.BASIC_LANDSCAPE,
  logLevel: window.IVSBroadcastClient.LOG_LEVEL.DEBUG
};
var postId = document.getElementById("post_id").value; // Replace with actual post ID
let lastCommentId = null;
// Error helpers
function clearError() {
  const errorEl = document.getElementById("error");
  errorEl.innerHTML = "";
}

function setError(message) {
  if (Array.isArray(message)) {
    message = message.join("<br/>");
  }
  const errorEl = document.getElementById("error");
  errorEl.innerHTML = message;
}

function getSupportedProperty(object, key) {
  if (key in object) {
    return object[key];
  }

  return "Unsupported";
}

// Get available audio/video inputs
async function initializeDeviceSelect() {
  const videoSelectEl = document.getElementById("video-devices");

  videoSelectEl.disabled = false;
  const { videoDevices, audioDevices } = await getDevices();
  videoDevices.forEach((device, index) => {
    videoSelectEl.options[index] = new Option(device.label, device.deviceId);
  });

  const audioSelectEl = document.getElementById("audio-devices");

  audioSelectEl.disabled = false;
  audioSelectEl.options[0] = new Option("None", "None");
  audioDevices.forEach((device, index) => {
    audioSelectEl.options[index + 1] = new Option(
      device.label,
      device.deviceId
    );
  });
}

async function getCamera(deviceId, maxWidth, maxHeight) {
  let media;
  const videoConstraints = {
    deviceId: deviceId ? { exact: deviceId } : null,
    width: {
      max: maxWidth
    },
    height: {
      max: maxHeight
    }
  };
  try {
    // Let's try with max width and height constraints
    media = await navigator.mediaDevices.getUserMedia({
      video: videoConstraints,
      audio: true
    });
  } catch (e) {
    // and fallback to unconstrained result
    delete videoConstraints.width;
    delete videoConstraints.height;
    media = await navigator.mediaDevices.getUserMedia({
      video: videoConstraints
    });
  }
  return media;
}

// Handle video device retrieval
async function handleVideoDeviceSelect() {
  const id = "camera";
  const videoSelectEl = document.getElementById("video-devices");
  const { videoDevices: devices } = await getDevices();
  if (window.client.getVideoInputDevice(id)) {
    window.client.removeVideoInputDevice(id);
  }

  // Get the option's video
  const selectedDevice = devices.find(
    (device) => device.deviceId === videoSelectEl.value
  );
  const deviceId = selectedDevice ? selectedDevice.deviceId : null;
  const { width, height } = config.streamConfig.maxResolution;
  const cameraStream = await getCamera(deviceId, width, height);

  // Add the camera to the top
  await window.client.addVideoInputDevice(cameraStream, id, {
    index: 0
  });
}

// Handle audio/video device enumeration
async function getDevices() {
  const devices = await navigator.mediaDevices.enumerateDevices();
  const videoDevices = devices.filter((d) => d.kind === "videoinput");
  if (!videoDevices.length) {
    setError("No video devices found.");
  }
  const audioDevices = devices.filter((d) => d.kind === "audioinput");
  if (!audioDevices.length) {
    setError("No audio devices found.");
  }

  return { videoDevices, audioDevices };
}

// Handle audio device retrieval
async function handleAudioDeviceSelect() {
  const id = "microphone";
  const audioSelectEl = document.getElementById("audio-devices");
  const { audioDevices: devices } = await getDevices();
  if (window.client.getAudioInputDevice(id)) {
    window.client.removeAudioInputDevice(id);
  }
  if (audioSelectEl.value.toLowerCase() === "none") return;
  const selectedDevice = devices.find(
    (device) => device.deviceId === audioSelectEl.value
  );
  // Unlike video, for audio we default to "None" instead of the first device
  if (selectedDevice) {
    const microphoneStream = await navigator.mediaDevices.getUserMedia({
      audio: {
        deviceId: selectedDevice.deviceId
      }
    });
    await window.client.addAudioInputDevice(microphoneStream, id);
  }
}

// Setup the stream configuration options
async function initializeStreamConfigSelect() {
  const streamConfigSelectEl = document.getElementById("stream-config");
  streamConfigSelectEl.disabled = false;

  channelConfigs.forEach(([configName], index) => {
    streamConfigSelectEl.options[index] = new Option(configName, index);
  });
}

// Handle setting the stream config
async function handleStreamConfigSelect() {
  const streamConfigSelectEl = document.getElementById("stream-config");
  const selectedConfig = streamConfigSelectEl.value;
  config.streamConfig = channelConfigs[selectedConfig][1];

  await createClient();
}

/**
 * Validates the form's input elements. Returns empty array if
 * valid else the list of errors.
 */
function validate() {
  const streamKey = document.getElementById("stream-key").value;
  const ingestUrl = document.getElementById("ingest-endpoint").value;
  const errors = [];

  if (!ingestUrl) {
    errors.push("Please provide an ingest endpoint");
  }

  if (!streamKey) {
    errors.push("Please provide a stream key");
  }

  return errors;
}

async function handleIngestEndpointChange(e) {
  handleValidationErrors(validate());

  try {
    client.config.ingestEndpoint = e.target.value;
  } catch {
    handleValidationErrors(["Incorrect Ingest Url"]);
  }
}

function handleStreamKeyChange(e) {
  handleValidationErrors(validate());
}

function handleValidationErrors(errors, doNotDisplay) {
  const start = document.getElementById("start");
  const stop = document.getElementById("stop");

  clearError();
  if (errors && errors.length) {
    // Display errors
    if (!doNotDisplay) {
      setError(errors);
    }

    // Disable start and stop buttons
    start.disabled = true;
    stop.disabled = true;
    return;
  }

  start.disabled = false;
}

// Start the broadcast
async function startBroadcast() {
  const post = document.getElementById("post");
  const stream = document.getElementById("stream");
  const streamKeyEl = document.getElementById("stream-key");
  const endpointEl = document.getElementById("ingest-endpoint");
  const start = document.getElementById("start");

  try {
    // Show the stream, hide the post, and disable the start button
    stream.classList.remove('d-none');
    post.classList.add('d-none');
    start.disabled = true;

    // Show loader
    Swal.fire({
      title: 'Processing...',
      text: 'Please wait while we start your live session.',
      allowOutsideClick: false,
      didOpen: () => {
        Swal.showLoading();
      }
    });

    // Start the broadcast
    await window.client.startBroadcast(streamKeyEl.value, endpointEl.value);

    // Make the POST request to /start-live
    const response = await fetch('/start-live', {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        text_content: document.getElementById('text_content').value,
        lock_type: document.querySelector('input[name="lock_type"]:checked').value
      })
    });

    if (!response.ok) {
      throw new Error('Network response was not ok');
    }

    const data = await response.json();
    console.log('Response data:', data);  // Log the data for debugging

    // Show success message
    Swal.fire({
      title: 'Success!',
      text: 'You are live now.',
      icon: 'success',
    });

    // Insert the post_id value into the hidden input field
    postId = data.post_id;

  } catch (err) {
    // Show error message
    Swal.fire({
      title: 'Error!',
      text: err.toString(),
      icon: 'error',
    });

    // Re-enable the start button
    start.disabled = false;

    // Optionally set error (if you have a setError function defined)
    if (typeof setError === 'function') {
      setError(err.toString());
    }

    console.error('Error:', err);
  }
}


// Stop the broadcast
async function stopBroadcast() {
  try {
    await window.client.stopBroadcast();
    fetch('/end-live')
.then(response => response.json())
.then(data => {
    data.message;
})
.catch(error => setError(error.toString()));

var feedUrl = "<?php echo e(route('feed')); ?>";
    window.location.href = feedUrl;
  } catch (err) {
    setError(err.toString());
  }
}

// Handle the enabling/disabling of buttons
function onActiveStateChange(active) {
  const start = document.getElementById("start");
  const stop = document.getElementById("stop");
  const streamConfigSelectEl = document.getElementById("stream-config");
  const inputEl = document.getElementById("stream-key");
  inputEl.disabled = active;
  start.disabled = active;
  stop.disabled = !active;
  streamConfigSelectEl.disabled = active;
}

// Helper to create an instance of the AmazonIVSBroadcastClient
async function createClient() {
  if (window.client) {
    window.client.delete();
  }

  window.client = window.IVSBroadcastClient.create(config);

  window.client.on(
    window.IVSBroadcastClient.BroadcastClientEvents.ACTIVE_STATE_CHANGE,
    (active) => {
      onActiveStateChange(active);
    }
  );

  const previewEl = document.getElementById("preview");
  window.client.attachPreview(previewEl);

  await handleVideoDeviceSelect();
  await handleAudioDeviceSelect();
}

// Initialization function
async function init() {
  try {
    const initBtn = document.getElementById("init");
    const videoSelectEl = document.getElementById("video-devices");
    const audioSelectEl = document.getElementById("audio-devices");
    const streamConfigSelectEl = document.getElementById("stream-config");
    const ingestEndpointInputEl = document.getElementById("ingest-endpoint");
    const streamKeyInputEl = document.getElementById("stream-key");
    fetch('/get-ivs-config')
.then(response => response.json())
.then(data => {
  document.getElementById('ingest-endpoint').value = data.ingestEndpoint;
  document.getElementById('stream-key').value = data.streamKey;
  document.getElementById('start').disabled = false;
})
.catch(error => setError(error.toString()));
    await initializeStreamConfigSelect();

    videoSelectEl.addEventListener("change", handleVideoDeviceSelect, true);
    audioSelectEl.addEventListener("change", handleAudioDeviceSelect, true);
    streamConfigSelectEl.addEventListener(
      "change",
      handleStreamConfigSelect,
      true
    );
    ingestEndpointInputEl.addEventListener(
      "input",
      handleIngestEndpointChange,
      true
    );
    streamKeyInputEl.addEventListener("input", handleStreamKeyChange, true);

    // Get initial values from the text fields.  Changes to these will re-create the client.
    const selectedConfig = streamConfigSelectEl.value;
    config.streamConfig = channelConfigs[selectedConfig][1];
    config.ingestEndpoint = ingestEndpointInputEl.value;

    await createClient();

    await initializeDeviceSelect();

    handleValidationErrors(validate(), true);
  } catch (err) {
    setError(err.message);
  }
}

init();
fetch('/end-live')
.then(response => response.json())
.then(data => {
    data.message;
})
.catch(error => setError(error.toString()));
// Assuming you fetch ingest endpoint and stream key from backend

function loadComments(postId) {
  // alert(postId)
    fetch(`/comment/${postId}`)
        .then(response => response.json())
        .then(data => {
            if (data.view) {
                document.getElementById('commentsBox').innerHTML = data.view;
                lastCommentId = data.lastId;
            } else {
                console.error('Failed to load comments.');
            }
        })
        .catch(error => console.error('Error:', error));
}


        function postComment() {
            const message = document.getElementById('commentInput').value;
            if (!message.trim()) return;
          // alert(postId)
            fetch(`/comment/${postId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ message })
            })
            .then(response => response.json())
            .then(data => {
                if (data.message === 'posted') {
                    document.getElementById('commentInput').value = '';
                    loadComments(postId);
                } else {
                    console.error('Failed to post comment.');
                }
            })
            .catch(error => console.error('Error:', error));
        }
        setInterval(() => loadComments(postId), 1000);


</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make( 'welcome' , \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\vipclub\resources\views/livestream.blade.php ENDPATH**/ ?>