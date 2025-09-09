<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>403 Forbidden | Access Denied</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #7e57c2;
            --secondary-color: #5e35b1;
            --accent-color: #f3e5f5;
            --text-color: #333;
            --light-text: #6c757d;
        }

        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            color: var(--text-color);
        }

        .error-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 2rem;
        }

        .error-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            transition: transform 0.3s ease;
        }

        .error-card:hover {
            transform: translateY(-5px);
        }

        .error-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 2rem;
            text-align: center;
        }

        .error-icon {
            font-size: 5rem;
            margin-bottom: 1rem;
            animation: pulse 2s infinite;
        }

        .error-body {
            padding: 2.5rem;
        }

        .error-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: var(--secondary-color);
        }

        .error-description {
            font-size: 1.2rem;
            margin-bottom: 2rem;
            color: var(--light-text);
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.3s;
        }

        .btn-primary:hover {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .error-image {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            margin-top: 2rem;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .help-links {
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid #eee;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }

            100% {
                transform: scale(1);
            }
        }

        @media (max-width: 768px) {
            .error-body {
                padding: 1.5rem;
            }

            .error-title {
                font-size: 2rem;
            }

            .error-icon {
                font-size: 4rem;
            }
        }
    </style>
</head>

<body>
    <div class="container error-container">
        <div class="error-card">
            <div class="error-header">
                <div class="error-icon">
                    <i class="fas fa-ban"></i>
                </div>
                <h1>403 Forbidden</h1>
            </div>

            <div class="error-body text-center">
                <h2 class="error-title">Access Denied</h2>
                <p class="error-description">
                    You don't have permission to access this resource.
                    This might be due to insufficient privileges or an authentication issue.
                </p>

                <div class="d-flex justify-content-center gap-3 flex-wrap">
                    <a href="{{ url('/') }}" class="btn btn-primary">
                        <i class="fas fa-home me-2"></i>Back to Home
                    </a>
                    <button class="btn btn-outline-secondary" onclick="history.back()">
                        <i class="fas fa-arrow-left me-2"></i>Go Back
                    </button>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <button class="btn btn-outline-secondary" type="submit">
                            <i class="fas fa-arrow-left me-2"></i>{{ __('Log Out') }}
                        </button>
                    </form>
                </div>

                <div class="mt-4">
                    <img src="{{ asset('img/403.png') }}" alt="403 Forbidden Illustration" class="error-image"
                        width="400">
                </div>

                <div class="help-links">
                    <p class="text-muted mb-2">Need help? Try one of these options:</p>
                    <div class="d-flex justify-content-center gap-4 flex-wrap">
                        <a href="#" class="text-decoration-none">
                            <i class="fas fa-question-circle me-1"></i>Help Center
                        </a>
                        <a href="#" class="text-decoration-none">
                            <i class="fas fa-book me-1"></i>Documentation
                        </a>
                        <a href="#" class="text-decoration-none">
                            <i class="fas fa-exclamation-circle me-1"></i>Report Issue
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
