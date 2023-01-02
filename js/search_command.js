// Redirection
const allCommands = Array.from(document.querySelectorAll("tbody tr"));

allCommands.forEach(command => {
    command.addEventListener("click", () => {
        const command_id = command.getAttribute("data-id");
        // console.log(window.location);
        window.location.href = `command_sheet.php?id=${command_id}`
    })
});

// Filter
const searchInput = document.getElementsByName("search")[0];

searchInput.addEventListener("input", (e) => {

    let search = searchInput.value.toUpperCase();

    for (command of allCommands) {
        if (command.innerText.toUpperCase().indexOf(search) > -1) {
            command.style.display = "flex";
        } else {
            command.style.display = "none";
        }
    }

})