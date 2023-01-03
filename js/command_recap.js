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

    // Get the 2 inputs (sold_price and quantity) of the articles
    const soldPriceInput = document.getElementsByName(`product_sold_price_${i+1}`)[0];
    const quantityInput = document.getElementsByName(`product_quantity_${i+1}`)[0];

    // Get the sold price and the quantity to compute the cost of the article
    const soldPrice = Number(soldPriceInput.value);
    const quantity = Number(quantityInput.value);
    
    const article_value = soldPrice * quantity;
    allPrices.push(article_value);
  }

  // Compute the sum
  const initialValue = 0;
  let sumOfAllPrices = allPrices.reduce(
    (accumulator, currentValue) => accumulator + currentValue,
    initialValue
  );

  // Get all payment amounts
  const allPaymentAmountsInputs = document.querySelectorAll("#payment_amount");

  let allPaymentAmounts = 0;
  allPaymentAmountsInputs.forEach(input => {
    if (input.value.length > 0) {
      allPaymentAmounts += Number(input.value);
    }
  })

  let price = sumOfAllPrices;
  if (deliveryPriceInput.value.length > 0) {
    price +=  Number(deliveryPriceInput.value);
  } 

  if (servicePriceInput.value.length > 0) {
    price +=  Number(servicePriceInput.value);
  } 

  let rest_to_pay = price - allPaymentAmounts;

  totalPriceInput.value = price;
  restToPay.value = rest_to_pay;
}

computCostBtn.addEventListener("click", computeCosts);
const form = document.querySelector("form");
form.addEventListener("submit", (e) => {
    computeCosts();
    sessionStorage.clear();
})