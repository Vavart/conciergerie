// Client inputs
const numeroClient = document.getElementsByName("numero")[0];
const codeClient = document.getElementsByName("code")[0];
const nameClient = document.getElementsByName("name_surname")[0];
const mailClient = document.getElementsByName("mail")[0];

// Client storage value
const client_id = sessionStorage.getItem("id_client");
const client_code = sessionStorage.getItem("code_client");
const client_name = sessionStorage.getItem("client_name");
const client_mail = sessionStorage.getItem("client_mail");
const client_points = JSON.parse(sessionStorage.getItem("client_points"));

// Get the point section
const point_section = document.querySelector(".cont-points");

// Get the product section
const productSection = document.querySelector(".product-section");

// ==============================================================
// ==================== INITIALIZATION BEGIN ====================
// ==============================================================

// If we are on the "add command page" we replace the values of the client inputs (not in "command page") because the client is already known
if (window.location.pathname.includes("add_command.php")) {

    numeroClient.value = client_id;
    codeClient.value = client_code;
    nameClient.value = client_name;
    mailClient.value = client_mail;

    if (client_points != null && client_points.length != 0) {
  
        for (let i = 0; i < client_points.length; i++) {
    
            // Creating a section for every unspent point
            const divSection = document.createElement("div");
            divSection.classList.add("section");

            const divSec1 = document.createElement("div");
            divSec1.classList.add("sec");
    
            // nb_points
            const divInput1 = document.createElement("div");
            divInput1.classList.add("cont-input");
            const labelInput1 = document.createElement("label");
            labelInput1.innerText = "Nombre de points";
            const input1 = document.createElement("input");
            input1.classList.add("locked");
            input1.setAttribute("type", "text");
            input1.setAttribute("name", `points_unspent_${i+1}`);
            input1.readOnly = true;
            input1.value = client_points[i]['nb_points'];
    
            divInput1.appendChild(labelInput1);
            divInput1.appendChild(input1);
    
            // date
            const divInput2 = document.createElement("div");
            divInput2.classList.add("cont-input");
            const labelInput2 = document.createElement("label");
            labelInput2.innerText = "Date d'expiration";
            const input2 = document.createElement("input");
            input2.classList.add("locked");
            input2.setAttribute("type", "date");
            input2.setAttribute("name", `exp_date_unspent_${i+1}`);
            input2.readOnly = true;
            input2.value = client_points[i]['exp_date'];
            
            divInput2.appendChild(labelInput2);
            divInput2.appendChild(input2);
            
            
            const divSec2 = document.createElement("div");
            divSec2.classList.add("sec");
            
            // checkbox : want to use for command
            const divInput3 = document.createElement("div");
            divInput3.classList.add("cont-input");
            divInput3.classList.add("checkbox");
            const labelInput3 = document.createElement("label");
            labelInput3.innerText = "Utiliser pour la commande";
            const input3 = document.createElement("input");
            input3.setAttribute("type", "checkbox");
            input3.setAttribute("name", `use_points_${i+1}`);
            
            divInput3.appendChild(labelInput3);
            divInput3.appendChild(input3);
            
            // reason for using points
            const divInput4 = document.createElement("div");
            divInput4.classList.add("cont-input");
            const labelInput4 = document.createElement("label");
            labelInput4.innerText = "Règle d'utilisation";
            const input4 = document.createElement("input");
            input4.setAttribute("type", "text");
            input4.setAttribute("name", `point_use_rule_${i+1}`);
            input4.setAttribute("placeholder", "20% de remise");

            // input hidden to get the id later in the php
            const inputHidden = document.createElement("input");
            inputHidden.setAttribute("type", "hidden");
            inputHidden.value = client_points[i]["id_points"];

            divInput4.appendChild(labelInput4);
            divInput4.appendChild(input4);
            divInput4.appendChild(inputHidden);
            
            // add to dom
            divSec1.appendChild(divInput1);
            divSec1.appendChild(divInput2);

            divSec2.appendChild(divInput3);
            divSec2.appendChild(divInput4);

            divSection.appendChild(divSec1);
            divSection.appendChild(divSec2);

            point_section.appendChild(divSection);
        }
    }

    else if (numeroClient != null && client_points.length === 0) {
        const p = document.createElement("p");
        p.innerText = "Ce client n'a aucun point disponible :(";
        point_section.appendChild(p);
    }

}

// If we are on the "command sheet" page we need to add the products to the session_storage first

// -- Products initialization
// Check if there is any existing articles
const howManyProductsInput = document.getElementsByName("how_many_products")[0];
let howManyProductsInputValue = howManyProductsInput.value;

// On load, initialize the number of products in the command
if (sessionStorage.getItem("nb_products") === null && howManyProductsInputValue == 0) {
    sessionStorage.setItem("nb_products", 0);
} else if (sessionStorage.getItem("nb_products") === null && howManyProductsInputValue > 0) {
    sessionStorage.setItem("nb_products", howManyProductsInputValue);
}
// -- //
const nb_products = sessionStorage.getItem("nb_products");
// -- //

// Id of products choosen for the command
const id_products_displayed = [];

if (sessionStorage.getItem("id_products_displayed") === null && howManyProductsInputValue == 0) {
    sessionStorage.setItem("id_products_displayed", JSON.stringify(id_products_displayed))

} else if (sessionStorage.getItem("id_products_displayed") === null && howManyProductsInputValue > 0) {

    // Add the existing products (command sheet page case)
    const allExistingArticles = Array.from(productSection.children);
    let i = 0;
    allExistingArticles.forEach(article => {

    // Get all the necessary information 
    const articleId = document.getElementsByName(`product_id_${i+1}`)[0].value;
    const articleName = document.getElementsByName(`product_name_${i+1}`)[0].value;
    const articleStatus = document.getElementsByName(`product_status_${i+1}`)[0].value;
    const articlePrice = document.getElementsByName(`product_price_${i+1}`)[0].value;
    const articleSoldPrice = document.getElementsByName(`product_sold_price_${i+1}`)[0].value;
    const articleStock = document.getElementsByName(`product_stock_${i+1}`)[0].value;
    const articleQuantity = document.getElementsByName(`product_quantity_${i+1}`)[0].value;

    const productArray = [articleId, articleName, articleStatus, articlePrice, articleSoldPrice, articleStock, articleQuantity];
    sessionStorage.setItem(`product_item_${i+1}`, JSON.stringify(productArray));
    id_products_displayed.push(articleId);
    i++;

    })

    // Update the current command
    sessionStorage.setItem("id_products_displayed", JSON.stringify(id_products_displayed));
}

// We added existing products, so we put back howManyProductsInputValue to 0, this value will be incremented when adding the products properly
howManyProductsInputValue = 0;
howManyProductsInput.value = howManyProductsInputValue;

// ==============================================================
// ===================== INITIALIZATION END =====================
// ==============================================================

// Before creating and adding all the products to the dom, we remove all possible remaining articles from the page (to add them properly later)
const productSectionItems = Array.from(productSection.children);
productSectionItems.forEach(child => {
    productSection.removeChild(child);
})


// Add properly all the products
for (let i = 0; i < nb_products; i++) {

    // Get the product
    const storedProduct = JSON.parse(sessionStorage.getItem(`product_item_${i+1}`));
    const articleId = storedProduct[0];
    const articleName = storedProduct[1];
    const articleStatus = storedProduct[2];
    const articlePrice = storedProduct[3];
    const articleSoldPrice = storedProduct[4];
    const articleStock = storedProduct[5];
    const articleQuantity = storedProduct[6];

    // Add a product to the total of product
    howManyProductsInputValue++;
    howManyProductsInput.value = howManyProductsInputValue;

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
    inputSec0.value = articleId;

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
    inputSec11.value = articleName;

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
    inputSec12.value = articleStatus;

    divSec1Input2.appendChild(labelSec1Input2)
    divSec1Input2.appendChild(inputSec12)

    divSec1.appendChild(inputSec0)
    divSec1.appendChild(divSec1Input1)
    divSec1.appendChild(divSec1Input2)

    // Sec 2 (unit price and sold price)
    const divSec2 = document.createElement("div");
    divSec2.classList.add("sec");

    // unit price
    const divSec2Input1 = document.createElement("div");
    divSec2Input1.classList.add("cont-input");
    const labelSec2Input1 = document.createElement("label");
    labelSec2Input1.innerText = "Prix unitaire (en €)";
    const inputSec21 = document.createElement("input");
    inputSec21.setAttribute("type", "number");
    inputSec21.setAttribute("name", `product_price_${i+1}`);
    inputSec21.classList.add("locked");
    inputSec21.readOnly = true;
    inputSec21.value = articlePrice;

    divSec2Input1.appendChild(labelSec2Input1)
    divSec2Input1.appendChild(inputSec21)

    // sold price
    const divSec2Input2 = document.createElement("div");
    divSec2Input2.classList.add("cont-input");
    const labelSec2Input2 = document.createElement("label");
    labelSec2Input2.innerText = "Prix vendu (en €)";
    const inputSec22 = document.createElement("input");
    inputSec22.setAttribute("type", "number");
    inputSec22.setAttribute("name", `product_sold_price_${i+1}`);
    inputSec22.setAttribute("placeholder", 20);
    inputSec22.required = true;
    inputSec22.value = articleSoldPrice;

    divSec2Input2.appendChild(labelSec2Input2)
    divSec2Input2.appendChild(inputSec22)

    divSec2.appendChild(divSec2Input1)
    divSec2.appendChild(divSec2Input2)

    // Sec 3 (nb_dispo and quantity)
    const divSec3 = document.createElement("div");
    divSec3.classList.add("sec");

    // nb dispo
    const divSec3Input1 = document.createElement("div");
    divSec3Input1.classList.add("cont-input");
    const labelSec3Input1 = document.createElement("label");
    labelSec3Input1.innerText = "Nombre en stock";
    const inputSec31 = document.createElement("input");
    inputSec31.setAttribute("type", "number");
    inputSec31.setAttribute("name", `product_stock_${i+1}`);
    inputSec31.classList.add("locked");
    inputSec31.readOnly = true;
    inputSec31.value = articleStock;

    divSec3Input1.appendChild(labelSec3Input1)
    divSec3Input1.appendChild(inputSec31)

    // quantity
    const divSec3Input2 = document.createElement("div");
    divSec3Input2.classList.add("cont-input");
    const labelSec3Input2 = document.createElement("label");
    labelSec3Input2.innerText = "Quantité";
    const inputSec32 = document.createElement("input");
    inputSec32.setAttribute("type", "number");
    inputSec32.setAttribute("name", `product_quantity_${i+1}`);
    inputSec32.setAttribute("placeholder", 2);
    inputSec32.required = true;
    inputSec32.value = articleQuantity;

    const divSec3InputBtn = document.createElement("div");
    divSec3InputBtn.classList.add("cont-input");
    const deleteBtn = document.createElement("button");
    deleteBtn.classList.add("delete-btn");
    deleteBtn.setAttribute("type", "button");
    deleteBtn.setAttribute("data-id", articleId)   ;
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

// =================================================
// ================== DELETE BEGIN =================
// =================================================

// Update names after delete
const allArticlesDeleteBtn = Array.from(document.querySelectorAll(".product-section .delete-btn"));
allArticlesDeleteBtn.forEach(btn => {
    btn.addEventListener("click", () => {

        // Substrack a product from the total of product
        howManyProductsInputValue--;
        howManyProductsInput.value = howManyProductsInputValue;

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

        // Redo the naming of all remaining articles
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

            // Clear the storage to update it well again
            sessionStorage.clear()

            // Redo the naming of session storage and re-add the necessary values
            sessionStorage.setItem("nb_products", currNbProducts);
            sessionStorage.setItem("id_products_displayed", JSON.stringify(id_products_displayed))

            const productArray = [allArticleInputs[0].value, allArticleInputs[1].value, allArticleInputs[2].value, allArticleInputs[3].value, allArticleInputs[4].value, allArticleInputs[5].value, allArticleInputs[6].value]

            sessionStorage.setItem(`product_item_${i+1}`, JSON.stringify(productArray));
            i++;
        }
    })
})

// =================================================
// =================== DELETE END ==================
// =================================================

// Save the quantities and sold_price when choosing a new product
const chooseNewProduct = document.querySelector(".choose-product");
chooseNewProduct.addEventListener("click", () => {
    for (let i = 0; i < nb_products; i++) {
    
        const articleSoldPrice = document.getElementsByName(`product_sold_price_${i+1}`)[0].value;
        const articleQuantity = document.getElementsByName(`product_quantity_${i+1}`)[0].value;
    
        const storedProduct = JSON.parse(sessionStorage.getItem(`product_item_${i+1}`));
        storedProduct[4] = articleSoldPrice;
        storedProduct[6] = articleQuantity;
    
        sessionStorage.setItem(`product_item_${i+1}`, JSON.stringify(storedProduct));
    }
})

// Clear storage when we leave the page (= click on 'cancel' or click on 'back to search')

const cancelBtn = document.querySelector(".cancel");
const menuBtn = document.querySelector(".menu");

cancelBtn.addEventListener("click", () => {
    sessionStorage.clear();
})

menuBtn.addEventListener("click", () => {
    sessionStorage.clear();
})