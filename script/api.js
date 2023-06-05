const auth_token = "Discogs token=tmaswzbNQlPUxhekudJyHsNNbUZxMXaPtxXfUYXa";
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
    return responseData;
  } catch (error) {
    // Handle any errors here
    console.error("Error:", error);
  }
};

const addPref = async (pref_id, type) => {
  const addPrefData = {
    action: "add",
    pref_id: pref_id,
    type: type,
  };
  const res = await sendPostRequest("./js_php_interface.php", addPrefData);
  if (res) {
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
    deleteButton.addEventListener(
      "click",
      (event) => delPref(pref_id, type, event.target),
      { once: true }
    );
    deleteButton.innerHTML = `<i class="fa-solid fa-trash"></i>`;
    li.innerHTML = `
                  <span>
                      <i class="fa-solid ${
                        type == "genre" ? "fa-music" : "fa-masks-theater"
                      }"></i>
                      <span>${
                        document.getElementById("selectedPref").innerText
                      }</span>
                  </span>`;
    li.appendChild(deleteButton);
    document.getElementsByClassName("pref-list")[0].appendChild(li);
      if (Number(nr) + 1 < 5) {
          //ABSOLUTE HACK LOL
          setTimeout(() => {
              document
                  .getElementsByClassName("pref-add")[0]
                  .classList.remove("disabled");
          }, 2000);
    }
  }
};

const delPref = async (pref_id, type, el) => {
    const delPrefData = {
        action: "delete",
        pref_id: pref_id,
        type: type,
    };
    const res = await sendPostRequest("./js_php_interface.php", delPrefData);
    if (res) {
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
            no_prefs.innerHTML = `<span class="no-pref-msg"> No ${type == "genre" ? "Genres" : "Moods"
                } preferences found </span>`;
            document.getElementsByClassName("pref-list")[0].prepend(no_prefs);
        }
        else if (Number(nr) - 1 == 4) {
            //ABSOLUTE HACK LOL
            setTimeout(() => {
                document
                    .getElementsByClassName("pref-add")[0]
                    .classList.remove("disabled");
            }, 2000);
        }
    }
};

const updatePref = async (value, type) => {
  const updatePrefData = {
    action: "update",
    value: value,
    type: type,
  };

  const res = await sendPostRequest("./js_php_interface.php", updatePrefData);
  if (res) {
    document
      .getElementsByTagName("main")[0]
      .prepend(genMsgBox("Preference updated"));
    checkMsgBox();
  }
};

const populateSongs = async (songlist, style, id) => {
    console.log('beforepost',songlist)
    const newSongData = {
        action: 'fill',
        songlist: songlist
    }
  try {
    const res = await sendPostRequest("./js_php_interface.php", newSongData);
      console.log(res);
      window.location.href = `./meteor.php?genPlaylist&style=${style}&sid=${id}`;
      document
      .getElementsByTagName("main")[0]
      .prepend(genMsgBox("New tracks added to database"));
      checkMsgBox();
      
      
  } catch (e) {
    console.error(e);
  }
};

const loadNewAlbums = async (usr_year, style, id) => {
    const year =
      Math.floor(Math.random() * (new Date().getFullYear() - usr_year)) +
      usr_year;
    let query = `https://api.discogs.com/database/search?style=${style
      .toLowerCase()
      .split(" ")
      .join(
        "+"
      )}&per_page=2&format=album&format=Single&format=EP&type=release&type=master&year=${year}`;
    try {
      const res_pages = await fetch(query, {
        headers: {
          Authorization: auth_token,
        },
      });
      let data = await res_pages.json();
      console.log(`AlbumData: \n`, data);
      const res = await fetch(
        query + `&page=${Math.floor(Math.random() * data.pagination.pages + 1)}`,
        {
          headers: {
            Authorization: auth_token,
          },
        }
      );
      data = await res.json();
      const promises = data.results.map((album) => {
        return loadNewSongs(album.id, album.thumb, album.uri, album.style);
      });
      const newSongs = await Promise.all(promises);
      const newSongArray = [].concat(...newSongs);
      populateSongs(newSongArray, style, id);
    } catch (e) {
      console.log(e);
    }
  };
  
  const loadNewSongs = async (album_id, cover, uri, styles) => {
    let songsArray = [];
    const songlist = document.getElementsByClassName("new-songs")[0];
    songlist.innerHTML = "";
    try {
      const res = await fetch(`https://api.discogs.com/releases/${album_id}`, {
        headers: {
          Authorization: auth_token,
        },
      });
      let data = await res.json();
      console.log(`SongData: \n`);
      console.log(data);
      data.tracklist.forEach((track) => {
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
        const songObj = {
          cover: cover,
          title: title,
          ytlink: `https://youtube.com/results?search_query=${encodeURIComponent(
            title
          )}`,
          sclink: `https://soundcloud.com/search?q=${title.replace("&", "%26")}`,
          discogs: `https://discogs.com/${uri.replace(/^\//, "")}`,
          year: data.year,
        };
        songsArray.push(songObj); // Add the song object to the array
      });
    } catch (e) {
      console.log(e);
    } finally {
      return { songsArray, styles };
    }
  };
  