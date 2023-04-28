const form = document.getElementById("form");

form.addEventListener("focus", (event) => { event.target.parentElement.classList.add("focused") }, true);

form.addEventListener("blur", (event) => { event.target.parentElement.classList.remove("focused") }, true);