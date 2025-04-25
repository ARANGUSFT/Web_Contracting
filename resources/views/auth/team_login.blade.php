<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<div class="login-container d-flex justify-content-center align-items-center min-vh-100">
    <div class="login-card p-4 shadow-lg">
        <div class="text-center">
            <img src="{{ asset('img/logo.png') }}" alt="Contracting Alliance Logo" class="logo">
            <h2 class="mt-3">Contracting Alliance</h2>
            <p class="text-muted">Login to your account</p>
        </div>

        <!-- 🔹 Mensajes de Error -->
        @if(session('error'))
            <div class="alert alert-danger text-center">{{ session('error') }}</div>
        @endif

        @if ($errors->has('email'))
            <div class="alert alert-danger">
                {{ $errors->first('email') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif


        <form method="POST" action="{{ route('team.login') }}" onsubmit="return validateForm()">
            @csrf
            <div class="mb-3">
                <label for="email" class="form-label"><i class="bi bi-envelope"></i> Email Address</label>
                <input type="email" name="email" id="email" class="form-control" required placeholder="Enter your email">
                <div class="invalid-feedback">Please enter a valid email.</div>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label"><i class="bi bi-key"></i> Password</label>
                <input type="password" name="password" id="password" class="form-control" required placeholder="Enter your password">
                <div class="invalid-feedback">Password must be at least 6 characters long.</div>
            </div>

            <button type="submit" class="btn btn-primary w-100"><i class="bi bi-box-arrow-in-right"></i> Login</button>
        </form>

        <!-- 🔹 Link de Recuperación -->
        <div class="text-center mt-3">
            <a href="#" class="text-decoration-none">Forgot Password?</a>
        </div>
    </div>
</div>

<!-- 🔹 JavaScript para Validación -->
<script>
    function validateForm() {
        let email = document.getElementById("email");
        let password = document.getElementById("password");
        let valid = true;

        // Validar Email
        if (!email.value.includes("@") || !email.value.includes(".")) {
            email.classList.add("is-invalid");
            valid = false;
        } else {
            email.classList.remove("is-invalid");
        }

        // Validar Contraseña (mínimo 6 caracteres)
        if (password.value.length < 6) {
            password.classList.add("is-invalid");
            valid = false;
        } else {
            password.classList.remove("is-invalid");
        }

        return valid;
    }
</script>

<!-- 🔹 Estilos Personalizados -->
<style>
    /* 📌 Fondo con degradado sutil */
    .login-container {
        background: linear-gradient(to right, #007bff, #117e9c);
        height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* 📌 Tarjeta de login con efectos de sombra y borde redondeado */
    .login-card {
        background: white;
        border-radius: 15px;
        padding: 40px;
        width: 100%;
        max-width: 400px;
        text-align: center;
        box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.2);
        animation: fadeIn 0.8s ease-in-out;
    }

    /* 📌 Animación de entrada */
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* 📌 Logo */
    .logo {
        width: 100px;
        margin-bottom: 10px;
    }

    /* 📌 Botón con efecto hover */
    .btn-primary {
        background-color: #007bff;
        border: none;
        transition: 0.3s ease-in-out;
    }

    .btn-primary:hover {
        background-color: #0056b3;
        transform: scale(1.05);
    }

    /* 📌 Inputs con bordes redondeados y sombras suaves */
    .form-control {
        border-radius: 8px;
        box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);
        transition: 0.2s ease-in-out;
    }

    .form-control:focus {
        border-color: #007bff;
        box-shadow: 0 0 8px rgba(0, 123, 255, 0.3);
    }

    /* 📌 Enlace de "Forgot Password" */
    .text-center a {
        color: #007bff;
        font-weight: 500;
        transition: 0.2s ease-in-out;
    }

    .text-center a:hover {
        text-decoration: underline;
        color: #0056b3;
    }
</style>

