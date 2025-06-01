function checkSession() {
    const xhr = new XMLHttpRequest();
    xhr.open("GET", "check-session.php?t=" + new Date().getTime(), true); // Prevent caching
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            try {
                const response = JSON.parse(xhr.responseText);
                if (response.status === "expired") {
                    // Redirect to the login page if the session has expired
                    alert("Your session has expired. You will be redirected to the login page.");
                    window.location.href = "login.php?message=Session expired. Please log in again.";
                }
            } catch (e) {
                console.error('Failed to parse JSON:', e);
            }
        }
    };
    xhr.send();
}

checkSession();

// Check the session every minute
setInterval(checkSession, 60000);
