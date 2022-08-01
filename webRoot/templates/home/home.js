let alertPlaceholder = $('#liveAlertPlaceholder');
const alert = (message, type) => {
    const wrapper = document.createElement('div')
    wrapper.innerHTML = [
        `<div class="alert alert-${type} alert-dismissible" role="alert">`,
        `   <div>${message}</div>`,
        '   <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>',
        '</div>'
    ].join('')

    alertPlaceholder.append(wrapper)
}

let GETparam = window.location.search.split("=")[1];

if (GETparam === "success") {
    console.log("Hey")
    alert("Aktion erfolgreich durchgeführt.", "success")

} else if (GETparam === "failed") {
    console.log("Ho")
    alert("Aktion fehlgeschlagen.", "danger")
}

function copy(){
    var content = document.getElementById("inviteCode");
    content.select();
    document.execCommand('copy');
}

async function setGroupSession(uuid){
    await fetch('http://localhost/api.php?setGroupSession=' + uuid);
}

