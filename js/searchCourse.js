/**
 * Projet : TeachFocus - Recherche de cours
 * @author : Alexandre Pintrand
 * @version : 1.0, 23/04/2021, Initial revision
**/

function searchCourse() {
    let input = document.getElementById('search');
    let filter = input.value.toLowerCase();
    let nodes = document.getElementsByClassName('name');
    let parentNodes = document.getElementsByClassName('col-sm-6 col-md-4 item')

    for (i = 0; i < nodes.length; i++) {
        if (nodes[i].innerText.toLowerCase().includes(filter)) {
            parentNodes[i].style.display = "block";
        } else {
            parentNodes[i].style.display = "none";
        } 
    }
}