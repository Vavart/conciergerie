// Redirection
const allClients = Array.from(document.querySelectorAll("tbody tr"));

allClients.forEach(client => {
    client.addEventListener("click", () => {
        const client_id = client.getAttribute("data-id");
        console.log(window.location);
        window.location.href = `client.php?id=${client_id}`
    })
});

// Filter
const searchInput = document.getElementsByName("search")[0];

searchInput.addEventListener("input", (e) => {

    let search = searchInput.value.toUpperCase();

    for (client of allClients) {
        if (client.innerText.toUpperCase().indexOf(search) > -1) {
            client.style.display = "flex";
        } else {
            client.style.display = "none";
        }
    }

})