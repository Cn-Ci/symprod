$('#add-image').click(function(){
    // je recupère le numero des futurs champs que je vais créer
    const index = +$('#widgets-counter').val();
    // je recupère le prototype des entrées
    const tmpl = $('#add_images').data('prototype').replace(/__name__/g, index);
   // j'injecte ce code au sein de la div
   $('#add_images').append(tmpl);

   $('#widgets-counter').val(index + 1);
   // Je gère le btn supprimer
   handleDeleteButtons();
})

function handleDeleteButtons(){
    $('button[data-action="delete"]').click(function() {
        const target = this.dataset.target;

        $(target).remove();
    });
}

function updateCounter() {
    const count = +$('#add_images div.form-group').length;
    $('#widgets-counter').val(count)
}

updateCounter();
handleDeleteButtons();