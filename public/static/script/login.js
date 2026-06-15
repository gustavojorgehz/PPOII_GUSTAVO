  // Simulação de banco de dados em memória
        let users = JSON.parse(localStorage.getItem('users')) || [];
        let currentUser = JSON.parse(localStorage.getItem('currentUser')) || null;

        // Inicialização
        document.addEventListener('DOMContentLoaded', () => {
            if (currentUser) {
                showUserArea(currentUser);
            } else {
                showForm('login');
            }
        });

        // Mostrar formulário específico
        function showForm(formType) {
            document.getElementById('loginForm').style.display = 'none';
            document.getElementById('registerForm').style.display = 'none';
            document.getElementById('userArea').style.display = 'none';

            if (formType === 'login') {
                document.getElementById('loginForm').style.display = 'block';
                document.getElementById('loginFormElement').reset();
                clearMessages();
            } else if (formType === 'register') {
                document.getElementById('registerForm').style.display = 'block';
                document.getElementById('registerFormElement').reset();
                clearMessages();
            }
        }

        // Mostrar área do usuário logado
        function showUserArea(user) {
            document.getElementById('loginForm').style.display = 'none';
            document.getElementById('registerForm').style.display = 'none';
            document.getElementById('userArea').style.display = 'block';
            document.getElementById('userNameDisplay').textContent = `Olá, ${user.name}!`;
        }

        // Limpar mensagens
        function clearMessages() {
            document.getElementById('loginMessage').style.display = 'none';
            document.getElementById('registerMessage').style.display = 'none';
        }

        // Mostrar mensagem
        function showMessage(elementId, message, type) {
            const element = document.getElementById(elementId);
            element.textContent = message;
            element.className = `message ${type}`;
            element.style.display = 'block';
            
            setTimeout(() => {
                element.style.display = 'none';
            }, 5000);
        }

        // Login
        document.getElementById('loginFormElement').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const email = document.getElementById('loginEmail').value;
            const password = document.getElementById('loginPassword').value;

            const user = users.find(u => u.email === email && u.password === password);

            if (user) {
                currentUser = user;
                localStorage.setItem('currentUser', JSON.stringify(user));
                showMessage('loginMessage', '✅ Login realizado com sucesso!', 'success');
                setTimeout(() => showUserArea(user), 1000);
            } else {
                showMessage('loginMessage', '❌ E-mail ou senha incorretos!', 'error');
            }
        });

        // Cadastro
        document.getElementById('registerFormElement').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const name = document.getElementById('registerName').value;
            const email = document.getElementById('registerEmail').value;
            const password = document.getElementById('registerPassword').value;
            const confirmPassword = document.getElementById('registerConfirmPassword').value;

            // Validações
            if (password.length < 6) {
                showMessage('registerMessage', '❌ A senha deve ter pelo menos 6 caracteres!', 'error');
                return;
            }

            if (password !== confirmPassword) {
                showMessage('registerMessage', '❌ As senhas não conferem!', 'error');
                return;
            }

            if (users.find(u => u.email === email)) {
                showMessage('registerMessage', '❌ Este e-mail já está cadastrado!', 'error');
                return;
            }

            // Criar novo usuário
            const newUser = {
                id: Date.now(),
                name: name,
                email: email,
                password: password,
                createdAt: new Date().toISOString()
            };

            users.push(newUser);
            localStorage.setItem('users', JSON.stringify(users));

            showMessage('registerMessage', '✅ Cadastro realizado com sucesso!', 'success');
            
            setTimeout(() => {
                document.getElementById('registerFormElement').reset();
                showForm('login');
                document.getElementById('loginEmail').value = email;
            }, 1500);
        });

        // Logout
        function logout() {
            currentUser = null;
            localStorage.removeItem('currentUser');
            document.getElementById('userArea').style.display = 'none';
            showForm('login');
        }