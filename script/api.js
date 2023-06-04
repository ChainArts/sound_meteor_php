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
  const res = await sendPostRequest("edit_pref", addPrefData);
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
      document
        .getElementsByClassName("pref-add")[0]
        .classList.remove("disabled");
    }
  }
};

const delPref = async (pref_id, type, el) => {
  const delPrefData = {
    action: "delete",
    pref_id: pref_id,
    type: type,
  };
  const res = await sendPostRequest("edit_pref", delPrefData);
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
      no_prefs.innerHTML = `<span class="no-pref-msg"> No ${
        type == "genre" ? "Genres" : "Moods"
      } preferences found </span>`;
      document.getElementsByClassName("pref-list")[0].prepend(no_prefs);
    }
  }
};

const updatePref = async (value, type) => {
  const updatePrefData = {
    action: "update",
    value: value,
    type: type,
  };

  const res = await sendPostRequest("edit_pref", updatePrefData);
  if (res) {
    document
      .getElementsByTagName("main")[0]
      .prepend(genMsgBox("Preference updated"));
    checkMsgBox();
  }
};

const populateSongs = async (songlist) => {
    console.log('beforepost',songlist)
    const newSongData = {
        action: 'fill',
        songlist: songlist
    }
  try {
    const res = await sendPostRequest("edit_pref", newSongData);
      console.log(res);
      document
      .getElementsByTagName("main")[0]
      .prepend(genMsgBox("Songs in Datenbank gekotzt"));
    checkMsgBox();
      
  } catch (e) {
    console.error(e);
  }
};
