/* 

  Original source
  https://www.youtube.com/watch?v=ZeNyjnneq_w

*/

const playPauseBtn = document.querySelector(".play-pause-btn");
const fullScreenBtn = document.querySelector(".full-screen-btn");
const miniPlayerBtn = document.querySelector(".mini-player-btn");
const muteBtn = document.querySelector(".mute-btn");
const speedBtn = document.querySelector(".speed-btn");
const currentTimeElem = document.querySelector(".current-time");
const totalTimeElem = document.querySelector(".total-time");
const previewImg = document.querySelector(".preview-img");
const thumbnailImg = document.querySelector(".thumbnail-img");
const volumeSlider = document.querySelector(".volume-slider");
const videoContainer = document.querySelector(".video-container");
const timelineContainer = document.querySelector(".timeline-container");
const video = document.querySelector("video");

// Disable mouse click and contextmenu...
videoContainer.addEventListener('contextmenu', (e) => {
  e.preventDefault();
  e.stopPropagation();
})

videoContainer.addEventListener('copy', (e) => {
  e.preventDefault();
  e.stopPropagation();
})
  
videoContainer.addEventListener('cut', (e) => {
  e.preventDefault();
  e.stopPropagation();
})

document.addEventListener("keydown", e => {
  const tagName = document.activeElement.tagName.toLowerCase()

  if (tagName === "input") return;

  switch (e.key.toLowerCase()) {
    case " ":
      if (tagName === "button") return;
    case "k":
      togglePlay();
      break
    case "f":
      toggleFullScreenMode();
      break
    case "i":
      toggleMiniPlayerMode();
      break
    case "m":
      toggleMute();
      break
    case "arrowleft":
      skip(-5);
      break
    case "arrowright":
      skip(5);
      break;
  }
})

// Timeline
timelineContainer.addEventListener("mousemove", handleTimelineUpdate);
timelineContainer.addEventListener("mousedown", toggleScrubbing);

document.addEventListener("mouseup", e => {
  if (isScrubbing) toggleScrubbing(e)
})

document.addEventListener("mousemove", e => {
  if (isScrubbing) handleTimelineUpdate(e)
})

let isScrubbing = false;
let wasPaused;

function toggleScrubbing(e) {
  const rect = timelineContainer.getBoundingClientRect()
  const percent = Math.min(Math.max(0, e.x - rect.x), rect.width) / rect.width
  isScrubbing = (e.buttons & 1) === 1
  videoContainer.classList.toggle("scrubbing", isScrubbing)
  if (isScrubbing) {
    wasPaused = video.paused
    video.pause()
  } else {
    video.currentTime = percent * video.duration
    if (!wasPaused) video.play()
  }

  handleTimelineUpdate(e)
}

function handleTimelineUpdate(e) {
  const rect = timelineContainer.getBoundingClientRect()
  const percent = Math.min(Math.max(0, e.x - rect.x), rect.width) / rect.width

  if (isScrubbing) {
    e.preventDefault()
    timelineContainer.style.setProperty("--progress-position", percent)
  }
}

// Playback Speed
speedBtn.addEventListener("click", changePlaybackSpeed)

function changePlaybackSpeed() {
  let newPlaybackRate = video.playbackRate + 0.25
  if (newPlaybackRate > 2) newPlaybackRate = 0.25
  video.playbackRate = newPlaybackRate
  speedBtn.textContent = `${newPlaybackRate}x`
}

// Duration
video.addEventListener("loadeddata", () => {
  console.log("duration", formatDuration(video.duration));
  setTimeout(() => totalTimeElem.textContent = formatDuration(video.duration), 500);
})

video.addEventListener("timeupdate", () => {
  currentTimeElem.textContent = formatDuration(video.currentTime)
  const percent = video.currentTime / video.duration
  timelineContainer.style.setProperty("--progress-position", percent);
  if (totalTimeElem.textContent === ""){
    totalTimeElem.textContent = formatDuration(video.duration);
  }
})

const leadingZeroFormatter = new Intl.NumberFormat(undefined, {minimumIntegerDigits: 2});

function formatDuration(time) {
  const seconds = Math.floor(time % 60)
  const minutes = Math.floor(time / 60) % 60
  const hours = Math.floor(time / 3600)
  if (hours === 0) {
    return `${minutes}:${leadingZeroFormatter.format(seconds)}`
  } else {
    return `${hours}:${leadingZeroFormatter.format(
      minutes
    )}:${leadingZeroFormatter.format(seconds)}`
  }
}

function skip(duration) {
  video.currentTime += duration
}

// Volume
function toggleMute() {
  video.muted = !video.muted;
  if (video.muted) {
    volumeSlider.style.background = 'linear-gradient(to right, white 0%, white 0%, #ffffff42 0%, #ffffff42 100%)';
  } else {
    setTimeout(() => {
      var value = (volumeSlider.value-volumeSlider.min)/(volumeSlider.max-volumeSlider.min)*100;
      volumeSlider.style.background = 'linear-gradient(to right, white 0%, white ' + value + '%, #ffffff42 ' + value + '%, #ffffff42 100%)';
    }, 200)
  }
}

muteBtn.addEventListener("click", toggleMute);

volumeSlider.onchange = function() {
  console.log("debug")
}

volumeSlider.oninput = function() {
  video.volume = this.value;
  video.muted = this.value === 0;

  var value = (this.value-this.min)/(this.max-this.min)*100;
  this.style.background = 'linear-gradient(to right, white 0%, white ' + value + '%, #ffffff42 ' + value + '%, #ffffff42 100%)';
}

video.addEventListener("volumechange", () => {
  volumeSlider.value = video.volume
  let volumeLevel
  if (video.muted || video.volume === 0) {
    volumeSlider.value = 0
    volumeLevel = "muted"
  } else if (video.volume >= 0.5) {
    volumeLevel = "high"
  } else {
    volumeLevel = "low"
  }

  videoContainer.dataset.volumeLevel = volumeLevel
})

// View Modes
fullScreenBtn.addEventListener("click", toggleFullScreenMode)
miniPlayerBtn.addEventListener("click", toggleMiniPlayerMode)

function toggleFullScreenMode() {
  if (document.fullscreenElement == null) {
    videoContainer.requestFullscreen()
  } else {
    document.exitFullscreen()
  }
}

function toggleMiniPlayerMode() {
  if (videoContainer.classList.contains("mini-player")) {
    document.exitPictureInPicture()
  } else {
    video.requestPictureInPicture()
  }
}

document.addEventListener("fullscreenchange", () => {
  videoContainer.classList.toggle("full-screen", document.fullscreenElement)
})

video.addEventListener("enterpictureinpicture", () => {
  videoContainer.classList.add("mini-player")
})

video.addEventListener("leavepictureinpicture", () => {
  videoContainer.classList.remove("mini-player")
})

// Play/Pause
playPauseBtn.addEventListener("click", togglePlay)
video.addEventListener("click", togglePlay)

function togglePlay() {
  video.paused ? video.play() : video.pause()
}

video.addEventListener("play", () => {
  videoContainer.classList.remove("paused")
})

video.addEventListener("pause", () => {
  videoContainer.classList.add("paused")
})