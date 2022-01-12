// QuerySelector seleciona um elemento utilizando seu id, nome, classe ou data-attribute
// Quando usar id coloque assim querySelector('#elemento')
// Quando usar nome coloque assim querySelector('elemento')
// Quando usar class coloque assim querySelector('.elemento')
// Quando usar data-attribute coloque assim querySelector('[data-js="elemento"]')

// Selecionando todos os inputs que tem required como propriedade
const inputFields = document.querySelectorAll("[required]");

// Para cada campo que tem um "required" adicionamos um evento para ver se ele é inválido
// neste caso o campo de email e senha
for (inputField of inputFields) {
  inputField.addEventListener("invalid", (event) => {
    // Eliminar o aviso padrão do navegador
    event.preventDefault();
    // Chama a nossa validação personalizada
    customValidation(event);
  });
  // Roda a verificação quando o usuário tira o mouse do input
  inputField.addEventListener("blur", customValidation);
}

// Validação personalizada / event é uma variável passada por padrão quando se usa um eventListener
function customValidation(event) {
  // O target do evento é um elemento html que está sofrendo o evento (neste caso o evento de ser inválido)
  const field = event.target;
  // Validation recebe o retorno de validateField, que por sua vez retorna uma função
  const validation = validateField(field);
  // Executo a variável ao por parênteses
  validation();
}

// Valida os campos de input baseado na propriedade validity (criado pelo próprio JS)
function validateField(field) {
  // logica para verificar se existem erros
  function verifyErrors() {
    // Verifica se um erro foi encontrado
    let foundError = false;
    // Itera sobre cada um dos campos de validity (badInput, customError, tooLong, tooShort)
    for (let error in field.validity) {
      // Se o campo valid for true a gente inverte e testa para dizer que foi encontrado um erro
      const fieldIsInvalid = !field.validity.valid;
      const errorExists = field.validity[error];
      // Se existe um erro e o campo é inválido então eu retorno o nome do erro encontrado
      if (errorExists && fieldIsInvalid) {
        foundError = error;
      }
    }
    return foundError;
  }

  // Retorna uma mensagem personalizada baseada no tipo de erro
  function customMessage(typeError) {
    // Mensagem específica para cada tipo de campo de input (email, text, password, number, etc)
    const messages = {
      text: {
        valueMissing: "Por favor, preencha este campo",
      },
      textarea: {
        valueMissing: "Por favor, preencha este campo",
      },
    };
    // Busca em mensagem para determinado tipo de input, um erro. Ex: messages["password"]["tooShort"]
    return messages[field.type][typeError];
  }

  // Bota a mensagem customizada no span com o atributo "data-js=error"
  function setCustomMessage(message) {
    // Procura o elemento span
    const spanError = field.parentNode.querySelector('span[data-js="error"]');
    /**
     * Se tiver uma mensagem de erro, aí adicionamos a classe que mostra
     * o span e alteramos o conteúdo para ser a mensagem de erro
     */
    if (message) {
      spanError.classList.add("active");
      spanError.innerHTML = message;
    } else {
      spanError.classList.remove("active");
      spanError.innerHTML = "";
    }
  }

  /**
   * Retorna uma função sem nome que testa se tem um erro e caso tenha um erro
   * então trocamos a cor da borda e rodamos uma função para colocar a mensagem
   * de erro no spam
   */
  return function () {
    const error = verifyErrors();
    if (error) {
      const message = customMessage(error);
      field.style.borderColor = "red";
      setCustomMessage(message);
    } else {
      field.style.borderColor = "green";
      setCustomMessage();
    }
  };
}
