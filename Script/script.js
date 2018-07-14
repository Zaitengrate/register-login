function init ()
{
    document.getElementById("create_form").disabled = false;
    document.getElementById("login_form").disabled = false;
}

function create_account()
{
    var login = document.getElementById('create_login').value;
    var password = document.getElementById('create_password').value;
    var conf_pass =  document.getElementById('create_conf_pass').value;
    var email = document.getElementById('create_email').value;
    var name = document.getElementById('create_name').value;

    var fd = new FormData();
    fd.append("create_login", login);
    fd.append("create_password", password);
    fd.append("conf_pass", conf_pass);
    fd.append("email", email);
    fd.append("name", name);

    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'ajax.php', true);
    xhr.onreadystatechange = function()
    {
        if (xhr.readyState === 4 && xhr.status === 200) {
            var response = xhr.responseText;
            console.log(response);
            var json = JSON.parse(response);
            console.log(json);
            document.getElementById('message').innerHTML = json.message;
        }
    };
    xhr.send(fd);

    return false;

}

function log_in()
{
    var login = document.getElementById('login').value;
    var password = document.getElementById('password').value;

    var fd = new FormData();
    fd.append("login", login);
    fd.append("password", password);

    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'ajax.php', true);
    xhr.onreadystatechange = function()
    {
        if (xhr.readyState === 4 && xhr.status === 200) {
            var response = xhr.responseText;
            var json = JSON.parse(response);
            if (json.message === 'Logged in') {
                document.getElementById('message').innerHTML = json.message;
                document.getElementById('greeting').innerHTML = "Hello " + json.name;
                var el = document.getElementById('forms');
                el.parentElement.removeChild(el);
            } else {
                document.getElementById('message').innerHTML = json.message;
            }

        }
    };
    xhr.send(fd);

    return false;
}