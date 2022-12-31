// Redirection
const allArticles = Array.from(document.querySelectorAll("tbody tr"));

allArticles.forEach(client => {
    client.addEventListener("click", () => {

        // Update the number of articles
        let currNbProducts = sessionStorage.getItem("nb_products");
        currNbProducts++;
        sessionStorage.setItem("nb_products", currNbProducts);

        console.log(sessionStorage.getItem("nb_products"));

        const product_id = client.getAttribute("data-id");
        const product_name = client.getAttribute("data-name");
        const product_price = client.getAttribute("data-price");
        const product_status = client.getAttribute("data-status");
        const product_dispo = client.getAttribute("data-dispo");

        const productArray = [product_id, product_name, product_price, product_status, product_dispo]

        
        sessionStorage.setItem(`product_item_${currNbProducts}`, JSON.stringify(productArray));
        
        window.location.href = "add_command.php";
    })
});

// Filter
const searchInput = document.getElementsByName("search")[0];

searchInput.addEventListener("input", (e) => {

    let search = searchInput.value.toUpperCase();

    for (client of allArticles) {
        if (client.innerText.toUpperCase().indexOf(search) > -1) {
            client.style.display = "flex";
        } else {
            client.style.display = "none";
        }
    }

})