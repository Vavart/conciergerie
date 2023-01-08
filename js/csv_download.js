// Matching status
const command_status_matching = {
    'to_buy' : 'À acheter',
    'bought' : 'Achetée',
    'packed' : 'Emballée',
    'shipped' : 'Expédiée',
    'arrived' : 'Arrivée',
    'delivered' : 'Livrée',
    'done' : 'Terminée'
};

function dataToCSV() {

	// final csv data
	let csv_data = [];

    // Write the first line (= labels)
    const firstRow = ["Numero commande", "Numero client", "Date de commande", "Items", "Prix total", "Frais de livraison", "Frais de service", "Montant déjà déposé", "Reste à payer", "Points générés", "Statut", "Date d'arrivée", "Note"]

    // Combine each value with comma
    csv_data.push(firstRow.join(","));

    // Now for every row 
    const rows = document.querySelectorAll('tr');
    // we ignore the first row (= labels)

    for (let i = 1; i < rows.length; i++) {
        const row = [];

        row.push(rows[i].getAttribute("data-cmd-code"));
        row.push(rows[i].getAttribute("data-client-code"));
        row.push(rows[i].getAttribute("data-cmd-date"));
        row.push(rows[i].getAttribute("data-items"));
        row.push(rows[i].getAttribute("data-cmd-total-price"));
        row.push(rows[i].getAttribute("data-delivery-price"));
        row.push(rows[i].getAttribute("data-service-price"));
        row.push(rows[i].getAttribute("data-total-deposits-amounts"));
        row.push(rows[i].getAttribute("data-rest-to-pay"));
        row.push(rows[i].getAttribute("data-points"));
        row.push(command_status_matching[rows[i].getAttribute("data-status")]);
        row.push(rows[i].getAttribute("data-cmd-arrival-date"));
        row.push(rows[i].getAttribute("data-note"));

        csv_data.push(row.join(","));
    }

	// Combine each row data with new line character
	csv_data = csv_data.join('\n');

    // Download
    downloadCSVFile(csv_data);
}

function downloadCSVFile(csv_data) {

	// Create CSV file object and feed our csv_data into it
	CSVFile = new Blob([csv_data], { type: "text/csv" });

	// Create to temporary link to initiate the download process
	const temp_link = document.createElement('a');

	// Download csv file
	temp_link.download = "commands.csv";
	const url = window.URL.createObjectURL(CSVFile);
	temp_link.href = url;

	// This link should not be displayed
	temp_link.style.display = "none";
	document.body.appendChild(temp_link);

	// Automatically click the link to trigger download
	temp_link.click();
	document.body.removeChild(temp_link);
}

const downloadBtn = document.querySelector(".export-csv");
downloadBtn.addEventListener("click", dataToCSV);
