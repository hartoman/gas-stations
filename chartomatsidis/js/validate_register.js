// validates the whole registration procedure
function validateRegister() {
  const businessName = document.forms["businessData"]["bname"].value;
  const afm = document.forms["businessData"]["afm"].value;
  const businessAdress = document.forms["businessData"]["address"].value;
  const businessEmail = document.forms["businessData"]["email"].value;
  const userName = document.forms["businessData"]["username"].value;
  const pass1 = document.forms["businessData"]["password"].value;
  const pass2 = document.forms["businessData"]["confirm"].value;

  // elegxos onomatos epixeirhshs
  if (!businessName) {
    alert("Το όνομα δε μπορεί να είναι κενό");
    return false;
  }

  // elegxos afm
  if (!afm || isNaN(afm) || afm.length != 9) {
    alert("Το ΑΦΜ πρέπει να είναι αριθμός με 9 ψηφία");
    return false;
  }

  // elegxos diey8ynshs
  if (!businessAdress) {
    alert("Η διεύθυνση δε μπορεί να είναι κενή");
    return false;
  }

  //elegxos email
  if (!businessEmail || !checkEmail(businessEmail)) {
    alert("Μη-έγκυρο email");
    return false;
  }

  // elegxos username
  if (!userName || userName.length < 6) {
    alert("Το όνομα χρήστη πρέπει να είναι τουλάχιστον 6 χαρακτήρες");
    return false;
  }

  // elegxos an to password tairiazei me to confirm
  if (!(pass1 == pass2)) {
    alert("Ο κωδικός και η επιβεβαίωσή του δεν ταιριάζουν");
    return false;
  }

  alert("validation successful");
  return true;
}

// checks if password regex contains at least one number and at least one Capital letter
function checkPassword(str) {
  return /^(?=.*[0-9])(?=.*[A-ZΑ-Ω])/.test(str);
}

// checks email regex string
// credits: https://stackoverflow.com/questions/940577/javascript-regular-expression-email-validation
function checkEmail(str) {
  return /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/.test(str);
}

// reloads the registration page every time a new county is selected while sending back a variable name
let selectedjs;
function countyChange(val) {
  window.location.href = "register.php?selectedjs=" + val;
}
