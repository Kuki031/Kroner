"use strict"

const run = function() {

    const inputs = Array.from(document.querySelectorAll('.card-input'));
    inputs.forEach(input => {

        input.addEventListener('change', (event) => {
            const mainEl = event.target;
            const inputVal = parseInt(mainEl.value);

            if (inputVal > 0) {
                const btn = mainEl.nextElementSibling;
                btn.classList.remove('disabled-cart');
                btn.removeAttribute('disabled');
            }
            else {
                const btn = mainEl.nextElementSibling;
                btn.classList.add('disabled-cart');
                btn.setAttribute('disabled', 'disabled');
            }
        })
    })
}
run();