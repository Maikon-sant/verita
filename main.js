(() => {
    'use strict'
    const forms = document.querySelectorAll('.needs-validation')
    Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
                event.preventDefault()
                event.stopPropagation()
            }
            form.classList.add('was-validated')
        }, false)
    })
})()

document.getElementById('toggleModo').addEventListener('click', function () {
    const html = document.documentElement;
    const icone = document.getElementById('iconeModo');
    const modoEscuro = html.getAttribute('data-bs-theme') === 'dark';

    const novoTema = modoEscuro ? 'light' : 'dark';
    html.setAttribute('data-bs-theme', novoTema);
    icone.textContent = modoEscuro ? '🌙' : '☀️';

    fetch('salvar_modo.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'modoEscuro=' + (novoTema === 'dark' ? '1' : '0')
    });
});
