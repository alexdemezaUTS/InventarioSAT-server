<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surtidora de Alta Tecnología S.A. de C.V. | 35 años impulsando el futuro</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-blue: #0056b3;
            --secondary-blue: #0077cc;
            --light-blue: #e6f2ff;
            --dark-blue: #003a75;
            --accent-teal: #00a8b5;
            --white: #ffffff;
            --light-gray: #f5f8fa;
            --dark-gray: #333333;
            --text-color: #2d3748;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: var(--light-gray);
            color: var(--text-color);
            line-height: 1.6;
        }

        /* Header */
        .header {
            background: linear-gradient(135deg, var(--primary-blue), var(--dark-blue));
            color: var(--white);
            padding: 15px 0;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .container {
            width: 90%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 15px;
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .logo-icon {
            font-size: 32px;
            color: var(--accent-teal);
        }

        .logo-text {
            font-size: 24px;
            font-weight: 700;
        }

        .nav-links {
            display: flex;
            gap: 30px;
        }

        .nav-links a {
            color: var(--white);
            text-decoration: none;
            font-weight: 500;
            font-size: 16px;
            transition: all 0.3s ease;
            position: relative;
        }

        .nav-links a:hover {
            color: var(--accent-teal);
        }

        .nav-links a::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 0;
            height: 2px;
            background-color: var(--accent-teal);
            transition: width 0.3s ease;
        }

        .nav-links a:hover::after {
            width: 100%;
        }

        /* Hero Section */
        .hero {
            background: linear-gradient(rgba(0, 56, 117, 0.9), rgba(0, 56, 117, 0.8)), url('https://images.unsplash.com/photo-1518770660439-4636190af475?ixlib=rb-4.0.3&auto=format&fit=crop&w=1950&q=80');
            background-size: cover;
            background-position: center;
            color: var(--white);
            padding: 100px 0;
            text-align: center;
        }

        .hero-content {
            max-width: 800px;
            margin: 0 auto;
        }

        .hero h1 {
            font-size: 48px;
            margin-bottom: 20px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .hero p {
            font-size: 22px;
            margin-bottom: 30px;
            opacity: 0.9;
        }

        .anniversary-badge {
            display: inline-block;
            background: linear-gradient(135deg, var(--accent-teal), #008e9b);
            color: var(--white);
            padding: 12px 30px;
            border-radius: 50px;
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 40px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            border: 2px solid rgba(255, 255, 255, 0.3);
        }

        .cta-button {
            display: inline-block;
            background-color: var(--accent-teal);
            color: var(--white);
            padding: 15px 40px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 700;
            font-size: 18px;
            transition: all 0.3s ease;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
        }

        .cta-button:hover {
            background-color: #008e9b;
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
        }

        /* About Section */
        .about-section {
            padding: 100px 0;
            background-color: var(--white);
        }

        .section-header {
            text-align: center;
            margin-bottom: 60px;
        }

        .section-title {
            font-size: 36px;
            color: var(--primary-blue);
            margin-bottom: 20px;
            position: relative;
            display: inline-block;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background-color: var(--accent-teal);
        }

        .section-subtitle {
            font-size: 20px;
            color: var(--dark-gray);
            max-width: 700px;
            margin: 0 auto;
        }

        .about-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 40px;
            margin-top: 50px;
        }

        .about-card {
            background: linear-gradient(135deg, var(--light-blue), var(--white));
            border-radius: 15px;
            padding: 40px 30px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s ease;
            position: relative;
            overflow: hidden;
            border-left: 5px solid var(--accent-teal);
        }

        .about-card:hover {
            transform: translateY(-10px);
        }

        .card-icon {
            font-size: 48px;
            color: var(--accent-teal);
            margin-bottom: 25px;
        }

        .card-title {
            font-size: 24px;
            color: var(--primary-blue);
            margin-bottom: 20px;
        }

        .card-content {
            font-size: 17px;
            line-height: 1.7;
        }

        /* Offices Section */
        .offices-section {
            padding: 80px 0;
            background: linear-gradient(135deg, var(--primary-blue), var(--dark-blue));
            color: var(--white);
            text-align: center;
        }

        .office-grid {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 30px;
            margin-top: 50px;
        }

        .office-card {
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            padding: 30px;
            width: 280px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
        }

        .office-card:hover {
            transform: translateY(-10px);
            background-color: rgba(255, 255, 255, 0.15);
            box-shadow: 0 12px 25px rgba(0, 0, 0, 0.2);
        }

        .office-title {
            font-size: 22px;
            margin-bottom: 15px;
            color: var(--accent-teal);
        }

        .office-description {
            opacity: 0.9;
            margin-bottom: 20px;
        }

        .office-icon {
            font-size: 40px;
            margin-bottom: 20px;
            color: var(--accent-teal);
        }

        /* Values Section */
        .values-section {
            padding: 100px 0;
            background-color: var(--light-gray);
        }

        .values-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            margin-top: 50px;
        }

        .value-card {
            background-color: var(--white);
            border-radius: 15px;
            padding: 40px 30px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.05);
            text-align: center;
            transition: all 0.3s ease;
        }

        .value-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        }

        .value-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--primary-blue), var(--secondary-blue));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
            font-size: 32px;
            color: var(--white);
        }

        .value-title {
            font-size: 24px;
            color: var(--primary-blue);
            margin-bottom: 20px;
        }

        /* Footer */
        .footer {
            background-color: var(--dark-blue);
            color: var(--white);
            padding: 60px 0 30px;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 40px;
            margin-bottom: 40px;
        }

        .footer-column h3 {
            font-size: 20px;
            margin-bottom: 25px;
            position: relative;
            padding-bottom: 10px;
        }

        .footer-column h3::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 50px;
            height: 3px;
            background-color: var(--accent-teal);
        }

        .footer-links {
            list-style: none;
        }

        .footer-links li {
            margin-bottom: 12px;
        }

        .footer-links a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .footer-links a:hover {
            color: var(--accent-teal);
            padding-left: 5px;
        }

        .contact-info {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 15px;
        }

        .copyright {
            text-align: center;
            padding-top: 30px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            font-size: 15px;
            opacity: 0.7;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .nav-links {
                gap: 20px;
            }
            
            .hero h1 {
                font-size: 40px;
            }
            
            .hero p {
                font-size: 20px;
            }
        }

        @media (max-width: 768px) {
            .navbar {
                flex-direction: column;
                gap: 20px;
            }
            
            .hero {
                padding: 70px 0;
            }
            
            .hero h1 {
                font-size: 32px;
            }
            
            .hero p {
                font-size: 18px;
            }
            
            .anniversary-badge {
                font-size: 18px;
            }
            
            .section-title {
                font-size: 30px;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="container">
            <nav class="navbar">
                <div class="logo">
                    <i class="fas fa-microchip logo-icon"></i>
                    <div class="logo-text">SAT</div>
                </div>
                <div class="nav-links">
                    <a href="#inicio">Inicio</a>
                    <a href="#quienes-somos">¿Quiénes somos?</a>
                    
                    <a href="#contacto">Contacto</a>
                </div>
            </nav>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero" id="inicio">
        <div class="container">
            <div class="hero-content">
                <div class="anniversary-badge">¡35 años impulsando el futuro de la tecnología!</div>
                <h1>Surtidora de Alta Tecnología S.A. de C.V.</h1>
                <p>Soluciones innovadoras para empresas e instituciones con el máximo respaldo de calidad y servicio</p>
                <a href="#quienes-somos" class="cta-button">Conoce nuestra historia</a>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="about-section" id="quienes-somos">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">¿Quiénes somos?</h2>
                <p class="section-subtitle">Con 35 años de experiencia, ofrecemos soluciones tecnológicas de vanguardia con presencia en todo México y Sudamérica</p>
            </div>
            
            <div class="about-grid">
                <div class="about-card">
                    <i class="fas fa-building card-icon"></i>
                    <h3 class="card-title">Nuestra Historia</h3>
                    <p class="card-content">Surtidora de Alta Tecnología S.A. de C.V. en sus 35 años de existencia, con oficinas en el Norte, Centro, Sur de México y representación en Sudamérica, se enorgullece en ofrecerle a sus clientes el máximo respaldo de calidad y servicio de marcas reconocidas a nivel mundial.</p>
                </div>
                
                <div class="about-card">
                    <i class="fas fa-bullseye card-icon"></i>
                    <h3 class="card-title">Nuestra Misión</h3>
                    <p class="card-content">Ser una empresa de aplicaciones tecnológicas, proveedora de soluciones innovadoras, integrales y de alto valor agregado, con clientes satisfechos por la calidad de los productos y la eficiente atención y servicio del personal.</p>
                </div>
                
                <div class="about-card">
                    <i class="fas fa-eye card-icon"></i>
                    <h3 class="card-title">Nuestra Visión</h3>
                    <p class="card-content">Contribuir a la difusión y aplicación de los avances tecnológicos, mediante la búsqueda continua de innovaciones de alto valor agregado, que al ser puestas en operación por instituciones o empresas, faciliten o hagan más eficientes las actividades del ser humano.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Offices Section -->
    <section class="offices-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Nuestra Presencia</h2>
                <p class="section-subtitle">Oficinas estratégicamente ubicadas para servirte en todo México y Sudamérica</p>
            </div>
            
            <div class="office-grid">
                <div class="office-card">
                    <i class="fas fa-map-marker-alt office-icon"></i>
                    <h3 class="office-title">Norte de México</h3>
                    <p class="office-description">Oficinas estratégicas en las principales ciudades del norte del país para atender a la industria manufacturera y maquiladora.</p>
                </div>
                
                <div class="office-card">
                    <i class="fas fa-city office-icon"></i>
                    <h3 class="office-title">Centro de México</h3>
                    <p class="office-description">Sede central en la Ciudad de México con capacidad para atender a las instituciones gubernamentales y empresas líderes.</p>
                </div>
                
                <div class="office-card">
                    <i class="fas fa-sun office-icon"></i>
                    <h3 class="office-title">Sur de México</h3>
                    <p class="office-description">Presencia en los estados del sur para apoyar el desarrollo tecnológico de la región con soluciones innovadoras.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Values Section -->
    <section class="values-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Nuestros Valores</h2>
                <p class="section-subtitle">Principios fundamentales que guían nuestro trabajo diario</p>
            </div>
            
            <div class="values-container">
                <div class="value-card">
                    <div class="value-icon">
                        <i class="fas fa-medal"></i>
                    </div>
                    <h3 class="value-title">Calidad</h3>
                    <p>Ofrecemos productos y servicios de la más alta calidad, respaldados por marcas líderes a nivel mundial.</p>
                </div>
                
                <div class="value-card">
                    <div class="value-icon">
                        <i class="fas fa-lightbulb"></i>
                    </div>
                    <h3 class="value-title">Innovación</h3>
                    <p>Buscamos continuamente las soluciones más innovadoras para satisfacer las necesidades cambiantes de nuestros clientes.</p>
                </div>
                
                <div class="value-card">
                    <div class="value-icon">
                        <i class="fas fa-hands-helping"></i>
                    </div>
                    <h3 class="value-title">Servicio</h3>
                    <p>Brindamos atención personalizada y soporte técnico especializado para garantizar el éxito de nuestros clientes.</p>
                </div>
                
                <div class="value-card">
                    <div class="value-icon">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <h3 class="value-title">Capacitación</h3>
                    <p>Invertimos en la formación continua de nuestro equipo para ofrecer el mejor conocimiento técnico especializado.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer" id="contacto">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-column">
                    <h3>Sobre SAT</h3>
                    <p>35 años de experiencia ofreciendo soluciones tecnológicas innovadoras con presencia en todo México y Sudamérica.</p>
                    <div class="contact-info">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>CDMX, México</span>
                    </div>
                    <div class="contact-info">
                        <i class="fas fa-phone"></i>
                        <span>+52 55 1234 5678</span>
                    </div>
                    <div class="contact-info">
                        <i class="fas fa-envelope"></i>
                        <span>info@suraltec.com</span>
                    </div>
                </div>
                
                <div class="footer-column">
                    <h3>Enlaces Rápidos</h3>
                    <ul class="footer-links">
                        <li><a href="#inicio">Inicio</a></li>
                        <li><a href="#quienes-somos">¿Quiénes Somos?</a></li>
                        <li><a href="#servicios">Servicios</a></li>
                        <li><a href="#laboratorios">Laboratorios</a></li>
                        <li><a href="#soluciones">Soluciones</a></li>
                        <li><a href="#contacto">Contacto</a></li>
                    </ul>
                </div>
                
                <div class="footer-column">
                    <h3>Áreas de Especialización</h3>
                    <ul class="footer-links">
                        <li><a href="#">Tecnologías de Información</a></li>
                        <li><a href="#">Energías Renovables</a></li>
                        <li><a href="#">Seguridad</a></li>
                        <li><a href="#">Educación Tecnológica</a></li>
                        <li><a href="#">Robótica</a></li>
                        <li><a href="#">Realidad Virtual</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="copyright">
                <p>&copy; 2023 Surtidora de Alta Tecnología S.A. de C.V. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>
</body>
</html>