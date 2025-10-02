import { Controller } from "@hotwired/stimulus";

export default class extends Controller {


    connect() {
        this.index = this.element.childElementCount
        const btn = document.createElement('button')
        btn.setAttribute('class','btn btn-outline-primary')
        btn.innerText = 'ajouter'
        btn.setAttribute('type', 'button')
        btn.addEventListener('click', this.addElement.bind(this))

        this.element.querySelectorAll('fieldset').forEach((fs) => {
            const row = fs.querySelector('.row') || fs
            this.addDeleteButton(row)
        })
        this.element.append(btn)
    }

    addElement(e) {
        console.log("CLIC ADD");
        e.preventDefault();
        const element = document.createRange().createContextualFragment(
            this.element.dataset['prototype'].replaceAll('__name__', this.index)
        ).firstElementChild
        this.index++
        e.currentTarget.insertAdjacentElement('beforebegin', element)

        const row = element.querySelector('.row') || element
        this.addDeleteButton(row)

    }


    addDeleteButton(row) {
        const col = document.createElement('div')
        col.className = 'col-auto d-flex align-items-end'

        const btn = document.createElement('button')
        btn.setAttribute('class','btn btn-icon btn-outline-primary')
        btn.setAttribute('type','button')
        const icon = document.createElement('i')
        icon.className = 'bi bi-trash'

        btn.addEventListener('click', this.remove.bind(this))
        btn.append(icon)
        col.append(btn)
        row.append(col)
    }



    remove(event) {
        event.preventDefault()
        const fs = event.currentTarget.closest('fieldset')
        if (fs) fs.remove()
    }

}
