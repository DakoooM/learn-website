document.addEventListener("DOMContentLoaded", () => {
    // Récupération des éléments
    const btn = document.querySelectorAll(".code");
    let copy = document.getElementById("bottom_notif");
    let antiSpam = false;

    notification = (type, element, length) => {
        switch(type) {
            case "success":
                element.style.background = "green";
                element.textContent = `${length} caractères copier dans le presse papiers !`;
                element.style.opacity = 1;
                antiSpam = true;
        
                setTimeout(() => {
                    element.style.opacity = 0;
                    antiSpam = false;
                }, 5000);
                break;
            case "failed":
                element.style.background_color = "red";
                element.textContent = "Le code n'a pas pu être copier dans le presse papiers !";
                element.style.opacity = 1;

                antiSpam = true;
        
                setTimeout(() => {
                    element.style.opacity = 0;
                    antiSpam = false;
                }, 5000);
                break;
            case "not":
                element.style.background = "#ad591d";
                element.textContent = "Veuillez patientez que cette notification ne soit plus afficher pour copier du code source a nouveau.";
                break;
            default:
        }
    }

    // Vérification si la page possède un ou plusieurs code(s) source
    if (btn && btn.length > 0){
        for (let i = 0; i < btn.length; i++){
        // Ajout d'un déclencheur d'événement sur chaque codes
            btn[i].onclick = () => {
                if (antiSpam == false){
                    navigator.clipboard.writeText(btn[i].innerText).then(() => {
                        notification("success", copy, btn[i].innerText.length);
                    }, () => {
                        notification("failed", copy, 0);
                    });
                } else {
                    notification("not", copy, 0);
                }
            }
        }
    }


    // Récupération des éléments concernant des réponses au commentaires
    const comment = document.querySelectorAll(".response-comments");

    if (comment && comment.length > 0){
        console.log("Hello man!");

        for (let i = 0; i < comment.length; i++){
            console.log("comments", comment[i].innerText);

            comment[i].addEventListener("click", (e) => {
                console.log("Ta cliquer mdr", e);

                let plusieursComments = [0, 1];

                

                plusieursComments.map((val, key) => {
                    if (val == i){
                        let comments = document.querySelector(`.comments-${val}-response`);

                        console.log("commentaire before", comments.innerHTML);
    
                        comments.innerHTML = `
                            <div class="modal-comments">
                                <span class="close-modal-comments">x</span>
                                <form method="post" class="modal-comments">
                                    <span class="modal-title">Répondre a DakoM</span>
                                    <textarea class="comment-text" name="comment_response" placeholder="Votre commentaire..." name="comment_text" cols="50" rows="10" required></textarea>
                                    <input type="submit" class="submit-comment" value="Répondre">
                                </form>
                            </div>
                        `;

                        let close = document.querySelectorAll(`.close-modal-comments`);
                        for (let i = 0; i < close.length; i++){
                            close[i].onclick = () => {
                                comments.innerHTML = "";
                            }
                        }
               
                        console.log("commentaire after", comments.innerHTML);
                    }
                });
            })            
        }
    }
})