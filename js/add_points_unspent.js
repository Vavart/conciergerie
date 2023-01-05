const btnToAddPoints = document.querySelector(".add-points");
const contPoints = document.querySelector(".points-unspent");
const howManyPoints = document.getElementsByName("how_many_points_unspent")[0];
let nbOfPointsAdded = howManyPoints.value;

// Listening to every delete button already present
const allDeletePointsBtns = Array.from(document.querySelectorAll(".points-unspent .delete-btn"));

allDeletePointsBtns.forEach(btn => {
    btn.addEventListener("click", () => {
        const parent = btn.parentElement.parentElement;
        contPoints.removeChild(parent);
        nbOfPointsAdded--;
        howManyPoints.value = nbOfPointsAdded;

        // Update every name
        const allPointsInputs = Array.from(document.querySelectorAll(".cont-points.points-unspent input[type=text]"));
        const allDatePointsInputs = Array.from(document.querySelectorAll(".cont-points.points-unspent input[type=date]"));

        let i = 0;
        allPointsInputs.forEach(input => {
            input.setAttribute("name", `points_unspent_${i+1}`);
            i++;
        })

        i = 0;
        allDatePointsInputs.forEach(input => {
            input.setAttribute("name", `exp_date_unspent_${i+1}`);
            i++;
        })
    })
})

// Onclick add antoher point
btnToAddPoints.addEventListener("click", () => {

    nbOfPointsAdded++;
    howManyPoints.value = nbOfPointsAdded;

    // Creating elements
    const divSec = document.createElement("div");
    divSec.classList.add("sec");

    const divInput1 = document.createElement("div");
    divInput1.classList.add("cont-input");

    const label1 = document.createElement("label");
    const inputText = document.createElement("input");
    inputText.setAttribute("type", "text");
    inputText.setAttribute("name", `points_unspent_${nbOfPointsAdded}`);
    inputText.setAttribute("id", "points");
    inputText.setAttribute("placeholder", "300");
    inputText.required = true;

    divInput1.appendChild(label1);
    divInput1.appendChild(inputText);

    const divInput2 = document.createElement("div");
    divInput2.classList.add("cont-input");

    const label2 = document.createElement("label");
    const inputDate = document.createElement("input");
    inputDate.setAttribute("type", "date");
    inputDate.setAttribute("name", `exp_date_unspent_${nbOfPointsAdded}`);
    inputDate.setAttribute("id", "exp_date");
    inputDate.required = true;

    divInput2.appendChild(label2);
    divInput2.appendChild(inputDate);

    const divInput3 = document.createElement("div");
    divInput3.classList.add("cont-input");

    const deleteBtn = document.createElement("button");
    deleteBtn.innerText = "X";
    deleteBtn.classList.add("delete-btn");
    deleteBtn.setAttribute("type", "button")

    divInput3.appendChild(deleteBtn);

    // Add element to DOM
    divSec.appendChild(divInput1);
    divSec.appendChild(divInput2);
    divSec.appendChild(divInput3);

    contPoints.appendChild(divSec);

    // Listening to events (to delete)
    deleteBtn.addEventListener("click", () => {
        const parent = deleteBtn.parentElement.parentElement;
        contPoints.removeChild(parent);
        nbOfPointsAdded--;
        howManyPoints.value = nbOfPointsAdded;
        
        // Update every name
        const allPointsInputs = Array.from(document.querySelectorAll(".points-unspent input[type=text]"));
        const allDatePointsInputs = Array.from(document.querySelectorAll(".points-unspent input[type=date]"));

        let i = 0;
        allPointsInputs.forEach(input => {
            input.setAttribute("name", `points_unspent_${i+1}`);
            i++;
        })

        i = 0;
        allDatePointsInputs.forEach(input => {
            input.setAttribute("name", `exp_date_unspent_${i+1}`);
            i++;
        })
    });

    
})