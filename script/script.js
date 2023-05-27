const auth_token = "Discogs token=tmaswzbNQlPUxhekudJyHsNNbUZxMXaPtxXfUYXa";

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
  const year = Math.floor(Math.random() * (new Date().getFullYear() - 2005)) + 2005;
  let query = `https://api.discogs.com/database/search?style=drum+n+bass&per_page=15&format=album&type=release&type=master&year=${year}`;
  try {
    const res_pages = await fetch(query, {
      headers: {
        Authorization: auth_token,
      },
    });
      let data = await res_pages.json();
      const res = await fetch(query + `&page=${Math.floor(Math.random() * data.pagination.pages + 1)}`, {
        headers: {
          Authorization: auth_token,
        },
      });
      data = await res.json();
    console.log(data);
    data.results.forEach((album) => {
      loadNewSongs(album.id, album.thumb, album.uri);
    });
  } catch (e) {
    console.log(e);
  }
}

async function loadNewSongs(album_id, cover, uri) {
  const songlist = document.getElementsByClassName("new-songs")[0];
  songlist.innerHTML = "";
  try {
    const res = await fetch(
      `https://api.discogs.com/releases/${album_id}`,
      {
        headers: {
          Authorization: auth_token
        },
      }
    );
    let data = await res.json();
    console.log(data);
    data.tracklist.forEach((track) => {
      const newSong = document.createElement("div");
      const title = Object.hasOwn(track, "artists")
        ? track.artists.map((art) => art.name).join(" & ") + " - " + track.title
        : data.artists.map((art) => art.name).join(" & ") + " - " + track.title;
      newSong.classList.add("new-song-wrapper");
      newSong.innerHTML = `
          <div class="song-cover">
        <img src="${cover}" alt="deine mamer"></a>
          </div>
          <span class="song-title">${title.replaceAll(/\([^)]*\)/g, "")}</span>
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
            <a target="_blank" href="https://discogs.com/${uri.replace(/^\//, "")}"><i class="fa-solid fa-record-vinyl"></i></a>
            </div>
            `;
      songlist.append(newSong);
    });
  } catch (e) {
    console.log(e);
  }
}
