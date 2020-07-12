window.onload = function () {
  document.getElementsByClassName("cpc-converter-container")[0].style.display = "flex";
};

document.addEventListener('click', function (event) {
  let isCPCDropdownList = event.target.closest('.cpc-dropdown-content');
  let isCPCDropdownButton = event.target.closest('.cpc-dropdown-box');
  if (!isCPCDropdownList && !isCPCDropdownButton) {
    console.log(isCPCDropdownList);
    let dropdownElements = document.querySelectorAll('.cpc-dropdown-content');
    if (dropdownElements.length) {
      for (let i = 0; i < dropdownElements.length; i++) {
        dropdownElements[i].style.display = "none";
      }

    }
  }
});

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

function markAsActive(event) {
  let list = event.target.parentElement.getElementsByTagName('li');
  for (let i = 0; i < list.length; i++) {
    list[i].setAttribute('data-isactive', 0);
  }
  event.target.setAttribute('data-isactive', 1);
}

let waitForChangingInput;
function handleRateChange(event) {
  clearTimeout(waitForChangingInput);
  if (event.target.value && event.target.value.trim() !== '') {
    waitForChangingInput = setTimeout(() => {
      fetchRate(event);
    }, 5 * 100);
  }
}

function fetchRate(event, isInverse = false) {
  let form = document.getElementById('cpcConverterForm');
  let lists = form.getElementsByClassName('cpc-dropdown-content');
  for (let i = 0; i < lists.length; i++) {
    lists[i].style.display = "none";
  }

  let fromCurrency = form.querySelector("#cpcConverterInputFrom .cpc-dropdown-list li[data-isactive='1']").dataset.currencysymbol;
  let toCurrency = form.querySelector("#cpcConverterInputTo .cpc-dropdown-list li[data-isactive='1']").dataset.currencysymbol;
  let count = form.querySelector("#cpcConverterInputFrom .cpc-rate-value").value;

  makeRequest({
    'action': 'getRate',
    'fromCurrency': !isInverse ? fromCurrency : toCurrency,
    'toCurrency': !isInverse ? toCurrency : fromCurrency,
    'count': count
  }).then((result) => {
    let list = form.querySelectorAll('.cpc-dropdown-content li');
    for (let i = 0; i < list.length; i++) {
      list[i].setAttribute('data-isactive', 0);
    }
    form.querySelector("#cpcConverterInputFrom .cpc-dropdown-list li[data-currencysymbol='" + result.from + "']").setAttribute('data-isactive', 1);
    form.querySelector("#cpcConverterInputFrom .cpc-rate-value").value = result.count;
    form.querySelector("#cpcConverterInputFrom .cpc-dropdown-button span").textContent = result.from;
    form.querySelector("#cpcConverterInputTo .cpc-dropdown-list li[data-currencysymbol='" + result.to + "']").setAttribute('data-isactive', 1);
    form.querySelector("#cpcConverterInputTo .cpc-rate-value").value = result.rate;
    form.querySelector("#cpcConverterInputTo .cpc-dropdown-button span").textContent = result.to;
  });
}


function makeRequest(data) {
  return new Promise((resolve, reject) => {
    let xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        let response = JSON.parse(this.responseText);
        resolve(response.data);
      }
    };
    xhttp.open("POST", cpc_object.cpc_ajax_url, true);
    xhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    let out = encodeURIData(data);
    xhttp.send(out);
  })

}


function encodeURIData(object) {
  let out = [];

  for (let key in object) {
    if (object.hasOwnProperty(key)) {
      out.push(key + '=' + encodeURIComponent(object[key]));
    }
  }
  return out.join('&');
}
