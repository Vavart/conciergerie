const totalProducts = sessionStorage.getItem("nb_products");
const totalProductsInput = document.getElementsByName("total_products")[0];
totalProductsInput.value = totalProducts;

// Compute the price
const allPrices = []
for (let i = 0; i < totalProducts; i++) {
    let value = parseFloat(JSON.parse(sessionStorage.getItem(`product_item_${i+1}`))[2])
    allPrices.push(value);
}

const initialValue = 0;
const sumWithInitial = allPrices.reduce(
  (accumulator, currentValue) => accumulator + currentValue,
  initialValue
);

const totalPriceInput = document.getElementsByName("total_price")[0];
const restToPay = document.getElementsByName("rest_to_pay")[0];
totalPriceInput.value = sumWithInitial;
restToPay.value = sumWithInitial;