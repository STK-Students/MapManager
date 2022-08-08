// Toast notification are triggered based on URL GET params
const successToast = document.getElementById('successToast')
const failureToast = document.getElementById('failureToast')

const urlParams = new URLSearchParams(window.location.search)
if (urlParams.has("result")) {
    const result = urlParams.get("result")
    toast = result === "success" ? new bootstrap.Toast(successToast) : new bootstrap.Toast(failureToast);
    toast.show()
}


function copy() {
    let content = document.getElementById("inviteCode");
    content.select();
    document.execCommand('copy');
}

async function setGroupSession(uuid) {
    await fetch('http://localhost/api.php?setGroupSession=' + uuid);
}
