// Register a change validator on title input field
let title = document.getElementById("joke_title_input");
title.addEventListener("blur", titleHandler);
title.addEventListener("keyup", charCounter);

// Register a change validator on text-box input field
let text_box = document.getElementById("text_joke");
text_box.addEventListener("blur", text_boxHandler);