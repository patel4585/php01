// Register a change validator on plus button field
let plus = document.getElementById("plus_1");
plus.addEventListener("click", plus_1);

// Register a change validator on minus button field
let minus = document.getElementById("minus_1");
minus.addEventListener("click", minus_1);

// Register a submit event on the joke detail form
let submit = document.getElementById("submit_JD");
submit.addEventListener("click", updateRating);