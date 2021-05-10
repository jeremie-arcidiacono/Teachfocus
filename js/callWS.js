/**
 * @project : TeachFocus
 * @author : Alexandre PINTRAND
 * @version : 1.0, 30/04/2021, Initial revision
**/

let URL = "";
var coursesInfo;

if (location.hostname == "dev.teachfocus.ch") {
    URL = "http://dev.teachfocus.ch/api";
}
else if (location.hostname == "teachfocus.ch") {
    URL = "https://teachfocus.ch/api";
}

function callWS_courses(page) {
    var path = window.location.pathname;
    var pageName = path.split("/").pop();

    var fullUrl = `${URL}/getCourses.php`;
    if (pageName == "index.php") {
        fullUrl = `${URL}/getCourses.php?limit=9&flag=bestClick`;
    }
    if (pageName == "cours.php") {
        fullUrl = `${URL}/getCourses.php?flag=all`;
        if (page != "" && page != undefined && page != null) {
            fullUrl += `&page=${page}`;
        }
        else {
            fullUrl += `&page=1`;
        }
    }

    $.ajax({
        type: "GET",
        url: fullUrl,
        success: function (data) {
            if (data["APIcode"] == 0) {
                if (data["courses"].length < 1) {
                    changePage(page - 1);
                }
                else {
                    displayAllCourses(data);
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
            alert("Error " + textStatus);
            console.log(jqXHR)
            console.log(errorThrown)
        }
    });
}

// Va chercher des info concernant les cours avec l'API
// Puis, les stock dans une vriable global pour plus tard
function callWS_refreshCoursesInfo() {
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

}

// Retourne le nombre de page maximum qu'on peut avoir
function getPaginationMaxPage() {
    if(typeof coursesInfo === "undefined"){
        callWS_refreshCoursesInfo();
    }
    return coursesInfo.NB_PAGES;
}

function displayAllCourses(dataCourses) {
    let courses = document.getElementById("courses");
    courses.innerHTML = ""; // Enleve les anciens cours afficher

    dataCourses.courses.forEach(dataCourse => {
        if (dataCourse["promoPrice"] !== null) {
            courses.innerHTML += `<div class="col-sm-6 col-md-4 item"><a href="viewCours.php?id=${dataCourse["idCourse"]}"><img class="img-fluid" src="assets/userMedia/imgCourseBanner/${dataCourse["codeBanner"]}"></a>
            <h3 class="name">${dataCourse["title"]}</h3>
            <p class="description">${dataCourse["shortDescription"]}</p>
            <a href="viewCours.php?id=${dataCourse["idCourse"]}"><p class="price">
            <span class=\"noPromoPrice\">${dataCourse["price"]}</span>
            <span class=\"currentPrice\">${dataCourse["promoPrice"]}</span></p><br>
            <button class="btn btn-outline-primary" value="VoirCours">Voir cours</button></a></div>`;
        }
        else if (dataCourse["price"] !== null) {
            courses.innerHTML += `<div class="col-sm-6 col-md-4 item"><a href="viewCours.php?id=${dataCourse["idCourse"]}"><img class="img-fluid" src="assets/userMedia/imgCourseBanner/${dataCourse["codeBanner"]}"></a>
            <h3 class="name">${dataCourse["title"]}</h3>
            <p class="description">${dataCourse["shortDescription"]}</p>
            <a href="viewCours.php?id=${dataCourse["idCourse"]}"><p class="price">
            <span class=\"currentPrice\">${dataCourse["price"]}</span></p><br>
            <button class="btn btn-outline-primary" value="VoirCours">Voir cours</button></a></div>`;
        }
        else {
            courses.innerHTML += `<div class="col-sm-6 col-md-4 item"><a href="viewCours.php?id=${dataCourse["idCourse"]}"><img class="img-fluid" src="assets/userMedia/imgCourseBanner/${dataCourse["codeBanner"]}"></a>
            <h3 class="name">${dataCourse["title"]}</h3>
            <p class="description">${dataCourse["shortDescription"]}</p>
            <a href="viewCours.php?id=${dataCourse["idCourse"]}"><p class="price">
            <span class=\"currentPrice\">Gratuit</span></p><br>
            <button class="btn btn-outline-primary" value="VoirCours">Voir cours</button></a></div>`;
        }
    });
}

function changePage(page) {
    const NAME_CLASS_SELECTED = "btnPageSelected";
    if (page != "" && page != undefined && page != null) {
        if (page > 6) {
            r
        }
        var lastPageButtonSelected = document.getElementsByClassName(NAME_CLASS_SELECTED)[0];
        lastPageButtonSelected.classList.remove(NAME_CLASS_SELECTED);

        document.getElementById("btnPage_" + page).classList.add(NAME_CLASS_SELECTED);

        document.getElementById("btnPage_next").setAttribute("onclick", "changePage(" + (page + 1) + ")");
        document.getElementById("btnPage_previous").setAttribute("onclick", "changePage(" + (page - 1) + ")");

        callWS_courses(page);
    }
}