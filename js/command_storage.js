// Client
const numeroClient = document.getElementsByName("numero")[0];
const codeClient = document.getElementsByName("code")[0];
const nameClient = document.getElementsByName("name_surname")[0];
const mailClient = document.getElementsByName("mail")[0];

const client_id = sessionStorage.getItem("id_client");
const client_code = sessionStorage.getItem("code_client");
const client_name = sessionStorage.getItem("client_name");
const client_mail = sessionStorage.getItem("client_mail");

// if we are on the add command page we replace the values of the client inputs (not in command page)
if (window.location.pathname.includes("add_command.php")) {
    numeroClient.value = client_id;
    codeClient.value = client_code;
    nameClient.value = client_name;
    mailClient.value = client_mail;
}


// if we are on the command page we need to add the products to the session_storage


// Products
const howManyProductsInput = document.getElementsByName("how_many_products")[0];
let nbOfProductsAdded = howManyProductsInput.value;

// Initializing the number of products in the command
if (sessionStorage.getItem("nb_products") === null) {
    sessionStorage.setItem("nb_products", 0);
}

if (sessionStorage.getItem("id_products_displayed") === null) {
    sessionStorage.setItem("id_products_displayed", JSON.stringify([]))
}

const nb_products = sessionStorage.getItem("nb_products");
const productSection = document.querySelector(".product-section");

for (let i = 0; i < nb_products; i++) {
    // -- Create a full product

    // Add a product to the total of product
    nbOfProductsAdded++;
    howManyProductsInput.value = nbOfProductsAdded;

    // Get the product
    const storedProduct = JSON.parse(sessionStorage.getItem(`product_item_${i+1}`));

    const divSection = document.createElement("div");
    divSection.classList.add("section");

    // Sec 1 (name and status)
    const divSec1 = document.createElement("div");
    divSec1.classList.add("sec");

    // hidden product id
    const inputSec0 = document.createElement("input");
    inputSec0.setAttribute("type", "hidden");
    inputSec0.setAttribute("name", `product_id_${i+1}`);
    inputSec0.classList.add("locked");
    inputSec0.readOnly = true;
    inputSec0.value = storedProduct[0];

    // product name
    const divSec1Input1 = document.createElement("div");
    divSec1Input1.classList.add("cont-input");
    divSec1Input1.classList.add("large");
    const labelSec1Input1 = document.createElement("label");
    labelSec1Input1.innerText = "Nom du produit";
    const inputSec11 = document.createElement("input");
    inputSec11.setAttribute("type", "text");
    inputSec11.setAttribute("name", `product_name_${i+1}`);
    inputSec11.classList.add("locked");
    inputSec11.readOnly = true;
    inputSec11.value = storedProduct[1]

    divSec1Input1.appendChild(labelSec1Input1)
    divSec1Input1.appendChild(inputSec11)

    // status
    const divSec1Input2 = document.createElement("div");
    divSec1Input2.classList.add("cont-input");
    const labelSec1Input2 = document.createElement("label");
    labelSec1Input2.innerText = "Statut";
    const inputSec12 = document.createElement("input");
    inputSec12.setAttribute("type", "text");
    inputSec12.setAttribute("name", `product_status_${i+1}`);
    inputSec12.classList.add("locked");
    inputSec12.readOnly = true;
    inputSec12.value = storedProduct[3]

    divSec1Input2.appendChild(labelSec1Input2)
    divSec1Input2.appendChild(inputSec12)

    divSec1.appendChild(inputSec0)
    divSec1.appendChild(divSec1Input1)
    divSec1.appendChild(divSec1Input2)

    // Sec 2 (unit price and sold price)
    const divSec2 = document.createElement("div");
    divSec2.classList.add("sec");

    const divSec2Input1 = document.createElement("div");
    divSec2Input1.classList.add("cont-input");
    const labelSec2Input1 = document.createElement("label");
    labelSec2Input1.innerText = "Prix unitaire (en €)";
    const inputSec21 = document.createElement("input");
    inputSec21.setAttribute("type", "number");
    inputSec21.setAttribute("name", `product_price_${i+1}`);
    inputSec21.classList.add("locked");
    inputSec21.readOnly = true;
    inputSec21.value = storedProduct[2]

    divSec2Input1.appendChild(labelSec2Input1)
    divSec2Input1.appendChild(inputSec21)

    const divSec2Input2 = document.createElement("div");
    divSec2Input2.classList.add("cont-input");
    const labelSec2Input2 = document.createElement("label");
    labelSec2Input2.innerText = "Prix vendu (en €)";
    const inputSec22 = document.createElement("input");
    inputSec22.setAttribute("type", "number");
    inputSec22.setAttribute("name", `product_sold_price_${i+1}`);
    inputSec22.setAttribute("placeholder", 20);
    inputSec22.required = true;

    divSec2Input2.appendChild(labelSec2Input2)
    divSec2Input2.appendChild(inputSec22)

    divSec2.appendChild(divSec2Input1)
    divSec2.appendChild(divSec2Input2)

    // Sec 3 (nb_dispo and quantity)
    const divSec3 = document.createElement("div");
    divSec3.classList.add("sec");

    const divSec3Input1 = document.createElement("div");
    divSec3Input1.classList.add("cont-input");
    const labelSec3Input1 = document.createElement("label");
    labelSec3Input1.innerText = "Nombre en stock";
    const inputSec31 = document.createElement("input");
    inputSec31.setAttribute("type", "number");
    inputSec31.setAttribute("name", `product_stock_${i+1}`);
    inputSec31.classList.add("locked");
    inputSec31.readOnly = true;
    inputSec31.value = storedProduct[4]

    divSec3Input1.appendChild(labelSec3Input1)
    divSec3Input1.appendChild(inputSec31)

    const divSec3Input2 = document.createElement("div");
    divSec3Input2.classList.add("cont-input");
    const labelSec3Input2 = document.createElement("label");
    labelSec3Input2.innerText = "Quantité";
    const inputSec32 = document.createElement("input");
    inputSec32.setAttribute("type", "number");
    inputSec32.setAttribute("name", `product_quantity_${i+1}`);
    inputSec32.setAttribute("placeholder", 2);
    inputSec32.required = true;

    const divSec3InputBtn = document.createElement("div");
    divSec3InputBtn.classList.add("cont-input");
    const deleteBtn = document.createElement("button");
    deleteBtn.classList.add("delete-btn");
    deleteBtn.setAttribute("type", "button");
    deleteBtn.setAttribute("data-id", storedProduct[0])   
    deleteBtn.setAttribute("data-order", i+1)   
    deleteBtn.innerText = "X";

    divSec3Input2.appendChild(labelSec3Input2)
    divSec3Input2.appendChild(inputSec32)
    divSec3InputBtn.appendChild(deleteBtn);

    divSec3.appendChild(divSec3Input1)
    divSec3.appendChild(divSec3Input2)
    divSec3.appendChild(divSec3InputBtn)

    // Add to dom
    divSection.appendChild(divSec1)
    divSection.appendChild(divSec2)
    divSection.appendChild(divSec3)
    productSection.appendChild(divSection)
}

// Update names after delete
const allArticlesDeleteBtn = Array.from(document.querySelectorAll(".product-section .delete-btn"));
allArticlesDeleteBtn.forEach(btn => {
    btn.addEventListener("click", () => {

        // Substrack a product from the total of product
        nbOfProductsAdded--;
        howManyProductsInput.value = nbOfProductsAdded;

        const parent = btn.parentElement.parentElement.parentElement;
        
        // Update the number of articles before deleting
        const productToDeleteId = String(btn.getAttribute("data-id"));

        const id_products_displayed = JSON.parse(sessionStorage.getItem("id_products_displayed")) 

        let indexOfProduct = id_products_displayed.indexOf(productToDeleteId)
        delete id_products_displayed[indexOfProduct];

        sessionStorage.setItem("id_products_displayed", JSON.stringify(id_products_displayed))

        let currNbProducts = sessionStorage.getItem("nb_products")
        currNbProducts--;
        sessionStorage.setItem("nb_products", currNbProducts);
        
        let articleIndex = btn.getAttribute("data-order");
        sessionStorage.removeItem(`product_item_${articleIndex}`)
        productSection.removeChild(parent);

        // Redo the naming
        const allArticles = Array.from(document.querySelectorAll(".product-section .section"))
        let i = 0;
        for (let article of allArticles) {
            const allArticleInputs = article.querySelectorAll("input");
            
            allArticleInputs[0].setAttribute("name", `product_id_${i+1}`)
            allArticleInputs[1].setAttribute("name", `product_name_${i+1}`)
            allArticleInputs[2].setAttribute("name", `product_status_${i+1}`)
            allArticleInputs[3].setAttribute("name", `product_price_${i+1}`)
            allArticleInputs[4].setAttribute("name", `product_sold_price_${i+1}`)
            allArticleInputs[5].setAttribute("name", `product_stock_${i+1}`)
            allArticleInputs[6].setAttribute("name", `product_quantity_${i+1}`)


            sessionStorage.clear()
            // Redo the naming of session storage
            sessionStorage.setItem("nb_products", currNbProducts);

            sessionStorage.setItem("id_products_displayed", JSON.stringify(id_products_displayed))

            const productArray = [allArticleInputs[0].value, allArticleInputs[1].value, allArticleInputs[2].value, allArticleInputs[3].value, allArticleInputs[4].value, allArticleInputs[5].value]

            sessionStorage.setItem(`product_item_${i+1}`, JSON.stringify(productArray));

            i++;
        }

    })
})
