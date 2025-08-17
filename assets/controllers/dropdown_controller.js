import { Controller } from '@hotwired/stimulus'

export default class extends Controller {
    static targets = ['submenu']

    connect() {
        this.initMobileDropdowns()
        this.initDropdownCloseHandler()
    }

    initMobileDropdowns() {
        this.element.querySelectorAll('.dropdown-submenu > a').forEach(el => {
            el.addEventListener('click', e => {
                if (window.innerWidth < 768) {
                    e.preventDefault()
                    let submenu = el.nextElementSibling
                    submenu.classList.toggle('show')
                }
            })
        })
    }

    initDropdownCloseHandler() {
        this.element.querySelectorAll('.dropdown').forEach(dd => {
            dd.addEventListener('hidden.bs.dropdown', () => {
                dd.querySelectorAll('.dropdown-menu.show').forEach(menu => {
                    menu.classList.remove('show')
                })
            })
        })
    }
}
