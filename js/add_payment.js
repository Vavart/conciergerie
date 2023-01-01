const btnToAddPayment = document.querySelector(".add-payment");
const contPayment = document.querySelector(".cont-payment");
const howManyPaymentInput = document.getElementsByName("how_many_payments")[0];
let nbOfPaymentAdded = howManyPaymentInput.value;

// Listening to every delete button already present
const allDeletePaymentBtns = Array.from(document.querySelectorAll(".cont-payment .delete-btn"));
allDeletePaymentBtns.forEach(btn => {
    btn.addEventListener("click", () => {
        const parent = btn.parentElement.parentElement;
        contPayment.removeChild(parent);
        nbOfPaymentAdded--;
        howManyPaymentInput.value = nbOfPaymentAdded;

        // Update every name
        const allPaymentInputsPrice = Array.from(document.querySelectorAll(".cont-payment input[type=number]"));
        const allPaymentInputsMethod = Array.from(document.querySelectorAll(".cont-payment input[type=text]"));

        let i = 0;
        allPaymentInputsPrice.forEach(input => {
            input.setAttribute("name", `payment_price_${i+1}`);
            i++;
        })
        i = 0;
        allPaymentInputsMethod.forEach(input => {
            input.setAttribute("name", `payment_method_${i+1}`);
            i++;
        })
    })
})

// Onclick add antoher phone number
btnToAddPayment.addEventListener("click", () => {

    nbOfPaymentAdded++;
    howManyPaymentInput.value = nbOfPaymentAdded;

    // Creating elements
    const divSec = document.createElement("div");
    divSec.classList.add("sec");

    const divInput1 = document.createElement("div");
    divInput1.classList.add("cont-input");
    const labelInput1 = document.createElement("label");
    labelInput1.innerText = "Dépôt (en €)";
    const input1 = document.createElement("input");
    input1.setAttribute("type", "number");
    input1.setAttribute("name", `payment_amount_${nbOfPaymentAdded}`);
    input1.setAttribute("placeholder", 45);
    input1.setAttribute("id", "payment_amount")
    input1.required = true;

    divInput1.appendChild(labelInput1);
    divInput1.appendChild(input1);

    const divInput2 = document.createElement("div");
    divInput2.classList.add("cont-input");
    const labelInput2 = document.createElement("label");
    labelInput2.innerText = "Mode de paiement";
    const input2 = document.createElement("input");
    input2.setAttribute("type", "text");
    input2.setAttribute("name", `payment_method_${nbOfPaymentAdded}`);
    input2.setAttribute("placeholder", "Carte");
    input2.required = true;

    divInput2.appendChild(labelInput2);
    divInput2.appendChild(input2);

    const divDeleteBtn = document.createElement("div");
    divDeleteBtn.classList.add("cont-input");
    const deleteBtn = document.createElement("button");
    deleteBtn.classList.add("delete-btn");
    deleteBtn.setAttribute("type", "button");
    deleteBtn.innerText = "X";

    divDeleteBtn.appendChild(deleteBtn);

    divSec.appendChild(divInput1);
    divSec.appendChild(divInput2);
    divSec.appendChild(divDeleteBtn);

    contPayment.appendChild(divSec);

    // Listening to events (to delete)
    deleteBtn.addEventListener("click", () => {
        const parent = deleteBtn.parentElement.parentElement;
        contPayment.removeChild(parent);
        nbOfPaymentAdded--;
        howManyPaymentInput.value = nbOfPaymentAdded;

        // Update every name
        const allPaymentInputsPrice = Array.from(document.querySelectorAll(".cont-payment input[type=number]"));
        const allPaymentInputsMethod = Array.from(document.querySelectorAll(".cont-payment input[type=text]"));

        let i = 0;
        allPaymentInputsPrice.forEach(input => {
            input.setAttribute("name", `payment_price_${i+1}`);
            i++;
        })
        i = 0;
        allPaymentInputsMethod.forEach(input => {
            input.setAttribute("name", `payment_method_${i+1}`);
            i++;
        })
    });
})
