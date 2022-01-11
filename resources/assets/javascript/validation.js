const testimonyForm = document.querySelector('[data-js="testimony-form"]');

testimonyForm.addEventListener("submit", validate(event));

function validate(event) {
    // Impede o formulário de ser enviado
    event.preventDefault();
    // Seleciona o elemento de nome e o elemento textarea de depoimento
    const inputName = testimonyForm.querySelector('[data-js="testimony-name"]');
    const inputTestimony = testimonyForm.querySelector('[data-js="testimony-content"]')


}
