const auth_token = "Discogs token=tmaswzbNQlPUxhekudJyHsNNbUZxMXaPtxXfUYXa";

const form = document.getElementById("form");
const initialEditState = (!window.location.href.includes("login")) ? document.getElementById("edit-form").innerHTML : "";

let isMenuOpen, isUserOpen, userEdited = false;

document.addEventListener("mousemove", parallax);
      function parallax(event) {
          this.querySelectorAll(".mouse").forEach((shift) => {
              const position = shift.getAttribute("value");
              const rotation = shift.getAttribute("rot");
          const x = (window.innerWidth - event.pageX * position) / 90;
          const y = (window.innerHeight - event.pageY * position) / 90;

          shift.style.transform = `translateX(${x}px) translateY(${y}px) rotateZ(${rotation}deg)`;
        });
      }

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

let cleanupUserEdit = () => {
    document.getElementById("edit-form").innerHTML = initialEditState;
};

let toggleEdit = (id, state) => {
  const el = document.getElementById(id);
  el.getElementsByTagName("span")[0].classList.toggle("hiddenform");
  el.closest("form").getElementsByClassName("edit-button")[0].classList.remove("hiddenform");
  el.getElementsByClassName("button")[0].classList.toggle("hiddenform");
  el.getElementsByClassName("button")[1].classList.toggle("hiddenform");
  el.getElementsByClassName("edit-box")[0].classList.toggle("hiddenform");
  state
    ? el.getElementsByClassName("edit-box")[0].focus()
    : id === "pwd-edit"
    ? (el.getElementsByTagName("span")[0].innerHTML = "********")
    : (el.getElementsByTagName("span")[0].innerHTML =
        el.getElementsByClassName("edit-box")[0].value);
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

let toggleMenuOpen = () => {
  isMenuOpen = !isMenuOpen;
  document
    .getElementsByClassName("nav-overlay")[0]
    .classList.toggle("menu-open");
  document.getElementById("menu-icon").classList.toggle("menu-toggle-open");
  document.getElementById("menu-icon").classList.toggle("menu-toggle-closed");
};

let toggleUserOpen = () => {
  const userListenener = new AbortController().signal;
  isUserOpen = !isUserOpen;
  document
    .getElementsByClassName("usr-overlay")[0]
    .classList.toggle("usr-open");
  document
    .getElementsByClassName("usr-backdrop")[0]
    .classList.toggle("usr-backdrop-enabled");
  document.body.addEventListener(
    "keydown",
    (e) => {
      if (!isUserOpen) {
        userListenener.abort;
      } else {
        e = e || window;
        if (e.key === "Escape") {
          toggleUserOpen();
          cleanupUserEdit();
        }
      }
    },
    { signal: userListenener }
  );
};

window.onresize = () => {
  if (window.screenX < 1000 && isMenuOpen) {
    toggleMenuOpen();
  }
};

async function loadNewAlbums() {
  const year =
    Math.floor(Math.random() * (new Date().getFullYear() - 2005)) + 2005;
  let query = `https://api.discogs.com/database/search?style=drum+n+bass&per_page=10&format=album&type=release&type=master&year=${year}`;
  try {
    const res_pages = await fetch(query, {
      headers: {
        Authorization: auth_token,
      },
    });
    let data = await res_pages.json();
    const res = await fetch(
      query + `&page=${Math.floor(Math.random() * data.pagination.pages + 1)}`,
      {
        headers: {
          Authorization: auth_token,
        },
      }
    );
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
    const res = await fetch(`https://api.discogs.com/releases/${album_id}`, {
      headers: {
        Authorization: auth_token,
      },
    });
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
        <img src="${cover}" alt="Cover Image">
          </div>
          <span class="song-title">${title.replaceAll(/\([^)]*\)/g, "")}</span>
          <div class="song-links">
          <a title="Search on Youtube" target="_blank" href="https://youtube.com/results?search_query=${encodeURIComponent(
            title
          ).replaceAll(
            /\([^)]*\)/g,
            ""
          )}"><i class="fa-brands fa-youtube"></i></a>
        <a title="Search on Soundcloud" target="_blank" href="https://soundcloud.com/search?q=${title
          .replaceAll(/\([^)]*\)/g, "")
          .replace("&", "%26")}"><i class="fa-brands fa-soundcloud"></i></a>
            <a title="Show on Discogs" target="_blank" href="https://discogs.com/${uri.replace(
              /^\//,
              ""
            )}"><i class="fa-solid fa-record-vinyl"></i></a>
            </div>
            `;
      songlist.append(newSong);
    });
  } catch (e) {
    console.log(e);
  }
}