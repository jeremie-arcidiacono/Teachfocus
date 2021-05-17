function deleteCoursPopup(courseId, courseName) {
    if (confirm(`Etes-vous sur de vouloir supprimer le cours ${courseName} ? (Identifiant : ${courseId})`)) {
        window.location.href = "";
    }
}