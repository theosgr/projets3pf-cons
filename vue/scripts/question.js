window.addEventListener('load', () => {
  
  const show = document.querySelectorAll('.boutonQuestion');

show.forEach(
  function(currentValue){
    currentValue.addEventListener('click', () =>{
      currentValue.nextSibling.nextSibling.style.visibility = "visible";
      currentValue.nextSibling.nextSibling.nextSibling.nextSibling.style.visibility = "visible";
    });
  }
)

});
