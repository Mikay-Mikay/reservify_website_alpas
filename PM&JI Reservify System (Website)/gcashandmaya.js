// Show/Hide Gcash Tutorial
const showGcashTutorial = document.getElementById("showGcashTutorial");
const gcashTutorial = document.getElementById("gcashTutorial");

if (showGcashTutorial) {
    showGcashTutorial.addEventListener("click", () => {
        gcashTutorial.classList.toggle("hidden");
    });
}

// Show/Hide Maya Tutorial
const showMayaTutorial = document.getElementById("showMayaTutorial");
const mayaTutorial = document.getElementById("mayaTutorial");

if (showMayaTutorial) {
    showMayaTutorial.addEventListener("click", () => {
        mayaTutorial.classList.toggle("hidden");
    });
}
