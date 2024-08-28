import "./bootstrap";

// import Alpine from "alpinejs";
// import Alpine from "alpinejs";
import Clipboard from "@ryangjchandler/alpine-clipboard";

// window.Alpine = Alpine

// Alpine.start()

Alpine.plugin(Clipboard.configure({
    onCopy: () => {
        const alertDiv = document.querySelector('.alert-div')
        alertDiv.classList.toggle('hidden')
        alertDiv.classList.add('block')

        setTimeout(() => {
            alertDiv.classList.toggle('block')
            alertDiv.classList.add('hidden')
        }, 3000)
    }
}))

// window.Alpine = Alpine
// window.Alpine.start()
