// validates the fields for the offers
function validateOffer() {
  const price = document.forms["offers"]["price"].value;

  if (!validatePrice(price)) {
    alert("Η τιμή πρέπει να είναι της μορφής 'Χ.ΧΧ' ");
    return false;
  }
  alert("Τα πεδία συμπληρώθηκαν σωστά");
  return true;
}

// checks that the price is in the format X.XX
function validatePrice(num) {
  // must have 1 digit before .
  if (String(num).split(".")[0]?.length != 1) {
    return false;
  }
  // must have 2 digits after .
  if (String(num).split(".")[1]?.length != 2) {
    return false;
  }
  return true;
}
