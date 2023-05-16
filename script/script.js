const form = document.getElementById("form");

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

function checkMsgBox() {
  const msg_box = document.getElementsByClassName("state-box")[0];
  if (msg_box != null) {
    msg_box.classList.remove("hidden");
    setTimeout(() => {
      msg_box.classList.add("hidden");
    }, 5000);
  }
}

function activateForm() {
    const inputs = document.querySelectorAll('input, select');

    for (const input of inputs) {
        if(input.id != "always-disabled"){
            input.removeAttribute('disabled');
        }
    }
    document.getElementsByClassName("edit-form")[0].classList.add("hiddenform");
    document.getElementsByClassName("side-by-side hiddenform")[0].classList.remove("hiddenform");
}

window.onload = () => {
  if (window.location.href.includes("?status")) {
    checkMsgBox();
    window.history.pushState({}, "Hide", window.location.href.replace(/(?<=.*\/.*php).*/, "").replace(/.*\//, ""));
  }
  document.getElementsByTagName("body")[0].classList.add("fade");
};

let toggleIsOpen = (e) => {
    document.getElementsByClassName("nav-overlay")[0].classList.toggle("menu-open");
    document.getElementById("menu-icon").classList.toggle("menu-toggle-open")
    document.getElementById("menu-icon").classList.toggle("menu-toggle-closed")
}
