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

window.onload = () => {
  if (window.location.href.includes("/usrmngmt.php?status")) {
    checkMsgBox();
    window.history.pushState({}, "Hide", "/usrmngmt.php");
  }
  document.getElementsByTagName("body")[0].classList.add("fade");
};
