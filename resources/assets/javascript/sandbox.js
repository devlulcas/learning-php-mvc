let baseUrlApiCep = "https://ws.apicep.com/cep"; //47802-436.json
const regexCep = /[0-9]{5}-[0-9]{3}/;
const fieldCep = document.querySelector('[data-js="cepField"]');

fieldCep.addEventListener("blur", (event) => checkCep(event));

async function checkCep(event) {
  const cep = event.target.value;
  if (cep.length === 9) {
    if (cep.match(regexCep)) {
      const cepData = await callCepApi(cep);
      fillLocation(cepData);
    }
  }
}

async function callCepApi(cep) {
  const cepApiUrl = `${baseUrlApiCep}/${cep}.json`;
  const data = await fetch(cepApiUrl);
  const json = await data.json();
  return json;
}

function fillLocation(locationData) {
  const cityField = document.querySelector('[data-js="city"]');
  const stateField = document.querySelector('[data-js="state"]');
  const adressField = document.querySelector('[data-js="adress"]');
  const districtField = document.querySelector('[data-js="district"]');

  cityField.value = locationData.city;
  stateField.value = locationData.state;
  adressField.value = locationData.address;
  districtField.value = locationData.district;
}
