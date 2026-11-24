(() => {
    const loginForm = document.querySelector('#loginForm');
    const registerForm = document.querySelector('#registerForm');

    if (!loginForm || !registerForm) {
        return;
    }

    window.showForm = (formName) => {
        const forms = { login: loginForm, register: registerForm };
        Object.values(forms).forEach((form) => form.classList.add('d-none'));
        forms[formName]?.classList.remove('d-none');
    };
})();
