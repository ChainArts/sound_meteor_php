const auth_token = "Discogs token=tmaswzbNQlPUxhekudJyHsNNbUZxMXaPtxXfUYXa";

const form = document.getElementById("form");
const initialEditState = !window.location.href.includes("login")
  ? document.getElementById("edit-form").innerHTML
  : "";
let title = document.title;
let prevUrl = window.location.href;
let isMenuOpen,
  isUserOpen,
  userEdited = false;

if (window.location.pathname == "/") {
  particlesJS.load("particles-js", "script/particles.json", function () {
    console.log("callback - particles.js config loaded");
  });
}

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

const sendPostRequest = async (url, data) => {
  try {
    const response = await fetch(url, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify(data),
    });

    if (!response.ok) {
      throw new Error("Error");
    }

    console.log("POST request succeeded");
    const responseData = await response.json();

    console.log(responseData);
    return responseData.status;
  } catch (error) {
    // Handle any errors here
    console.error("Error:", JSON.stringify(error));
  }
};

const addPref = (pref_id, type) => {
  const addPrefData = {
    action: "add",
    pref_id: pref_id,
    type: type,
  };
  if (sendPostRequest("edit_pref", addPrefData)) {
    document.getElementById("select").classList.add("disabled");
    document
      .getElementsByTagName("main")[0]
      .prepend(genMsgBox("Preference added"));
    checkMsgBox();
    let nr = document
      .getElementById("pref-count")
      .innerHTML.replace(/(^\d+)(.+$)/i, "$1");
    document.getElementById("pref-count").innerHTML = `${Number(nr) + 1} / 5 `;
    if (nr == 0) {
      document.getElementsByClassName("pref-list")[0].innerHTML = "";
    }

    const li = document.createElement("li");
    li.classList.add("pref-list-item");
    const deleteButton = document.createElement("button");
    deleteButton.type = "button";
    deleteButton.addEventListener("click", (event) =>
      delPref(pref_id, type, event.target)
      ,{once: true});
    deleteButton.innerHTML = `<i class="fa-solid fa-trash"></i>`
    li.innerHTML = `
                <span>
                    <i class="fa-solid ${
                      type == "genre" ? "fa-music" : "fa-masks-theater"
                    }"></i>
                    <span>${
                      document.getElementById("selectedPref").innerText
                    }</span>
                </span>`
      li.appendChild(deleteButton);
    document.getElementsByClassName("pref-list")[0].appendChild(li);
    if (Number(nr) + 1 < 5) {
      document
        .getElementsByClassName("pref-add")[0]
        .classList.remove("disabled");
    }
  }
};

const delPref = (pref_id, type, el) => {
  const delPrefData = {
    action: "delete",
    pref_id: pref_id,
    type: type,
  };
  if (sendPostRequest("edit_pref", delPrefData)) {
    console.log(el);
    el.closest("li").remove();
    document
      .getElementsByTagName("main")[0]
      .prepend(genMsgBox("Preference removed"));
    checkMsgBox();
    let nr = document
      .getElementById("pref-count")
      .innerHTML.replace(/(^\d+)(.+$)/i, "$1");
    document.getElementById("pref-count").innerHTML = `${nr - 1} / 5 `;
    if (nr - 1 <= 0) {
      let no_prefs = document.createElement("li");
      no_prefs.classList.add("pref-list-item");
      no_prefs.innerHTML = `<span class="no-pref-msg"> No ${
        type == "genre" ? "Genres" : "Moods"
      } preferences found </span>`;
      document.getElementsByClassName("pref-list")[0].prepend(no_prefs);
    }
  }
};

const updatePref = (year) => {
  const updatePrefData = {
    action: "update",
    year: year,
  };
};

const openPrefAdd = (el) => {
  document.getElementById("select").classList.remove("disabled");
  el.parentElement.classList.add("disabled");
};

const genMsgBox = (msg) => {
  let statebox = document.createElement("div");
  statebox.classList.add("state-box");
  statebox.classList.add("hidden");
  statebox.innerHTML = `<span>${msg}</span>`;

  return statebox;
};

const checkMsgBox = () => {
  const msg_box = document.getElementsByClassName("state-box")[0];
  if (msg_box != null) {
    msg_box.classList.remove("hidden");
    setTimeout(() => {
      msg_box.classList.add("hidden");
    }, 5000);
  }
};

const activateForm = () => {
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

const cleanupUserEdit = () => {
  document.getElementById("edit-form").innerHTML = initialEditState;
};

const toggleEdit = (id, state) => {
  const el = document.getElementById(id);
  el.getElementsByTagName("span")[0].classList.toggle("hiddenform");
  el.closest("form")
    .getElementsByClassName("edit-button")[0]
    .classList.remove("hiddenform");
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

window.onload = () => getStatus();

const getStatus = () => {
  if (
    window.location.href.includes("?status") ||
    window.location.href.includes("&status")
  ) {
    checkMsgBox();
    window.history.pushState(
      {},
      "Hide",
      window.location.href.replace(/(?<=.*\/.*php).*/, "").replace(/.*\//, "")
    );
  }
  document.getElementsByTagName("body")[0].classList.add("fade");
};

const toggleMenuOpen = () => {
  isMenuOpen = !isMenuOpen;
  document
    .getElementsByClassName("nav-overlay")[0]
    .classList.toggle("menu-open");
  document.getElementById("menu-icon").classList.toggle("menu-toggle-open");
  document.getElementById("menu-icon").classList.toggle("menu-toggle-closed");
};

const toggleUserOpen = () => {
  const userListenener = new AbortController().signal;
  isUserOpen = !isUserOpen;
  document
    .getElementsByClassName("usr-overlay")[0]
    .classList.toggle("usr-open");
  document
    .getElementsByClassName("usr-backdrop")[0]
    .classList.toggle("usr-backdrop-enabled");

  isUserOpen
    ? ((title = document.title),
      window.history.pushState({}, "", "settings"),
      (document.title = "Sound Meteor | Settings"))
    : (window.history.pushState({}, "", prevUrl), (document.title = title));
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

const prefs = document.querySelectorAll("[prefID]");

const updateSelect = (el) => {
  document.getElementById("selectedPref").innerText = el.closest("li").firstChild.innerText;
  document
    .getElementById("selectedPref")
    .setAttribute("selected_id", el.getAttribute("prefid"));
  toggleDropDown();
  document
    .getElementById("pref-submit")
    .addEventListener(
      "click",
      () =>
        addPref(
          el.getAttribute("prefid"),
          document.getElementById("selectedPref").getAttribute("type")
        ),
      { once: true }
    );
};
const toggleDropDown = () => {
  document.getElementById("select").classList.toggle("active");
};

const checkInp = (input) => {
  let searchQuery = input.value.toLowerCase();
  if (searchQuery) {
    const icon = prefs[0].querySelector("i").outerHTML;
    const filteredPrefs = Array.from(prefs)
      .map((el) => {
        const spanEl = el.querySelector("span");
        const text = spanEl ? spanEl.innerText.toLowerCase() : "";
        if (
          text.includes(searchQuery) &&
          text.indexOf(searchQuery) === text.lastIndexOf(searchQuery)
        ) {
          return { innerText: text, prefID: el.getAttribute("prefID") };
        } else return null;
      })
      .filter((value) => value !== null);

    document.getElementById("pref-options").innerHTML = "";

    filteredPrefs.forEach((pref) => {
      const li = document.createElement("li");
      li.innerHTML = `<span>${icon}${pref.innerText}</span>`;
      li.prefID = pref.prefID;
      li.classList.add("pref-option");
      li.classList.add("pref-list-item");
      if (
        pref.innerText === document.getElementById("selectedPref").innerText
      ) {
        li.classList.add("selected");
      } else {
        li.addEventListener("click", (event) => updateSelect(event.target));
      }
      document.getElementById("pref-options").appendChild(li);
    });
  } else {
    prefs.forEach((node) => {
      document.getElementById("pref-options").appendChild(node);
    });
  }
};

window.onresize = () => {
  if (window.screenX < 1000 && isMenuOpen) {
    toggleMenuOpen();
  }
};

async function loadNewAlbums() {
  const year =
    Math.floor(Math.random() * (new Date().getFullYear() - 2005)) + 2005;
  let query = `https://api.discogs.com/database/search?style=drum+n+bass&per_page=10&format=album&format=Single&format=EP&type=release&type=master&year=${year}`;
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
        ? track.artists
            .map((art) => art.name)
            .join(" & ")
            .replaceAll(/\([^)]*\)/g, "") +
          " - " +
          track.title
        : data.artists
            .map((art) => art.name)
            .join(" & ")
            .replaceAll(/\([^)]*\)/g, "") +
          " - " +
          track.title;
      newSong.classList.add("new-song-wrapper");
      newSong.innerHTML = `
          <div class="song-cover">
        <img src="${cover}" alt="Cover Image">
          </div>
          <span class="song-title">${title}</span>
          <div class="song-links">
          <a title="Search on Youtube" target="_blank" href="https://youtube.com/results?search_query=${encodeURIComponent(
            title
          )}">
          <i class="fa-brands fa-youtube"></i></a>
        <a title="Search on Soundcloud" target="_blank" href="https://soundcloud.com/search?q=${title.replace(
          "&",
          "%26"
        )}"><i class="fa-brands fa-soundcloud"></i></a>
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
