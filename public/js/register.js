const btnRegister = document.getElementById("register");
const formRegister = document.getElementById("formRegister");

btnRegister.addEventListener("click", (e) => {
  e.preventDefault();
  const itUser = document.getElementById("user");
  const itPassword = document.getElementById("password");
  const itConfirmPassword = document.getElementById("confirm-password");
  const message = document.getElementById("message");

  let formData = new FormData(formRegister);
  let object = {};
  formData.forEach((value, key) => (object[key] = value));
  if (isEqualsPasswords(itPassword, itConfirmPassword)) {
    if (isValidPassword(itPassword)) {
      if (itUser.value !== "") {
        message.innerHTML = "";
        registerAccount(object);
      } else {
        message.innerHTML = "Asegúrese de ingresar un correo válido.";
      }
    } else {
      message.innerHTML =
        "Asegúrese de ingresar una contraseña con minúsculas, mayúsculas, números y que tenga al menos 8 carácteres de longitud.";
    }
  } else {
    message.innerHTML = "Las contraseñas deben ser iguales.";
  }
});

async function registerAccount(dataObject) {
  const response = await fetch("registerAccount", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify(dataObject),
  });
  const data = await response.json();
  if (data["status"] === "ok") {
    notify(data.result.message);
    if (data.result.message === "Registro exitoso.")
      window.location.href = "https://ecuadteam-pokeapi.herokuapp.com/";
  } else {
    notify("Verifique el formulario.");
  }
}

function isValidPassword(itPassword) {
  return (
    isValidLength(itPassword) &&
    validateLowercaseLetters(itPassword) &&
    validateCapitalLetters(itPassword) &&
    validateNumbers(itPassword)
  );
}

function isEqualsPasswords(itPassword, itConfirmPassword) {
  return itPassword.value === itConfirmPassword.value;
}

function isValidLength(itPassword) {
  return itPassword.value.length >= 8;
}

function validateLowercaseLetters(itPassword) {
  const lowerCaseLetters = /[a-z]/g;
  return itPassword.value.match(lowerCaseLetters);
}

function validateCapitalLetters(itPassword) {
  const upperCaseLetters = /[A-Z]/g;
  return itPassword.value.match(upperCaseLetters);
}

function validateNumbers(itPassword) {
  const numbers = /[0-9]/g;
  return itPassword.value.match(numbers);
}

function notify() {
  if (!("Notification" in window)) {
    alert("Tu navegador no soporta notificaciones.");
  } else if (Notification.permission === "granted") {
    let notification = new Notification("Registro satisfactorio.");
  } else if (Notification.permission !== "denied") {
    Notification.requestPermission().then(function (permission) {
      if (Notification.permission === "granted") {
        let notification = new Notification("Registro satisfactorio.");
      }
    });
  }
}
