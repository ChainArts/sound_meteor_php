const form = document.getElementById("form");
let initialEditState =
  !window.location.href.includes("register") &&
  !window.location.href.includes("login") && (!!document.getElementById("edit-form"))
    ? document.getElementById("edit-form").innerHTML
    : "";

let isMenuOpen,
  isUserOpen,
  userEdited = false;

if (
  window.location.pathname == "/~fhs49272/mmp1/" ||
    window.location.pathname.includes("generateMeteor") ||
    window.location.pathname.includes("index")
) {
  particlesJS.load("particles-js", "script/particles.json", function () {
    console.log("callback - particles.js config loaded");
  });
}

document.addEventListener("mousemove", parallax);
function parallax(event) {
  this.querySelectorAll(".mouse").forEach((shift) => {
    const position = shift.getAttribute("data-value");
    const rotation = shift.getAttribute("data-rot");
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

    if(document.getElementById("usr-name-edit").innerText !== document.getElementById("usr-name-input").value) {
        document.getElementById("usr-name-input").value = document.getElementById("usr-name-edit").innerText;
    }
    if(document.getElementById("usr-mail-edit").innerText !== document.getElementById("usr-mail-input").value) {
        document.getElementById("usr-mail-input").value = document.getElementById("usr-mail-edit").innerText;
    }
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
    : (el.getElementsByTagName("span")[0].innerHTML =
        el.getElementsByClassName("edit-box")[0].value);
};

const checkUserEdit = async () => {
    const newUser = document.getElementById("usr-name-edit").innerText;
    const newEmail = document.getElementById("usr-mail-edit").innerText;
    const tempEdit = document.createElement('div');
    tempEdit.innerHTML = initialEditState;
    if(newUser === tempEdit.querySelector('#usr-name-edit').innerText && newEmail === tempEdit.querySelector('#usr-mail-edit').innerText) {
        cleanupUserEdit();
    }
    else {
        const res = await updateUserData(newUser, newEmail)
        if (res) {
            document.querySelector('.usr-details > span').innerText = newUser;
            document.querySelector('.profile-name').innerText = newUser;
            tempEdit.querySelector('#usr-name-input').value = newUser;
            tempEdit.querySelector('#usr-name-edit').innerText = newUser;
            tempEdit.querySelector('#usr-mail-input').value = newEmail;
            tempEdit.querySelector('#usr-mail-edit').innerText = newEmail;
            initialEditState = tempEdit.innerHTML;
            await cleanupUserEdit();
        }
    }
}

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

const updateGenSelect = (el, type) => {
  toggleDropDown(type);
  document.getElementById(type).querySelector("#selectedPref").innerText =
    el.firstChild.innerText;
  updatePref(el.firstChild.innerText, type.split("-").pop());
};

const prefs = document.querySelectorAll("[prefID]");

const updateSelect = (el) => {
  document.getElementById("selectedPref").innerText =
    el.closest("li").firstChild.innerText;
  document
    .getElementById("selectedPref")
    .setAttribute("selected_id", el.getAttribute("prefid"));
  toggleDropDown("select");
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
const toggleDropDown = (toggleId) => {
  document.getElementById(toggleId).classList.toggle("active");
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
          return { innerText: text, prefID: el.getAttribute("prefid") };
        } else return null;
      })
      .filter((value) => value !== null);

    document.getElementById("pref-options").innerHTML = "";

    filteredPrefs.forEach((pref) => {
      const li = document.createElement("li");
      li.innerHTML = `<span>${icon}${pref.innerText}</span>`;
      li.setAttribute('prefID', pref.prefID);
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

//Function developed with Sandra Scheipl
const checkPassword = () => {
  const submit = document.getElementById("register");
  const pwd = document.getElementById("pass").value;
  const verPwd = document.getElementById("confPass").value;
  const error = document.getElementById("confErr");

  if ((submit.disabled = pwd != verPwd)) {
    submit.style.borderColor = "tomato";
    error.innerHTML = "Passwords do not match!";
  } else {
    submit.style.borderColor = "var(--accent)";
    error.innerHTML = "";
  }
}

const checkScrollIndicators = () => {
    const scrollContainers = document.querySelectorAll('.scroll-cont');
    scrollContainers.forEach(container => {
        const simpleBar = new SimpleBar(container.querySelector('.meteor-scroll'));
        const chevronLeft = container.querySelector('.chevron-left');
        const chevronRight = container.querySelector('.chevron-right');
        updateChevrons(simpleBar, chevronLeft, chevronRight);
        setSimplebarListener(simpleBar, chevronLeft, chevronRight);
        window.onresize = () => {
            simpleBar.recalculate();
            updateChevrons(simpleBar, chevronLeft, chevronRight);
        }
    });
}

const setSimplebarListener = (simpleBar, chevronLeft, chevronRight) => {
    const container = simpleBar.getScrollElement();
    container.addEventListener('scroll', () => {
        updateChevrons(simpleBar, chevronLeft, chevronRight);
    });
}

const updateChevrons = (simpleBar, chevronLeft, chevronRight) => {
    const container = simpleBar.getScrollElement();
    const scrollLeft = container.scrollLeft === 0;
    const scrollRight = container.scrollLeft === container.scrollWidth - container.clientWidth;

    if (container.scrollWidth > container.clientWidth) {
        chevronLeft.style.display = scrollLeft ? 'none' : 'initial';
        chevronRight.style.display = scrollRight ? 'none' : 'initial';
    } else {
        chevronLeft.style.display = 'none';
        chevronRight.style.display = 'none';
    }
}

if (window.location.pathname.includes("meteor.php")) {
    checkScrollIndicators();
}

const addGenButton = () => {
    if (!document.getElementById("genBtn")) {
        document.getElementsByClassName("genBtn")[0].innerHTML =
            `<div id="genBtn" class="generate-button" style="font-size: 1.2rem;">
                <div class="button">
                    <a href="./generateMeteor.php"><span>generate</span></a>
            </div>
        </div>`;
    }
}