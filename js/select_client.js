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

        const points_info = client.getAttribute("data-points");
        console.log(points_info);

        // Store an array with 2 objects
        const points_info_to_array = points_info.split(",");
        points_info_to_array.pop(); // bc the last item is always empty
        
        // data to store (and to use in the command)
        const client_points = [];

        // step of 4 because there are 4 data per point 
        for (let i = 0; i < points_info_to_array.length; i+=4) {
            let point = {};
            point.id_points = points_info_to_array[i];
            point.id_client = points_info_to_array[i+1];
            point.nb_points = points_info_to_array[i+2];
            point.exp_date = points_info_to_array[i+3];

            client_points.push(point);
        }

        sessionStorage.setItem("client_points",JSON.stringify(client_points));

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