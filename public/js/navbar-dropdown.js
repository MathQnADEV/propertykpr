document.addEventListener("DOMContentLoaded", () => {
    const profileButton = document.getElementById("Profile");
    if (!profileButton) return;

    const dropdownMenu = profileButton.querySelector("ul");

    profileButton.addEventListener("click", (e) => {
        e.stopPropagation();
        dropdownMenu.classList.toggle("hidden");
    });

    document.addEventListener("click", () => {
        if (!dropdownMenu.classList.contains("hidden")) {
            dropdownMenu.classList.add("hidden");
        }
    });

    dropdownMenu.addEventListener("click", (e) => {
        e.stopPropagation();
        dropdownMenu.classList.add("hidden");
    });
});
