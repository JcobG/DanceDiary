document.addEventListener("DOMContentLoaded", function () {
    console.log("DanceDiary Loaded");

    // Jeśli użytkownik jest zalogowany, przekieruj go do dashboardu
    if (localStorage.getItem("user")) {
        document.querySelector("#login-link").style.display = "none";
        document.querySelector("#dashboard-link").style.display = "block";
    }
});
