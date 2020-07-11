
function filterFunction(event) {
  let value = event.target.value.trim();
  let list = event.target.parentElement.nextSibling.nextSibling.getElementsByTagName("li");
  for (let i = 0; i < list.length; i++) {
    let text = list[i].textContent || list[i].innerText;
    if (text.toLowerCase().indexOf(value.toLowerCase()) > -1 || value === '') {
      list[i].style.display = "";
    } else {
      list[i].style.display = "none";
    }
  }
}

function openCurrenciesList(event) {
  let list = event.target.parentElement.parentElement.getElementsByClassName('cpc-dropdown-content');//.currentNode.nextSibling;//.nextElementSibling;
  let styleDisplay = list[0].style.display;
  list[0].style.display = styleDisplay === "block" ? "none" : "block";
}