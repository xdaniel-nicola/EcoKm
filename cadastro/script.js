document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById("form");
    const campos = document.querySelectorAll('.conteudo input, .conteudo select');
    const spans = document.querySelectorAll('.span-required');
    const selectElement = document.getElementById('sexo');
    const toggleThemeButton = document.getElementById('toggleTheme');
    const themeIcon = document.getElementById('themeIcon');

    const senhaRegex = /^[a-zA-Z0-9]{8,}$/;
    const loginRegex = /^[a-zA-Z]{6}$/;
    const nomeRegex = /^[A-Za-záàâãéèêíïóôõöúçñÁÀÂÃÉÈÍÏÓÔÕÖÚÇÑ ]{15,}$/;
    const emailRegex = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    const cpfRegex = /^[0-9]{3}\.[0-9]{3}\.[0-9]{3}-[0-9]{2}$/;
    const celular1Regex = /^\+55 \(\d{2}\)\d{5}-\d{4}$/;
    const celular2Regex = /^\+55 \(\d{2}\)\d{5}-\d{4}$/;
    const dataRegex = /^(0?[1-9]|[12][0-9]|3[01])\/(0?[1-9]|1[012])\/\d{4}$/;
    const nomeMaeRegex = /^[A-Za-záàâãéèêíïóôõöúçñÁÀÂÃÉÈÍÏÓÔÕÖÚÇÑ ]{15,}$/;
    const cepRegex = /^[0-9]{5}-[0-9]{3}/;

    // Adiciona o listener para alternar o tema
    toggleThemeButton.addEventListener('click', function(event) {
        event.preventDefault(); // Impede o comportamento padrão do botão
        document.body.classList.toggle('light'); // Alterna a classe 'light' no body

        // Alterna o ícone entre Lua e Sol
        if (document.body.classList.contains('light')) {
            themeIcon.textContent = '🌞'; // Sol
        } else {
            themeIcon.textContent = '🌙'; // Lua
        }
    });

    form.addEventListener('submit', function(event) {
        event.preventDefault(); // Evita o envio padrão do formulário
        validador(); // Função para validar os campos antes de enviar
    });

    function validador() {
        const isNameValid = nameValidate();
        const isEmailValid = emailValidate();
        const isCpfValid = cpfValidate();
        const isCelular1Valid = celular1Validate();
        const isCelular2Valid = celular2Validate();
        const isDateValid = dateValidate();
        const isNomeMaeValid = nomeMaeValidate();
        const isCepValid = cepValidate();
        const isEnderecoValid = enderecoValidate();
        const isBairroValid = bairroValidate();
        const isCidadeValid = cidadeValidate();
        const isLoginValid = loginValidate();
        const isMainPasswordValid = mainPasswordValidate();
        const isComparePasswordValid = comparePassword();
        const isSexoValid = sexoValidate();

        console.log("Nome válido: ", isNameValid)
        console.log("Email válido: ", isEmailValid);
        console.log("CPF válido: ", isCpfValid);
        console.log("Celular1 válido: ", isCelular1Valid);
        console.log("Celular2 válido: ", isCelular2Valid);
        console.log("Data válida: ", isDateValid);
        console.log("NomeMae válido: ", isNomeMaeValid);
        console.log("CEP válido: ", isCepValid);
        console.log("Endereço válido: ", isEnderecoValid);
        console.log("Bairro válido: ", isBairroValid);
        console.log("Cidade válida: ", isCidadeValid);
        console.log("Login válido: ", isLoginValid);
        console.log("MainPass válida: ", isMainPasswordValid);
        console.log("CompPass válida: ", isComparePasswordValid);
        console.log("Sexo válido: ", isSexoValid);

        if (isNameValid && isEmailValid && isCpfValid && isCelular1Valid && isCelular2Valid && isDateValid && isNomeMaeValid
            && isCepValid && isEnderecoValid && isBairroValid && isCidadeValid && isLoginValid && isMainPasswordValid && 
            isComparePasswordValid && isSexoValid) {
            form.submit(); // Envio do formulário se todos os campos forem válidos
            return true;
        } else {
            // alert('Por favor, preencha todos os campos corretamente.');
            return false;
        }
    }

    function setError(index) {
        campos[index].style.border = '2px solid #e63636';
        spans[index].style.display = 'block';
    }

    function removeError(index) {
        campos[index].style.border = '';
        spans[index].style.display = 'none';
    }

    campos[0].addEventListener('input', nameValidate);
    campos[1].addEventListener('input', emailValidate);
    campos[2].addEventListener('input', cpfValidate);
    campos[3].addEventListener('input', () => celularValidate(3));
    campos[5].addEventListener('input', () => celularValidate(5));
    campos[4].addEventListener('input', dateValidate);
    campos[6].addEventListener('input', cepValidate);
    campos[7].addEventListener('input', nomeMaeValidate);
    campos[8].addEventListener('input', enderecoValidate);
    campos[9].addEventListener('input', loginValidate);
    campos[10].addEventListener('input', cidadeValidate);
    campos[11].addEventListener('input', mainPasswordValidate);
    campos[12].addEventListener('input', bairroValidate);
    campos[13].addEventListener('input', comparePassword);
    campos[14].addEventListener('input', sexoValidate);
    
    function nameValidate() {
        if (!nomeRegex.test(campos[0].value.trim())) {
            setError(0);
            return false;
        } else {
            removeError(0);
            return true;
        }
    }

    function emailValidate() {
        if (!emailRegex.test(campos[1].value.trim())) {
            setError(1);
            return false;
        } else {
            removeError(1);
            return true;
        }
    }

    function cpfValidate() {
        const cpf = campos[2].value.trim();
        
        if (!cpfRegex.test(cpf)) {
            setError(2);
            return false;
        } else {
            const cpfNumbers = cpf.replace(/[^\d]+/g, '');
            
            if (!validateCpfDigits(cpfNumbers)) {
                setError(2);
                return false;
            }
    
            removeError(2);
            return true;
        }
    }
    
    function validateCpfDigits(cpf) {
        let sum = 0;
        for (let i = 0; i < 9; i++) {
            sum += parseInt(cpf.charAt(i)) * (10 - i);
        }
    
        let firstDigit = 11 - (sum % 11);
        if (firstDigit >= 10) firstDigit = 0;
    
        sum = 0;
        for (let i = 0; i < 10; i++) {
            sum += parseInt(cpf.charAt(i)) * (11 - i);
        }
    
        let secondDigit = 11 - (sum % 11);
        if (secondDigit >= 10) secondDigit = 0;
    
        return cpf.charAt(9) == firstDigit && cpf.charAt(10) == secondDigit;
    }

    function celular1Validate() {
        if (!celular1Regex.test(campos[3].value.trim())) {
            setError(3);
            return false;
        } else {
            removeError(3);
            return true;
        }
    }
    
    function dateValidate() {
        if (!dataRegex.test(campos[4].value.trim())) {
            setError(4);
            return false;
        } else {
            removeError(4);
            return true;
        }
    }

    function celular2Validate() {
        if (!celular2Regex.test(campos[5].value.trim())) {
            setError(5);
            return false;
        } else {
            removeError(5);
            return true;
        }
    }

    function cepValidate() {

        // const onlyNumbers = /^[0-9]+$/;
    if (!cepRegex.test(campos[6].value.trim())) {
        setError(6);
        return false;
    } else {
        removeError(6);
        return true;
    }
    }

    function nomeMaeValidate(){
        if (!nomeMaeRegex.test(campos[7].value.trim())){
            setError(7);
            return false;
        } else {
            removeError(7);
            return true;
        }
    }
    
    function enderecoValidate(){
        if (campos[8].value.trim().length < 3) {
            setError(8);
            return false;
        } else {
            removeError(8);
            return true;
        }
    }

    function loginValidate() {
        if (loginRegex.test(campos[9].value.trim())) {
            removeError(9);
            return true;
        } else {
            setError(9);
            return false;
        }
    }

    function cidadeValidate(){
        if (campos[10].value.trim().length < 2) {
            setError(10);
            return false;
        } else {
            removeError(10);
            return true;
        }
    }

    function mainPasswordValidate() {
        if (!senhaRegex.test(campos[11].value.trim())) {
            setError(11);
            return false;
        } else {
            removeError(11);
            return true;
        }
    }

    function bairroValidate(){
        if (campos[12].value.trim().length < 3) {
            setError(12);
            return false;
        } else {
            removeError(12);
            return true;
        }
    }
   
    function comparePassword() {
        const firstPassword = campos[11].value
        const secondPassword = campos[13].value
        console.log({
            firstPassword,
            secondPassword,
            equal: firstPassword === secondPassword
        })
        if (firstPassword == secondPassword && campos[13].value.length >= 8) {
            removeError(13);
            return true;
        }
        else {
            setError(13);
            return false;
        }
    }
    
    function sexoValidate() {
        const selectElement = document.getElementById('sexo');
        if (!selectElement) {
            console.error("Elemento 'sexo' não encontrado");
            return false;
        }
        if (selectElement.value === "0") {
            setError(14);
            return false;
        } else {
            removeError(14);
            return true;
        }
    }
})
cep.addEventListener('focusout', async () => {

    const response = await fetch (`https://viacep.com.br/ws/${cep.value}/json/`);
    
    if(!response.ok) {
        throw await response.json();
    }
     const responseCep = await response.json();
     endereco.value = responseCep.logradouro;
     bairro.value = responseCep.bairro;
     cidade.value = responseCep.localidade;
    }) 
// VALIDAÇÃO /\ //
// MASCARAS \/ //

cpf.addEventListener('keypress', () => {
    let cpflength = cpf.value.length

    if (cpflength === 3 || cpflength === 7) {
        cpf.value += '.'
    }else if (cpflength === 11) {
        cpf.value += '-'
    }
})

celular1.addEventListener('keypress', () => {
    let celular1length = celular1.value.length
    if (celular1length === 0){
        celular1.value += '+'
    }
    else if (celular1length === 3){
        celular1.value += ' ('
    }
    else if (celular1length === 7) {
        celular1.value += ')'
    }
    else if (celular1length === 13) {
        celular1.value += '-'
    }
})

celular2.addEventListener('keypress', () => {
    let celular2length = celular2.value.length
    if (celular2length === 0){
        celular2.value += '+'
    }
    else if (celular2length === 3){
        celular2.value += ' ('
    }
    else if (celular2length === 7) {
        celular2.value += ')'
    }
    else if (celular2length === 13) {
        celular2.value += '-'
    }
})


data_de_nascimento.addEventListener('keypress', () => {
    let data_de_nascimentolength = data_de_nascimento.value.length

    if (data_de_nascimentolength === 2 || data_de_nascimentolength === 5)
        data_de_nascimento.value += '/'
})

cep.addEventListener('keypress', () => {
    let ceplength = cep.value.length

    if (ceplength === 5)
        cep.value += '-'
})
