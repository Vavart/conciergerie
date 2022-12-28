const btnToAddPhoneNumber = document.querySelector(".add-phone-number");
const contNumeros = document.querySelector(".cont-numeros");
let nbOfNumbersAdded = 0;

// Onclick add antoher phone number
btnToAddPhoneNumber.addEventListener("click", () => {

    nbOfNumbersAdded++;

    // Creating element
    const div = document.createElement("div");
    div.classList.add("cont-input");
    const input = document.createElement("input");
    input.setAttribute("type", "text")
    input.setAttribute("name", `phone_number_${nbOfNumbersAdded}`);
    input.setAttribute("id", "phone_number")
    input.setAttribute("placeholder", "+3364585956271")

    // Add element do DOM
    div.appendChild(input)
    contNumeros.appendChild(div)

})