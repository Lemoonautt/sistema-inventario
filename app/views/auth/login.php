<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - <?= APP_NAME ?></title>
    <link rel="stylesheet" href="<?= APP_URL ?>/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-container {
            width: 100%;
            max-width: 440px;
        }

        .login-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
        }

        .login-header {
            background: white;
            padding: 40px 40px 30px;
            text-align: center;
        }

        .login-logo {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            box-shadow: 0 8px 20px rgba(14, 165, 233, 0.3);
        }

        .login-logo i {
            font-size: 36px;
            color: white;
        }

        .login-title {
            font-size: 28px;
            font-weight: 700;
            color: var(--dark-text);
            margin: 0 0 8px;
            letter-spacing: -0.5px;
        }

        .login-subtitle {
            color: var(--text-light);
            font-size: 15px;
            margin: 0;
        }

        .login-body {
            padding: 0 40px 40px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            color: var(--dark-text);
            margin-bottom: 8px;
            font-size: 14px;
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper i {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-light);
            font-size: 16px;
        }

        .form-control {
            width: 100%;
            padding: 14px 16px 14px 48px;
            border: 2px solid var(--border);
            border-radius: 10px;
            font-size: 15px;
            transition: all 0.2s;
            font-family: inherit;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(14, 165, 233, 0.1);
        }

        .form-control::placeholder {
            color: var(--text-light);
        }

        .btn-login {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 8px;
            box-shadow: 0 4px 12px rgba(14, 165, 233, 0.3);
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(14, 165, 233, 0.4);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .login-footer {
            text-align: center;
            padding: 20px;
            background: var(--light);
            color: var(--text-light);
            font-size: 13px;
        }

        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 14px;
            animation: slideDown 0.3s;
        }

        .alert-success {
            background: #d1fae5;
            color: #065f46;
            border-left: 4px solid #10b981;
        }

        .alert-danger {
            background: #fee2e2;
            color: #991b1b;
            border-left: 4px solid #ef4444;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .demo-credentials {
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            border-radius: 8px;
            padding: 16px;
            margin-bottom: 20px;
        }

        .demo-credentials h4 {
            color: var(--primary);
            font-size: 13px;
            margin: 0 0 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .demo-credentials p {
            margin: 4px 0;
            font-size: 13px;
            color: var(--text);
        }

        .demo-credentials code {
            background: white;
            padding: 2px 6px;
            border-radius: 4px;
            font-family: monospace;
            color: var(--primary);
        }

        @media (max-width: 480px) {
            .login-header {
                padding: 32px 24px 24px;
            }

            .login-body {
                padding: 0 24px 32px;
            }

            .login-title {
                font-size: 24px;
            }

            .login-logo {
                width: 64px;
                height: 64px;
            }

            .login-logo i {
                font-size: 28px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <div class="login-logo">
                    <i class="fas fa-boxes"></i>
                </div>
                <h1 class="login-title"><?= APP_NAME ?></h1>
                <p class="login-subtitle">Sistema de Gestión de Inventario</p>
            </div>

            <div class="login-body">
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        <?= $_SESSION['success'] ?>
                    </div>
                    <?php unset($_SESSION['success']); ?>
                <?php endif; ?>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i>
                        <?= $_SESSION['error'] ?>
                    </div>
                    <?php unset($_SESSION['error']); ?>
                <?php endif; ?>

                <!-- Credenciales de demostración -->
             <!--    <div class="demo-credentials">
                    <h4><i class="fas fa-info-circle"></i> Credenciales de Prueba</h4>
                    <p><strong>Usuario:</strong> <code>admin</code></p>
                    <p><strong>Contraseña:</strong> <code>admin123</code></p>
                </div>
 -->
                <form method="POST" action="<?= APP_URL ?>/auth/authenticate">
                    <div class="form-group">
                        <label for="username">Usuario</label>
                        <div class="input-wrapper">
                            <i class="fas fa-user"></i>
                            <input type="text"
                                   id="username"
                                   name="username"
                                   class="form-control"
                                   placeholder="Ingrese su usuario"
                                   required
                                   autofocus
                                   autocomplete="username">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password">Contraseña</label>
                        <div class="input-wrapper">
                            <i class="fas fa-lock"></i>
                            <input type="password"
                                   id="password"
                                   name="password"
                                   class="form-control"
                                   placeholder="Ingrese su contraseña"
                                   required
                                   autocomplete="current-password">
                        </div>
                    </div>

                    <button type="submit" class="btn-login">
                        <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
                    </button>
                </form>
            </div>

            <div class="login-footer">
                <p>&copy; <?= date('Y') ?> <?= APP_NAME ?>. Todos los derechos reservados.</p>
            </div>
        </div>
    </div>
</body>
</html>
