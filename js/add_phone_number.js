const btnToAddPhoneNumber = document.querySelector(".add-phone-number");
const contNumeros = document.querySelector(".cont-numeros");
const howManyPhoneNumbersInput = document.getElementsByName("how_many_phone_numbers")[0];
let nbOfNumbersAdded = howManyPhoneNumbersInput.value;


// Listening to every delete button already present
const allDeletePhoneBtns = Array.from(document.querySelectorAll(".cont-numeros .delete-btn"));
allDeletePhoneBtns.forEach(btn => {
    btn.addEventListener("click", () => {
        const parent = btn.parentElement.parentElement;
        contNumeros.removeChild(parent);
        nbOfNumbersAdded--;
        howManyPhoneNumbersInput.value = nbOfNumbersAdded;

        // Update every name
        const allPhoneInputs = Array.from(document.querySelectorAll(".cont-numeros input"));

        let i = 0;
        allPhoneInputs.forEach(input => {
        input.setAttribute("name", `phone_number_${i+1}`);
        i++;
        })

    })
})

// Onclick add antoher phone number
btnToAddPhoneNumber.addEventListener("click", () => {

    nbOfNumbersAdded++;
    howManyPhoneNumbersInput.value = nbOfNumbersAdded;

    // Creating elements
    const div = document.createElement("div");
    div.classList.add("cont-input");

    const innerDiv = document.createElement("div");
    innerDiv.classList.add("cont-input-btn");

    const deleteBtn = document.createElement("button");
    deleteBtn.innerText = "X";
    deleteBtn.classList.add("delete-btn");
    deleteBtn.setAttribute("type", "button")

    const input = document.createElement("input");
    input.setAttribute("type", "text");
    input.setAttribute("name", `phone_number_${nbOfNumbersAdded}`);
    input.setAttribute("id", "phone_number");
    input.setAttribute("placeholder", "+3364585956271");

    // Add element do DOM
    innerDiv.appendChild(input);
    innerDiv.appendChild(deleteBtn);
    div.appendChild(innerDiv);
    contNumeros.appendChild(div);

    // Listening to events (to delete)
    deleteBtn.addEventListener("click", () => {
        const parent = deleteBtn.parentElement.parentElement;
        contNumeros.removeChild(parent);
        nbOfNumbersAdded--;
        howManyPhoneNumbersInput.value = nbOfNumbersAdded;

        // Update every name
        const allPhoneInputs = Array.from(document.querySelectorAll(".cont-numeros input"));

        let i = 0;
        allPhoneInputs.forEach(input => {
        input.setAttribute("name", `phone_number_${i+1}`);
        i++;
        })
    });
})
