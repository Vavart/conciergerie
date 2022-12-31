// Redirection
const allClients = Array.from(document.querySelectorAll("tbody tr"));

allClients.forEach(client => {
    client.addEventListener("click", () => {
        const client_id = client.getAttribute("data-id");
        const client_code = client.getAttribute("data-code");
        const client_name = client.getAttribute("data-name");
        const client_mail = client.getAttribute("data-mail");

        sessionStorage.setItem("id_client", client_id);
        sessionStorage.setItem("code_client", client_code);
        sessionStorage.setItem("client_name", client_name);
        sessionStorage.setItem("client_mail", client_mail);

        window.location.href = "add_command.php";
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