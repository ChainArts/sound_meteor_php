const form = document.getElementById("form");
let isMenuOpen = false;

if (form != null) {
  form.addEventListener(
    "focus",
    (event) => {
      event.target.parentElement.classList.add("focused");
    },
    true
  );

  form.addEventListener(
    "blur",
    (event) => {
      event.target.parentElement.classList.remove("focused");
    },
    true
  );
}

let checkMsgBox = () => {
  const msg_box = document.getElementsByClassName("state-box")[0];
  if (msg_box != null) {
    msg_box.classList.remove("hidden");
    setTimeout(() => {
      msg_box.classList.add("hidden");
    }, 5000);
  }
};

let activateForm = () => {
  const inputs = document.querySelectorAll("input, select");

  for (const input of inputs) {
    if (input.id != "always-disabled") {
      input.removeAttribute("disabled");
    }
  }
  document.getElementsByClassName("edit-form")[0].classList.add("hiddenform");
  document
    .getElementsByClassName("side-by-side hiddenform")[0]
    .classList.remove("hiddenform");
};

window.onload = () => {
  if (window.location.href.includes("?status")) {
    checkMsgBox();
    window.history.pushState(
      {},
      "Hide",
      window.location.href.replace(/(?<=.*\/.*php).*/, "").replace(/.*\//, "")
    );
  }
  document.getElementsByTagName("body")[0].classList.add("fade");
};

let toggleIsOpen = () => {
  isMenuOpen = !isMenuOpen;
  document
    .getElementsByClassName("nav-overlay")[0]
    .classList.toggle("menu-open");
  document.getElementById("menu-icon").classList.toggle("menu-toggle-open");
  document.getElementById("menu-icon").classList.toggle("menu-toggle-closed");
};

window.onresize = () => {
  if (window.screenX < 1000 && isMenuOpen == true) {
    toggleIsOpen();
  }
};

async function loadNewAlbums() {
    try {
      const page = Math.floor((Math.random()*90)+1)
      const response = await fetch(
      `https://api.discogs.com/database/search?style=drum+n+bass&per_page=10&page=${page}&format=album&format=single&format=ep&type=release&type=master`,
      {
        headers: {
          Authorization:
            "Discogs token=tmaswzbNQlPUxhekudJyHsNNbUZxMXaPtxXfUYXa",
        },
      }
    );
    let data = await response.json();
    console.log(data);
        data.results.forEach((album) => {
        if(!((album.title).toLowerCase().includes("various")&& Number(album.year) < 2008)){
            loadNewSongs(album.id, album.thumb, album.uri);
        }
    });
  } catch (e) {
    console.log(e);
  }
}

async function loadNewSongs(album_id, cover, uri) {
  const songlist = document.getElementsByClassName("new-songs")[0];
  songlist.innerHTML = "";
  try {
    const response = await fetch(
      `https://api.discogs.com/releases/${album_id}`,
      {
        headers: {
          Authorization:
            "Discogs token=tmaswzbNQlPUxhekudJyHsNNbUZxMXaPtxXfUYXa",
        },
      }
    );
    let data = await response.json();
    console.log(data);
    data.tracklist.forEach((track) => {
      const newSong = document.createElement("div");
      const title = data.artists_sort + " - " + track.title;
      newSong.classList.add("new-song-wrapper");
      newSong.innerHTML = `
          <div class="song-cover">
        <img src="${cover}" alt="deine mamer"></a>
          </div>
          <span class="song-title">${title}</span>
          <div class="song-links">
          <a target="_blank" href="https://youtube.com/results?search_query=${encodeURIComponent(
            title
          ).replaceAll(
            /\([^)]*\)/g,
            ""
          )}"><i class="fa-brands fa-youtube"></i></a>
        <a target="_blank" href="https://soundcloud.com/search?q=${title
          .replaceAll(/\([^)]*\)/g, "")
          .replace("&", "%26")}"><i class="fa-brands fa-soundcloud"></i></a>
            <a target="_blank" href="https://discogs.com/${uri}"><i class="fa-solid fa-record-vinyl"></i></a>
            </div>
            `;
      songlist.append(newSong);
    });
  } catch (e) {
    console.log(e);
  }
}
