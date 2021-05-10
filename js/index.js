function ValidateSignInForm() {
    let email = document.sign_in_form.email.value;
    let password = document.sign_in_form.mdp.value;

    if (email.length == "" && password.length == "") {
        alert("Le formulaire de connexion est incomplet.");
        window.location.replace("sign-in.php");
        return false;
    } else {
        if (email.length == "" || password.length == "") {
            alert("Certains champs sont incomplets.");
            window.location.replace("sign-in.php");
            return false;
        }
    }
}

function ValidateSignUpForm() {
    let username = document.sign_up_form.pseudo.value;
    let lastName = document.sign_up_form.nom.value;
    let firstName = document.sign_up_form.prenom.value;
    let email = document.sign_up_form.email.value;
    let password = document.sign_up_form.mdp.value;
    let choice = document.sign_up_form.choix.value;

    if (username.length == "" && lastName.length == "" && firstName.length == "" && email.length == "" && password.length == "" && choice.length == "") {
        alert("Le formulaire d'inscription est incomplet.");
        return false;
    } else {
        if (username.length == "" || lastName.length == "" || firstName.length == "" || email.length == "" || password.length == "" || choice.length == "") {
            alert("Certains champs sont incomplets.");
            return false;
        }
    }
}

function resetSignUpForm() {
    let confirmMessage = confirm("Voulez-vous vraiment effacer le formulaire?");

    if (confirmMessage) {
        document.getElementById("pseudo").value = "";
        document.getElementById("nom").value = "";
        document.getElementById("prenom").value = "";
        document.getElementById("email").value = "";
        document.getElementById("mdp").value = "";
        document.getElementById("choix").value = "";
    }
}

function ValidateCreateCoursForm() {
    let NameCours = document.create_cours_form.courseName.value;
    let PrerequisiteCours = document.create_cours_form.prerequis.value;
    let descriptionCours = document.create_cours_form.description.value;
    let ShortDescriptionCours = document.create_cours_form.shortDescription.value;

    if (NameCours.length == "" && PrerequisiteCours.length == "" && descriptionCours.length == "" && ShortDescriptionCours.length == "") {
        alert("Le formulaire d'inscription est incomplet.");
        return false;
    } else {
        if (NameCours.length == "" || PrerequisiteCours.length == "" || descriptionCours.length == "" || ShortDescriptionCours.length == "") {
            alert("Certains champs sont incomplets.");
            return false;
        }
    }
}

function onlyAcceptDecimalNumbers(text, event) {
    let char = (event.which) ? event.which : event.keyCode;
    if (char == 46) {
      if (text.value.indexOf('.') === -1) {
        return true;
      } else {
        return false;
      }
    } else {
      if (char > 31 &&
        (char < 48 || char > 57))
        return false;
    }
    return true;
}