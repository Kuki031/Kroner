"use strict"

const readFile = function() {

    const fileInput = document.querySelector('#file');
    if (!fileInput) return;

    fileInput.addEventListener('change', (e) => {
        const parseInput = e.target;
        const file = parseInput.files[0];
        
        if (file) {
            const reader = new FileReader();
            reader.onload = function(ev) {
                document.querySelector('.preview').style.backgroundImage = `url('${ev.target.result}')`;
            };
            reader.readAsDataURL(file);
        }
    });
}
readFile();