var passengerButton = document.getElementById('passengerButton');
var passengerModal = document.querySelector(".modal-content"); 
var closeButton = document.querySelector(".close");
    
passengerButton.addEventListener('click', (e) => {
    e.preventDefault();
    e.stopPropagation();
    passengerModal.style.display = "block";
});
closeButton.addEventListener('click', (e) => {
    e.preventDefault();
    e.stopPropagation();
    passengerModal.style.display = "none";
});
window.addEventListener('click', (e) => {
    e.stopPropagation();
    if (e.target == passengerModal) {
        passengerModal.style.display = "none";
    }
});

var print_button = document.querySelector('#print-button');
var ticket = document.querySelector('.ticket-download');

print_button.addEventListener('click',(e)=>{
    e.stopPropagation();
    ticket.print();
})


var selection = document.querySelectorAll('.selection input[type=\'number\']');
console.log(selection);