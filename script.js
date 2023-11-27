
// Get all buttons with the common classname
let buttons = document.querySelectorAll('.commonClass');
let modal = document.querySelectorAll( '.myModal');
let spans = document.querySelectorAll(".close");
let newTodo = document.querySelector(".addNewTodo")
let textfield = document.querySelector('li');
let checkboxes = document.querySelectorAll("input[type=checkbox][name=settings]");
let boxes =  document.querySelectorAll('.checky > input');
let allTodos = document.querySelectorAll('.todoText');
let enabledSettings = []



// Add change event listener to each checkbox
boxes.forEach(function (checkbox) {
  checkbox.addEventListener('change', function () {

    let theBox = checkbox.getAttribute('data-button-id');
    console.log(theBox);
    let zeeText = 'data-text-id="' + theBox +'"';
    console.log(zeeText)
    let textPop = document.querySelector("div[data-text-id="+ CSS.escape(theBox) + "]");


    // Check if the checkbox is checked
    if (checkbox.checked) {
      // Apply line-through style to the text
            textPop.style.textDecoration = "line-through";
            console.log(textPop)


    } else {
      // Remove line-through style
      textPop.style.textDecoration = "none";
    }
  });
});


newTodo.addEventListener('click', function(){
    let formski = document.querySelector("form.todoForm");
    formski.style.display = "block";
    newTodo.style.display = "none";
    console.log('woad')

})


 buttons.forEach(function (button) {
        button.addEventListener('click', function () {
            let buttonId = button.getAttribute('data-button-id');
            console.log('Button with ID ' + buttonId + ' clicked.');
            let modalId = 'data-modal-id="' + buttonId +'"';
            console.log(modalId);
            let modalPop = document.querySelector("div[data-modal-id="+ CSS.escape(buttonId) + "]");
            modalPop.style.display = "block";
            console.log(modalPop)
           
          
        });
    });

     spans.forEach(function (span, event) {
        span.addEventListener('click', function () {
            let spanId = span.getAttribute('data-span-id');
            let closeX = 'data-span-id="' + spanId +'"';
            let getSpan =  document.querySelector("span[data-span-id="+ CSS.escape(spanId) + "]");
            console.log(spanId);
             console.log(closeX);
             console.log(getSpan);
                location.reload();

          
        });
    });

   
// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}

// Handle the submit button click
var submitBtn = document.getElementById("submitBtn");
submitBtn.onclick = function() {

    // Close the modal
    modal.style.display = "none";
}





