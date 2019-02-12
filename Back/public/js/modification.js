
// On recupère chaque element par son id
var input = document.getElementById('js-input');
var plus = document.getElementById('js-plus');
var minus = document.getElementById('js-minus');
var valid = document.getElementById('js-valid');
var newStock = document.getElementById('js-stock');
var inventory = document.getElementById('js-Inventaire')
var picking = document.getElementById('js-Prélèvement')
var order = document.getElementById('js-Commande')
var modification = 1

// On dit que quand on clique il doit ajouter +1 à la valeur de l'input 
plus.addEventListener('click', function (evt) {
    evt.preventDefault();
    input.value ++;
})
// On dit que quand on clique il doit retirer -1 à la valeur de l'input 
minus.addEventListener('click', function (evt) {
    evt.preventDefault();
    input.value--;
})

inventory.addEventListener('click',function(evt){

    modification = inventory.dataset.inventaire;
})

picking.addEventListener('click',function(evt){

    modification = picking.dataset.prélèvement;
})

order.addEventListener('click',function(evt){

    modification = order.dataset.commande;
})

// Quand on clique il éxecute cette fonction onClickBtn
valid.addEventListener('click', onClickBtn);

function onClickBtn(evt) {
    evt.preventDefault();
    var user = valid.dataset.user; // id de l'utilisateur passé par twig
    var product =  valid.dataset.product; // id du produit 
   
axios.post('http://92.243.8.234/Back/public/api/history/add', {
    user: user,
    modification: modification,
    variation: input.value,
    product: product

}).then(function (response) {
   // recuperation de la clé stock dans la réponse de l'api
    var stock = response.data.stock;
    newStock.textContent = stock;
    input.value = 0;

}).catch(function (error) {
    console.log(error);
});
}