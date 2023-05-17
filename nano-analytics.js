function getUUID() {
    const exdays = 30;
    const cookieName = 'uuid'; // Name of the cookie

    // Check if the cookie is already set
    if (document.cookie.indexOf(cookieName) === -1) {
        // If the cookie is not set, create a new cookie with the UUID
        let cvalue = crypto.randomUUID(); // Generate a UUID
        const d = new Date();
        d.setTime(d.getTime() + (exdays*24*60*60*1000));
        let expires = "expires="+ d.toUTCString();
        document.cookie = cookieName + "=" + cvalue + ";" + expires + ";path=/";
        return cvalue;
    }
    else {
        // Cookie is already set
        let cookies = document.cookie.split(';');

        // Find the UUID cookie in the list of cookies
        for (var i = 0; i < cookies.length; i++) {
            let cookie = cookies[i].trim();
            if (cookie.indexOf(cookieName + '=') === 0) {
                return cookie.substring(cookieName.length + 1);
            }
        }
    }
}

let request = new XMLHttpRequest();
request.open("GET", "/nano-analytics.php?uuid=" + getUUID() + "&path=" + window.location.pathname);
request.send();
