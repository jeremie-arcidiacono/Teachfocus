function deleteCoursePopup(courseId, courseName) {
    if (confirm(`Etes-vous sur de vouloir supprimer le cours ${courseName} ? (Identifiant : ${courseId})`)) {
        window.location.href = "php/deleteCourse.php?id=" + courseId;
    }
}