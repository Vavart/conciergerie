const computCostBtn = document.querySelector(".compute-btn");

function computeCosts() {
  const totalPriceInput = document.getElementsByName("total_price")[0];
  const restToPay = document.getElementsByName("rest_to_pay")[0];
  
  // Listen to delivery and service price
  const deliveryPriceInput = document.getElementsByName("delivery_fee")[0];
  const servicePriceInput = document.getElementsByName("service_fee")[0];

  const totalProducts = sessionStorage.getItem("nb_products");

  // Compute the price
  let allPrices = []
  for (let i = 0; i < totalProducts; i++) {
      let value = parseFloat(JSON.parse(sessionStorage.getItem(`product_item_${i+1}`))[2])
      allPrices.push(value);
  }

  const initialValue = 0;
  let sumWithInitial = allPrices.reduce(
    (accumulator, currentValue) => accumulator + currentValue,
    initialValue
  );

  sumWithInitial = parseFloat(sumWithInitial);


  // Get all payment amounts
  const allPaymentAmountsInputs = document.querySelectorAll("#payment_amount");

  let allPaymentAmounts = 0;
  allPaymentAmountsInputs.forEach(input => {
    if (input.value.length > 0) {
      allPaymentAmounts += parseFloat(input.value);
    }
  })

  let price = sumWithInitial;
  if (deliveryPriceInput.value.length > 0) {
    price +=  parseFloat(deliveryPriceInput.value);
  } 

  if (servicePriceInput.value.length > 0) {
    price +=  parseFloat(servicePriceInput.value);
  } 

  let rest_to_pay = price - allPaymentAmounts;
  console.log(price);

  totalPriceInput.value = price;
  restToPay.value = rest_to_pay;
}

computCostBtn.addEventListener("click", computeCosts);


const form = document.querySelector("form");
form.addEventListener("submit", (e) => {
    computeCosts();
    sessionStorage.clear();

    // à supprimer après les tests
    // e.preventDefault();
    // window.location.reload();

})