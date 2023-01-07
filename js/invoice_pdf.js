const generateInvoiceBtn = document.querySelector(".invoice-btn");
const pdfToSend = document.querySelector(".page");


generateInvoiceBtn.addEventListener("click", () => {

    html2pdf(pdfToSend).save();
    
    // const opt = {
    //     margin: 1,
    //     filename: 'command.pdf',
    //     image : { type: 'jpeg', quality : 0.98},
    //     html2canvas:  {scale : 2},
    //     jsPDF: {unit: 'in', format: 'letter', orientation: 'portrait'}
    // };

    // html2pdf().from(pdfToSend).set(opt).save();
})