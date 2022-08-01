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
    console.log("HEy")
    alert("Aktion erfolgreich durchgeführt.", "success")

} else if (GETparam === "failed") {
    console.log("Ho")
    alert("Aktion fehlgeschlagen.", "danger")
}


// delay(3000).then(function () {
//     $('#alertSuccess').remove();
//     $('#alertFail').remove()
// });
//
// // remove success state from URL
// history.replaceState('', 'Übersicht', 'http://localhost/templates/home/home.php')
//
