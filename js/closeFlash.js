"use strict"

const closeMessage = function () {

    const wrap = document.querySelector('.flash-wrap');
    if (!wrap) return;

    wrap.addEventListener('click', (e) => {
        let mainEl = e.target;
        if (!mainEl.classList.contains("flash__close")) return;

        document.body.removeChild(mainEl.parentElement.parentElement);
    })

}
closeMessage();