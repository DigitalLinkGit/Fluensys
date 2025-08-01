import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    connect() {
            this.index = this.element.childElementCount
            const btn = document.createElement("button")
            btn.type = "button"
            btn.className = "btn btn-outline-primary mt-2"
            btn.innerText = "Ajouter un élément"
            btn.addEventListener("click", this.addElement)
            this.element.childNodes.forEach(this.addDeleteButton)
            this.element.append(btn)

    }

    addElement = (e) => {
        e.preventDefault()

        const element = document.createRange().createContextualFragment(
            this.element.dataset['prototype'].replaceAll("__name__", this.index)
        ).firstElementChild
        this.index++
        e.currentTarget.insertAdjacentElement('beforebegin',element)
        this.addDeleteButton(element)


    }

    addDeleteButton = (item) => {
        const btn = document.createElement("button")
        btn.type = "button"
        btn.className = "btn btn-outline-danger btn-sm mt-2"
        btn.innerText = "Supprimer"
        item.append(btn)
        btn.addEventListener("click", (e) => {
            e.preventDefault()
            item.remove()
        })
    }
}
