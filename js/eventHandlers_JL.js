//////////////////////////////////////////////////////////////////////////////

//                   Event Handlers for Joke List Page

//////////////////////////////////////////////////////////////////////////////

setInterval(updateRating_JL, 20000);

function updateRating_JL(){
    let xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function(){
        if (xhr.readyState == 4 && xhr.status == 200){
            let stmt = JSON.parse(xhr.responseText);

            let i = 0;
            while(raw = stmt[i]){
                let joke_id = raw["joke_id"];
                let aRating = raw["AVG(rating_value)"];

                let temp = document.getElementById("aRating_" + joke_id);
                if(temp != null)
                    temp.innerText = Math.round(aRating * 100) / 100;
                i++;
            }
        }
    }

    xhr.open("GET", "ajax_backend_JL.php", true);
    xhr.send();
} 

setInterval(newJokeCheck, 90000);

function newJokeCheck(){
    let latest_joke_id = document.getElementById("jsVar").textContent;
    let xhr_2 = new XMLHttpRequest();
    xhr_2.onreadystatechange = function(){
        if (xhr_2.readyState == 4 && xhr_2.status == 200){
            let stmt_2 = JSON.parse(xhr_2.responseText);

            if(stmt_2 != null){
                location.reload();
            }
        }
    }

    xhr_2.open("GET", "ajax_backend_JL_2.php?jI=" + latest_joke_id, true);
    xhr_2.send();
}