/**
 * @project : TeachFocus
 * @author : Alexandre PINTRAND
 * @version : 1.0, 07/07/2021, Initial revision
**/

let pagination = null;

function getElements() {
    pagination = document.querySelector('.pagination');
}

function generateButtons(nbButtons=6) {
    //let nbButtons = 6;

    pagination.innerHTML = `<li class="page-item" onclick="changePage(0)" id="btnPage_previous"><a class="page-link" aria-label="Previous"><span aria-hidden="true">«</span></a></li>`;
    pagination.innerHTML += `<li class="page-item btnPageSelected" onclick="changePage(1)" id="btnPage_1"><a class="page-link" >1</a></li>`;

    for (i = 2; i <= nbButtons; i++) {
        pagination.innerHTML +=  `<li class="page-item" onclick="changePage(${i})" id="btnPage_${i}"><a class="page-link">${i}</a></li>`;
    }

    pagination.innerHTML += `<li class="page-item" onclick="changePage(2)" id="btnPage_next"><a class="page-link" aria-label="Next"><span aria-hidden="true">»</span></a></li>`;
}