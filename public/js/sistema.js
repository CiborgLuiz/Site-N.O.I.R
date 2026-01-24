window.addEventListener("load", () => {
    const bootScreen = document.getElementById("boot-screen");
    const desktop = document.getElementById("desktop");
    const sound = document.getElementById("boot-sound");

    setTimeout(() => {
        sound.play();
    }, 500);

    setTimeout(() => {
        bootScreen.classList.add("hidden");
        desktop.classList.remove("hidden");
    }, 4500);
});
