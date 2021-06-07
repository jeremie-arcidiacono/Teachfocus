/**
 * @project : TeachFocus
 * @author : Alexandre PINTRAND
 * @version : 1.0, 30/04/2021, Initial revision
**/

let URL = "";
var coursesInfo;

var filterPrice = null;
var filterDifficulty = null;
var filterLang = null;
var filterSearch = null

// Récuperation des filtres de cours
function priceChanged(){
    let select = document.getElementById("inputSelectPrice");
    filterPrice = select.value;

    callWS_courses()
}
function langChanged(){
    let select = document.getElementById("inputSelectLanguages");
    filterLang = select.value;

    callWS_courses()
}
function difficultyChanged(){
    let select = document.getElementById("inputSelectDifficulty");
    filterDifficulty = select.value;

    callWS_courses()
}
function searchChanged(){
    let input = document.getElementById("search");
    filterSearch = input.value;

    callWS_courses()
}

function resetFilter(){
    filterPrice = null;
    filterDifficulty = null;
    filterLang = null;
    filterSearch = null;

    document.getElementById("inputSelectPrice").selectedIndex=0;
    document.getElementById("inputSelectLanguages").selectedIndex=0;
    document.getElementById("inputSelectDifficulty").selectedIndex=0;
    document.getElementById("search").value = "";

    let arrCheckbox = document.querySelectorAll('input[type="checkbox"]')
    for (let i = 0; i < arrCheckbox.length; i++) {
        arrCheckbox[i].checked = false;
    }

    callWS_courses()
}


if (location.hostname == "dev.teachfocus.ch") {
    URL = "http://dev.teachfocus.ch/api";
}
else if (location.hostname == "teachfocus.ch") {
    URL = "https://teachfocus.ch/api";
}

var lstPageWithPagination = ["cours.php"]; // array de toutes les page néccéssitant un système de pagination

function callWS_courses(page) {
    var path = window.location.pathname;
    var pageName = path.split("/").pop();

    var fullUrl = `${URL}/getCourses.php`;
    if (lstPageWithPagination.indexOf(pageName) == -1) {
        // La page n'a pas besoin de pagination
        fullUrl = `${URL}/getCourses.php?limit=9&flag=bestClick`;
    }
    else{
        fullUrl = `${URL}/getCourses.php?flag=all`;
        if (!isNullOrEmpty(page)) {
            fullUrl += `&page=${page}`;
        }
        else {
            fullUrl += `&page=1`;
        }
        
        if (!isNullOrEmpty(filterSearch)) {
            fullUrl += `&s=${filterSearch}`;
        }
    }

    if (!isNullOrEmpty(filterPrice)) {
        fullUrl += "&fPrice=" + filterPrice;
    }
    if (!isNullOrEmpty(filterLang)) {
        fullUrl += "&fLang=" + filterLang;
    }
    if (!isNullOrEmpty(filterDifficulty)) {
        fullUrl += "&fDifficulty=" + filterDifficulty;
    }

    $.ajax({
        type: "GET",
        url: fullUrl,
        success: function (data) {
            if (data["APIcode"] == 0) {
                coursesInfo = data["info"];
                if (coursesInfo.NB_ALL_COURSES <= 0) {
                    document.getElementById("courses").innerHTML = "Aucun cours à afficher";
                }
                else{
                    var path = window.location.pathname;
                    var pageName = path.split("/").pop();

                    if (lstPageWithPagination.indexOf(pageName) != -1) {
                        let numberCurrentPage = document.getElementById("paginationButtonsContainers").childElementCount -2; // Nombre de page a afficher actuelle, avant de modifier ce nombre.  -2 car les btn suivant et précédent ne doive pas etre compter
                        if (numberCurrentPage != coursesInfo.NB_PAGES) {
                            generateButtons(coursesInfo.NB_PAGES); // Regénere les boutons de selection de page, dans le cas ou les filtres/recherche change
                        }
                    }
                    

                    // if (coursesInfo.NB_PAGES < page) {
                    //     changePage(page - 1);
                    // }
                    // else {
                        displayAllCourses(data);
                    // }
                }   
            }
            else if (data["APIcode"] == 21) {
                document.getElementById("courses").innerHTML = "Le service est actuellement en maintenance .<br>Merci de reessayer plus tard.";
            }
            else {
                document.getElementById("courses").innerHTML = "Une erreur interne est survenu. <br>Merci de reessayer plus tard.";
            }

        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert("Erreur général.\r\nReessayer plus tard");
            console.log(jqXHR)
            console.log(errorThrown)
        }
    });
}

// Va chercher des info concernant les cours avec l'API
// Puis, les stock dans une vriable global pour plus tard
/*function callWS_refreshCoursesInfo() {
        var fullUrl = `${URL}/getCoursesStatistics.php`;
        $.ajax({
            type: "GET",
            url: fullUrl,
            success: function (data) {
                if (data["APIcode"] == 0) {
                    coursesInfo = data["info"];
                    return coursesInfo;
                }
                else if (data["APIcode"] == 21) {
                    document.getElementById("courses").innerHTML = "Le service est actuellement en maintenance .<br>Merci de reessayer plus tard.";
                }
                else {
                    document.getElementById("courses").innerHTML = "Une erreur interne est survenu. <br>Merci de reessayer plus tard.";
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert("Error " + textStatus);
                console.log(jqXHR)
                console.log(errorThrown)
            }
    });

}*/

// Retourne le nombre de page maximum qu'on peut avoir
/*function getPaginationMaxPage() {
    if(typeof coursesInfo === "undefined"){
        callWS_refreshCoursesInfo();
    }
    return coursesInfo.NB_PAGES;
}*/

function displayAllCourses(dataCourses) {
    let courses = document.getElementById("courses");
    courses.innerHTML = ""; // Enleve les anciens cours afficher

    dataCourses.courses.forEach(dataCourse => {
        if (dataCourse["promoPrice"] !== null) {
            courses.innerHTML += `<div class="col-sm-6 col-md-4 item"><a href="viewCours.php?id=${dataCourse["idCourse"]}"><img class="img-fluid" src="assets/userMedia/imgCourseBanner/${dataCourse["codeBanner"]}"></a>
            <h3 class="name">${dataCourse["title"]}</h3>
            <p class="description">${dataCourse["shortDescription"]}</p>
            <p class="price">
            <span class=\"noPromoPrice\">${dataCourse["price"]}</span>
            <span class=\"currentPrice\">${dataCourse["promoPrice"]}</span></p><br>
            <a href="viewCours.php?id=${dataCourse["idCourse"]}">
            <button class="btn btn-outline-primary" value="VoirCours">Voir cours</button></a></div>`;
        }
        else if (dataCourse["price"] !== null) {
            courses.innerHTML += `<div class="col-sm-6 col-md-4 item"><a href="viewCours.php?id=${dataCourse["idCourse"]}"><img class="img-fluid" src="assets/userMedia/imgCourseBanner/${dataCourse["codeBanner"]}"></a>
            <h3 class="name">${dataCourse["title"]}</h3>
            <p class="description">${dataCourse["shortDescription"]}</p>
            <p class="price">
            <span class=\"currentPrice\">${dataCourse["price"]}</span></p><br>
            <a href="viewCours.php?id=${dataCourse["idCourse"]}">
            <button class="btn btn-outline-primary" value="VoirCours">Voir cours</button></a></div>`;
        }
        else {
            courses.innerHTML += `<div class="col-sm-6 col-md-4 item"><a href="viewCours.php?id=${dataCourse["idCourse"]}"><img class="img-fluid" src="assets/userMedia/imgCourseBanner/${dataCourse["codeBanner"]}"></a>
            <h3 class="name">${dataCourse["title"]}</h3>
            <p class="description">${dataCourse["shortDescription"]}</p>
            <p class="price">
            <span class=\"currentPrice\">Gratuit</span></p><br>
            <a href="viewCours.php?id=${dataCourse["idCourse"]}">
            <button class="btn btn-outline-primary" value="VoirCours">Voir cours</button></a></div>`;
        }
    });
}

function changePage(page) {
    const NAME_CLASS_SELECTED = "btnPageSelected";
    if (page != "" && page != undefined && page != null && page != 0) {
        if (page > coursesInfo.NB_PAGES) {
            return false;
        }
        var lastPageButtonSelected = document.getElementsByClassName(NAME_CLASS_SELECTED)[0];
        lastPageButtonSelected.classList.remove(NAME_CLASS_SELECTED);

        document.getElementById("btnPage_" + page).classList.add(NAME_CLASS_SELECTED);

        document.getElementById("btnPage_next").setAttribute("onclick", "changePage(" + (page + 1) + ")");
        document.getElementById("btnPage_previous").setAttribute("onclick", "changePage(" + (page - 1) + ")");

        callWS_courses(page, document.getElementById("search").value);
    }
}