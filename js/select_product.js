// Redirection
const allArticles = Array.from(document.querySelectorAll("tbody tr"));

allArticles.forEach(client => {
    client.addEventListener("click", () => {

        const id_products_displayed = JSON.parse(sessionStorage.getItem("id_products_displayed")) 

        const product_id = client.getAttribute("data-id");

        // Check if we already added this article
        if (!id_products_displayed.includes(product_id)) {

             id_products_displayed.push(product_id)
             sessionStorage.setItem("id_products_displayed", JSON.stringify(id_products_displayed))

             // Update the number of articles
             let currNbProducts = sessionStorage.getItem("nb_products");
             currNbProducts++;
             sessionStorage.setItem("nb_products", currNbProducts);
     
            //  console.log(sessionStorage.getItem("nb_products"));
     
             const product_name = client.getAttribute("data-name");
             const product_price = client.getAttribute("data-price");
             const product_status = client.getAttribute("data-status");
             const product_dispo = client.getAttribute("data-dispo");
     
             const productArray = [product_id, product_name, product_price, product_status, product_dispo]
     
             
             sessionStorage.setItem(`product_item_${currNbProducts}`, JSON.stringify(productArray));
             
            } 
        
        // determine where we relocate from the url parameter
        const urlSearchParams = new URLSearchParams(window.location.search);
        const params = Object.fromEntries(urlSearchParams.entries());
        if (params.for === "add") {
            window.location.href = "add_command.php";
        } else if (params.for === "sheet") {
            window.location.href = `command_sheet.php?id=${params.id}`;
        }
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