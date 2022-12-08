document.getElementById('loginForm').addEventListener('submit', async (e) => {
    e.preventDefault();

    let response = await fetch(e.target.action, {
        method: e.target.method,
        body : new FormData(e.target),
    });

    if(response.ok) {
        let json = await response.json();
        console.log(json);
        localStorage.setItem('jwt', json.jwt);
        document.cookie = 'jwt='+json.jwt;
        window.location = "/";
    } else if(response.status == 400) {
        console.log(response);
        let json = await response.json();
        const error = document.getElementById('error');
        error.querySelector('b').innerText = json.error;
        error.style.display = "block";
        console.log(json);
    }
});
