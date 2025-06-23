document.addEventListener('DOMContentLoaded', function() {
    console.log('JavaScript carregado');

    // Elementos principais
    const form = document.getElementById("form");
    const toggleSwitch = document.getElementById('toggle-switch');
    const body = document.body;

    // Regex
    const nomeRegex = /^[A-Za-záàâãéèêíïóôõöúçñÁÀÂÃÉÈÍÏÓÔÕÖÚÇÑ ]{15,}$/;
    const emailRegex = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    const cpfRegex = /^[0-9]{3}\.[0-9]{3}\.[0-9]{3}-[0-9]{2}$/;
    const celularRegex = /^\+55 \(\d{2}\)\d{5}-\d{4}$/;
    const dataRegex = /^(0?[1-9]|[12][0-9]|3[01])\/(0?[1-9]|1[012])\/\d{4}$/;
    const nomeMaeRegex = /^[A-Za-záàâãéèêíïóôõöúçñÁÀÂÃÉÈÍÏÓÔÕÖÚÇÑ ]{15,}$/;
    const cepRegex = /^[0-9]{5}-[0-9]{3}/;
    const loginRegex = /^[a-zA-Z]{6}$/;

    // Tema
    toggleSwitch.addEventListener('change', () => {
        body.classList.toggle('light-mode', toggleSwitch.checked);
        body.classList.toggle('dark-mode', !toggleSwitch.checked);
        localStorage.setItem('theme', toggleSwitch.checked ? 'light' : 'dark');
    });

    window.addEventListener('load', () => {
        const isLightMode = localStorage.getItem('theme') === 'light';
        toggleSwitch.checked = isLightMode;
        body.classList.add(isLightMode ? 'light-mode' : 'dark-mode');
    });

    // Formulário
    form.addEventListener('submit', function(event) {
        event.preventDefault();
        if (validador()) form.submit();
    });

    function setErrorById(id) {
        const campo = document.getElementById(id);
        const span = campo?.parentElement.querySelector('.span-required');
        if (campo) campo.style.border = '2px solid #e63636';
        if (span) span.style.display = 'block';
    }

    function removeErrorById(id) {
        const campo = document.getElementById(id);
        const span = campo?.parentElement.querySelector('.span-required');
        if (campo) campo.style.border = '';
        if (span) span.style.display = 'none';
    }

    function toggleRequirement(id, isValid) {
        const el = document.getElementById(id);
        if (!el) return;
        el.classList.toggle('valid', isValid);
        el.classList.toggle('invalid', !isValid);
    }

    // Validações individuais
    function nameValidate() {
        const campo = document.getElementById('nome');
        return validateWithRegex(campo, nomeRegex, 'nome');
    }

    function emailValidate() {
        const campo = document.getElementById('email');
        return validateWithRegex(campo, emailRegex, 'email');
    }

    function cpfValidate() {
        const campo = document.getElementById('cpf');
        const cpf = campo.value.trim();
        if (!cpfRegex.test(cpf) || !validateCpfDigits(cpf.replace(/\D/g, ''))) {
            setErrorById('cpf');
            return false;
        }
        removeErrorById('cpf');
        return true;
    }

    function validateCpfDigits(cpfNum) {
        let sum = 0;
        for (let i = 0; i < 9; i++) sum += parseInt(cpfNum[i]) * (10 - i);
        let firstDigit = 11 - (sum % 11);
        if (firstDigit >= 10) firstDigit = 0;

        sum = 0;
        for (let i = 0; i < 10; i++) sum += parseInt(cpfNum[i]) * (11 - i);
        let secondDigit = 11 - (sum % 11);
        if (secondDigit >= 10) secondDigit = 0;

        return cpfNum[9] == firstDigit && cpfNum[10] == secondDigit;
    }

    function celular1Validate() { return validateWithRegex(document.getElementById('celular1'), celularRegex, 'celular1'); }
    function celular2Validate() { return validateWithRegex(document.getElementById('celular2'), celularRegex, 'celular2'); }
    function dateValidate() { return validateWithRegex(document.getElementById('dt_nasc'), dataRegex, 'dt_nasc'); }
    function nomeMaeValidate() { return validateWithRegex(document.getElementById('nomeMae'), nomeMaeRegex, 'nomeMae'); }
    function cepValidate() { return validateWithRegex(document.getElementById('cep'), cepRegex, 'cep'); }

    function enderecoValidate() {
        return minLengthValidate('endereco', 3);
    }

    function bairroValidate() {
        return minLengthValidate('bairro', 3);
    }

    function cidadeValidate() {
        return minLengthValidate('cidade', 2);
    }

    function loginValidate() {
        const campo = document.getElementById('login');
        if (loginRegex.test(campo.value.trim())) {
            removeErrorById('login');
            return true;
        } else {
            setErrorById('login');
            return false;
        }
    }

    function mainPasswordValidate() {
        const campo = document.getElementById('senha');
        const senha = campo.value.trim();
        const checks = [
            { id: 'length', valid: senha.length >= 8 },
            { id: 'uppercase', valid: /[A-Z]/.test(senha) },
            { id: 'lowercase', valid: /[a-z]/.test(senha) },
            { id: 'number', valid: /\d/.test(senha) },
            { id: 'special', valid: /[\W_]/.test(senha) }
        ];
        checks.forEach(({ id, valid }) => toggleRequirement(id, valid));

        if (checks.every(c => c.valid)) {
            removeErrorById('senha');
            return true;
        } else {
            setErrorById('senha');
            return false;
        }
    }

    function comparePassword() {
        const senha = document.getElementById('senha').value.trim();
        const confirma = document.getElementById('confirmaSenha').value.trim();
        if (senha === confirma && confirma.length >= 8) {
            removeErrorById('confirmaSenha');
            return true;
        } else {
            setErrorById('confirmaSenha');
            return false;
        }
    }

    function sexoValidate() {
        const campo = document.getElementById('sexo');
        if (!campo || campo.value === "0") {
            setErrorById('sexo');
            return false;
        } else {
            removeErrorById('sexo');
            return true;
        }
    }

    function validateWithRegex(campo, regex, id) {
        if (!regex.test(campo.value.trim())) {
            setErrorById(id);
            return false;
        } else {
            removeErrorById(id);
            return true;
        }
    }

    function minLengthValidate(id, minLength) {
        const campo = document.getElementById(id);
        if (campo.value.trim().length < minLength) {
            setErrorById(id);
            return false;
        } else {
            removeErrorById(id);
            return true;
        }
    }

    function validador() {
        const validations = [
            nameValidate(),
            emailValidate(),
            cpfValidate(),
            celular1Validate(),
            celular2Validate(),
            dateValidate(),
            nomeMaeValidate(),
            cepValidate(),
            enderecoValidate(),
            bairroValidate(),
            cidadeValidate(),
            loginValidate(),
            mainPasswordValidate(),
            comparePassword(),
            sexoValidate()
        ];
        return validations.every(Boolean);
    }

    // Busca automática de endereço pelo CEP
    document.getElementById('cep').addEventListener('focusout', async () => {
        const cepField = document.getElementById('cep');
        try {
            const response = await fetch(`https://viacep.com.br/ws/${cepField.value}/json/`);
            if (!response.ok) throw new Error("Erro ao buscar o CEP");
            const data = await response.json();
            if (document.getElementById('endereco').value.trim() === "") document.getElementById('endereco').value = data.logradouro || "";
            if (document.getElementById('bairro').value.trim() === "") document.getElementById('bairro').value = data.bairro || "";
            if (document.getElementById('cidade').value.trim() === "") document.getElementById('cidade').value = data.localidade || "";
            addCommaToEndereco();
        } catch (error) {
            console.error("Erro ao buscar o CEP:", error);
        }
    });

    // Vírgula automática no campo endereço
    document.getElementById('endereco').addEventListener('focusout', addCommaToEndereco);

    function addCommaToEndereco() {
        const campo = document.getElementById('endereco');
        if (campo.value.trim() && !campo.value.includes(',')) {
            campo.value += ',';
        }
    }

    // Máscaras jQuery
    $(document).ready(function() {
        $("#cpf").inputmask("999.999.999-99");
        $("#celular1").inputmask("+55 (99)99999-9999");
        $("#celular2").inputmask("+55 (99)99999-9999");
        $("#dt_nasc").inputmask("99/99/9999");
        $("#cep").inputmask("99999-999");
        console.log('Máscaras aplicadas');
    });
});
