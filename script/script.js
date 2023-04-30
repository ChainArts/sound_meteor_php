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
    console.log(window.location.href);
  if (window.location.href.includes("?status")) {
    checkMsgBox();
    window.history.pushState({}, "Hide", window.location.href.replace(/(?<=.*\/.*php).*/, "").replace(/.*\//, ""));
  }
  document.getElementsByTagName("body")[0].classList.add("fade");
};
