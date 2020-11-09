

var $lieu = $('#sortie_lieu');
//When 'lieu' gets selected...
$lieu.change(function ()
{
    //...retrieve the corresponding form
    var $form = $(this).closest('form');
    
    //simulate form data, but only include the selected 'lieu' value.
    var data = {};
    data[$lieu.attr('name')] = $lieu.val();
    
    //submit data via AJAX to the form's action path
    $.ajax({
        url: $form.attr('action'),
        type: "POST",
        data: data,
        success: function (html)
        {
            //Replace current 'rue' field...
            $('#sortie_rue').replaceWith(
                //...with the returned one from the AJAX response
                $(html).find('#sortie_rue')
            );
            //Rue field now displays the appropriate 'rue'.
            
            
            //Do the same for 'latitude' and 'longitude'
            $('#sortie_latitude').replaceWith(
                $(html).find('#sortie_latitude')
            );
            
            $('#sortie_longitude').replaceWith(
                $(html).find('#sortie_longitude')
            );
        }
    });
});

//--------------------------------------------------------

//get the modal
var modal = document.getElementById('modalForm');

//get the button that opens the modal
var btnOpen = document.getElementById('btn-modal');

//get the span element that closes the modal
var span = document.getElementById('spanClose');

//get the button that close the modal and reset the form
var btnClose = document.getElementById('btnClose');

//when the user clicks the btnOpen, open the modal
btnOpen.onclick = function (){
    modal.style.display = "block";
}

//when the user clicks on span (x), close the modal
span.onclick = function (){
    modal.style.display = "none";
}

//when the user clicks on btnClose, close the modal too
btnClose.onclick = function (){
    modal.style.display = "none";
    
}

//when the user clicks anywhere outside of the modal, close it
window.onclick = function (event){
    if(event.target === modal){
        modal.style.display = "none";
    }
}
