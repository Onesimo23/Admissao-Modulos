<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<title>{{ config('app.name', 'ADMISSAO_SYS') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600,700&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#044386',
                        secondary: '#EAB308',
                        text: '#333333',
                        textLight: '#666666',
                    },
                    fontFamily: {
                        sans: ['Figtree', 'sans-serif'],
                    },
                }
            }
        }
    </script>
	<!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
	   .hero-bg {
            background-image: url('frontend1/img/slide/1.jpg');
            background-size: cover;
            background-position: center;
        }
        body {
            font-family: 'Figtree', sans-serif;
        }

        .section-padding {
            padding: 4rem 1rem;
        }

        .container-padding {
            max-width: 1200px;
            margin: 0 auto;
            padding-left: 1.5rem;
            padding-right: 1.5rem;
        }

        #header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            transition: all 0.3s ease-in-out;
			 /* border-bottom: 4px solid #EAB308; */
            border-top: 4px solid #3B82F6;
        }

        #header.scrolled {
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
        }

        .nav-link {
            position: relative;
            transition: color 0.3s ease;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -5px;
            left: 0;
            background-color: #2563EB;
            transition: width 0.3s ease;
        }

        .nav-link:hover::after {
            width: 100%;
        }

        .mobile-nav-toggle {
            display: none;
        }

        @media (max-width: 1023px) {
            .mobile-nav-toggle {
                display: block;
            }

            #navbar {
                position: fixed;
                top: 74px; /* Adjusted for the top border */
                right: -100%;
                width: 80%;
                height: calc(100vh - 74px);
                background-color: white;
                transition: 0.3s;
                padding: 20px;
                overflow-y: auto;
                box-shadow: -5px 0 15px rgba(0, 0, 0, 0.1);
            }

            #navbar.active {
                right: 0;
            }

            #navbar ul {
                flex-direction: column;
            }

            #navbar ul li {
                margin: 10px 0;
            }
        }
    </style>
</head>
<body class="bg-gray-100 text-text pt-20">
    <header id="header" class="w-full bg-white ">
        <div class="container-padding mx-auto flex justify-between items-center py-4">
            <!-- Logo -->
			<img class="h-16 w-auto" src="frontend1/img/logo.png" alt="Logo">
            <h1 class="text-2xl font-bold">
                <a href="#" class="text-primary hover:text-secondary transition duration-300">Admissão à UniSave</a>
            </h1>

            <!-- Navbar -->
            <nav id="navbar" class="lg:flex space-x-8">
                <ul class="flex lg:flex-row flex-col space-y-4 lg:space-y-0 lg:space-x-6">
                    <li><a class="nav-link text-text hover:text-primary" href="">Home</a></li>
                    <li><a class="nav-link text-text hover:text-primary" href="">Candidatura</a></li>
                    <li><a class="nav-link text-text hover:text-primary" href="https://drive.google.com/file/d/1ebA5bRsKxeBqGo3Do2ICUpSKDdETBqFG/view?usp=sharing">Edital 2025</a></li>
					<li class="relative group">
						<a href="#" class="nav-link text-text hover:text-primary flex items-center">
							Helpdesk <i class="ml-2 fas fa-chevron-down text-xs"></i>
						</a>
						<!-- Dropdown Menu -->
						<ul class="lg:absolute lg:left-0 lg:top-full mt-2 lg:hidden group-hover:block bg-white shadow-lg rounded-md w-64">
							<li>
								<a href="{{route('recover-id')}}" class="block px-6 py-3 text-text hover:bg-gray-100 hover:text-primary transition duration-300 text-lg">
									Recuperar Nr de Candidadato
								</a>
							</li>
							<li>
								<a href="{{route('reset')}}" class="block px-6 py-3 text-text hover:bg-gray-100 hover:text-primary transition duration-300 text-lg">
									Recuperar Senha
								</a>
							</li>
                            <li>
								<a href="{{ route('payment-guide') }}" class="block px-6 py-3 text-text hover:bg-gray-100 hover:text-primary transition duration-300 text-lg">
									Guia de Pagamento
								</a>
							</li>
                            <li>
								<a href="{{ route('registration-status') }}" class="block px-6 py-3 text-text hover:bg-gray-100 hover:text-primary transition duration-300 text-lg">
									Estado da Inscrição
								</a>
							</li>
						</ul>
					</li>
                    <li><a href="#faq" class="nav-link text-text hover:text-primary">Perguntas Frequentes</a></li>
                </ul>
            </nav>

            <!-- Button -->
            <a href="{{route('enrollment')}}" class="hidden lg:inline-block bg-primary text-white px-6 py-2 rounded-md hover:bg-secondary transition duration-300 shadow-md font-semibold">
                <span class="hidden md:inline">Fazer</span> Pré-Inscrição
            </a>

            <!-- Mobile Menu Toggle -->
            <button class="mobile-nav-toggle lg:hidden text-primary focus:outline-none" id="mobile-nav-toggle">
                <i class="fas fa-bars text-2xl"></i>
            </button>
        </div>
    </header>


    <main>
        <section class="hero-bg min-h-[300px] flex items-center justify-center text-white">
            <!-- Contêiner com o fundo colorido e a borda superior azul -->
            <div class="container-padding bg-primary bg-opacity-80 p-12 rounded-lg text-center border-t-4 border-yellow-500">
                <h2 class="text-2xl font-bold mb-3">Exames de Admissão 2025</h2>
                <p class=" mb-3">Caro candidato, Iniciou o processo de Candidatura aos Exames de Admissão 2025.</p>
                <!-- Responsividade e centralização no desktop -->
                <div class="flex flex-col space-y-4 sm:flex-row sm:space-y-0 sm:space-x-4 sm:justify-center">			
                    <a href="{{route('enrollment')}}" class="px-6 py-3 border-2 bg-yellow-500 border-yellow-600 text-white rounded hover:bg-yellow-600 hover:text-white transition">
                        Pré-Inscrição
                    </a>
                    <a href="{{route('login')}}" class="px-6 py-3 border-2 bg-blue-500 border-blue-600 text-white rounded hover:bg-blue-600 hover:text-white transition">
                        Acessar a Plataforma
                    </a>
					<a href="https://abrir.link/lJBxw" 
					   class="px-6 py-3 border-2 bg-green-500 border-green-600 text-white rounded hover:bg-green-600 hover:text-white transition">
						<i class="fab fa-whatsapp text-xl mr-2"></i>
						Grupo do Whatsapp
					</a>					
                </div>
				
            </div>
        </section>


        <section id="why-us" class="why-us py-12">
            <div class="container mx-auto px-6">
                <div class="flex flex-wrap gap-8">
                    <!-- Coluna Esquerda com Fundo Azul -->
                    <div class="w-full lg:w-[30%]">
                        <div class="content h-full p-8 bg-blue-600 text-white rounded-lg shadow">
                            <h4 class="text-center text-xl font-bold mb-6">Processo de Candidatura?</h4>
                            <p class="mb-3 font-bold">
                               CURSOS DO REGIME LABORAL:
                            </p>
                            <ul class="text-left list-disc pl-6 space-y-4">
                                <li>Os Exames de Admissão para os cursos do regime laboral terão lugar em todas as províncias do país;</li>
                            </ul>
							
                            <p class="mt-3 mb-3 font-bold">
                              CURSOS DO REGIME PÓS-LABORAL, EAD E SEMI-PRESENCIAL:
                            </p>
                            <ul class="text-left list-disc pl-6 space-y-4">
                                <li>A candidatura aos cursos de graduação, do regime pós-laboral, EAD e Semi-presencial será feita via Concurso Documental;</li>
                            </ul>							
                        </div>
                    </div>

                    <!-- Coluna Direita com as Caixas -->
                    <div class="w-full lg:w-[65%]">
                        <div class="grid grid-cols-1 xl:grid-cols-3 gap-8 h-full">
                            <!-- Caixa 1 - Edital -->
							<div class="bg-white rounded-lg shadow p-6 flex flex-col items-center">
								<div class="mb-6 text-blue-600">
									<svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
										<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
										<polyline points="14 2 14 8 20 8" />
										<line x1="16" y1="13" x2="8" y2="13" />
										<line x1="16" y1="17" x2="8" y2="17" />
										<line x1="10" y1="9" x2="8" y2="9" />
									</svg>
								</div>
								<h4 class="text-lg font-bold mb-4">Edital 2025</h4>
								<p class="text-center mb-6">Baixe o Edital clicando abaixo</p>
								<a href="https://drive.google.com/file/d/1ebA5bRsKxeBqGo3Do2ICUpSKDdETBqFG/view?usp=sharing" target="_blank" 
								   class="mt-auto bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-lg transition duration-300 text-center">
									Download Edital
								</a>
							</div>

                            <!-- Caixa 2 - Cursos -->
                            <div class="bg-white rounded-lg shadow p-6 flex flex-col items-center">
                                <div class="mb-6 text-yellow-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20" />
                                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z" />
                                        <path d="m8 6 4 4 4-4" />
                                        <path d="M12 10v7" />
                                    </svg>
                                </div>
                                <h4 class="text-lg font-bold mb-4">Cursos Disponíveis</h4>
                                <p class="text-center mb-6">Consulte os cursos para 2025</p>
                                <button class="mt-auto bg-yellow-500 hover:bg-yellow-600 text-white font-semibold py-2 px-6 rounded-lg transition duration-300">
                                    Ver Cursos
                                </button>
                            </div>

                            <!-- Caixa 3  -->
                            <div class="bg-white rounded-lg shadow p-6 flex flex-col items-center">
                                <div class="mb-6 text-[#2563EB]">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                                        <line x1="16" y1="2" x2="16" y2="6" />
                                        <line x1="8" y1="2" x2="8" y2="6" />
                                        <line x1="3" y1="10" x2="21" y2="10" />
                                        <path d="m9 16 2 2 4-4" />
                                    </svg>
                                </div>
                                <h4 class="text-lg font-bold mb-4">Realização dos Exames</h4>
                                <p class="text-center mb-6">21 a 24 de Janeiro de 2025</p>
                                <button class="mt-auto bg-[#2563EB] hover:bg--[#2563EB] text-white font-semibold py-2 px-6 rounded-lg transition duration-300">
                                    Ver Calendário
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="bg-primary bg-opacity-100 text-white py-12 px-4 mb-16">
            <div class="text-center">
                <h3 class="font-bold mb-4">GARANTA JÁ A SUA PRÉ - INSCRIÇÃO PARA OS EXAMES DE ADMISSÃO 2025</h3>
                <a href="{{route('enrollment')}}" class="inline-block px-6 py-3 bg-secondary  rounded-full font-semibold hover:bg-gray-100 transition duration-300">Fazer Pré Inscrição</a>
            </div>
        </section>

        <section id="services" class="py-16 bg-gray-50">
            <div class="container mx-auto px-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <!-- Card 1 - Documentos Necessários -->
                    <div class="bg-white p-8 rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-300">
                        <div class="flex flex-col items-center">
                            <div class="w-16 h-16 flex items-center justify-center rounded-full bg-blue-100 text-blue-600 mb-6">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                                    <polyline points="14 2 14 8 20 8" />
                                    <line x1="16" y1="13" x2="8" y2="13" />
                                    <line x1="16" y1="17" x2="8" y2="17" />
                                    <line x1="10" y1="9" x2="8" y2="9" />
                                </svg>
                            </div>
                            <h4 class="text-xl font-bold mb-4 text-gray-800 text-center">
                                <a href="#" class="hover:text-blue-600 transition-colors ">Período de candidatura para Cursos do Regime LABORAL</a>
                            </h4>
                            <p class="text-center text-gray-600">De 28 de Outubro de 2024 e com o término previsto para as 15 horas do dia 06 de Dezembro de 2024.</p>
                        </div>
                    </div>

                    <!-- Card 2 - Prazos de Inscrição -->
                    <div class="bg-white p-8 rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-300">
                        <div class="flex flex-col items-center">
                            <div class="w-16 h-16 flex items-center justify-center rounded-full bg-orange-100 text-orange-600 mb-6">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                                    <line x1="16" y1="2" x2="16" y2="6" />
                                    <line x1="8" y1="2" x2="8" y2="6" />
                                    <line x1="3" y1="10" x2="21" y2="10" />
                                </svg>
                            </div>
                            <h4 class="text-xl font-bold mb-4 text-gray-800 text-center">
                                <a href="#" class="hover:text-orange-600 transition-colors">Período de candidatura para os Cursos do Regime PÓS-LABORAL, EAD E SEMI-PRESENCIAL</a>
                            </h4>
                            <p class="text-center text-gray-600">De 28 de Outubro de 2024 e com o término previsto para as 15 horas do dia 31 de Janeiro de 2025;</p>
                        </div>
                    </div>

                    <!-- Card 3 - Suporte ao Candidato -->
                    <div class="bg-white p-8 rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-300">
                        <div class="flex flex-col items-center">
                            <div class="w-16 h-16 flex items-center justify-center rounded-full bg-pink-100 text-pink-600 mb-6">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10" />
                                    <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3" />
                                    <line x1="12" y1="17" x2="12.01" y2="17" />
                                </svg>
                            </div>
                            <h4 class="text-xl font-bold mb-4 text-gray-800">
                                <a href="#" class="hover:text-pink-600 transition-colors">Suporte ao Candidato</a>
                            </h4>
                            <p class="text-center text-gray-600">Em caso de necessidade de apoio, os candidatos podem contactar a Comissão de Exames de Admissão através dos contactos seguintes:856608521 / 876608520 848073141 / 878558282</p>
                        </div>
                    </div>

                    <!-- Card 4 - Passos para Inscrição -->
                    <div class="bg-white p-8 rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-300">
                        <div class="flex flex-col items-center">
                            <div class="w-16 h-16 flex items-center justify-center rounded-full bg-yellow-100 text-yellow-600 mb-6">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="8" y1="6" x2="21" y2="6" />
                                    <line x1="8" y1="12" x2="21" y2="12" />
                                    <line x1="8" y1="18" x2="21" y2="18" />
                                    <line x1="3" y1="6" x2="3.01" y2="6" />
                                    <line x1="3" y1="12" x2="3.01" y2="12" />
                                    <line x1="3" y1="18" x2="3.01" y2="18" />
                                </svg>
                            </div>
                            <h4 class="text-xl font-bold mb-4 text-gray-800">
                                <a href="#" class="hover:text-yellow-600 transition-colors">Passos para Inscrição</a>
                            </h4>
                            <p class="text-center text-gray-600">
                                1. Leia todo o edital.
								2. Preencha o formulário de inscrição online.<br>
                                2. Baixe a Referência de Pagamento.<br>
                                3. Realize o pagamento da taxa de inscrição.<br>
                                4. Aguarde pela confirmação da Inscrição.
                            </p>
                        </div>
                    </div>

                    <!-- Card 5 - Horário de Atendimento -->
                    <div class="bg-white p-8 rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-300">
                        <div class="flex flex-col items-center">
                            <div class="w-16 h-16 flex items-center justify-center rounded-full bg-red-100 text-red-600 mb-6">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10" />
                                    <polyline points="12 6 12 12 16 14" />
                                </svg>
                            </div>
                            <h4 class="text-xl font-bold mb-4 text-gray-800">
                                <a href="#" class="hover:text-red-600 transition-colors">Horário de Atendimento</a>
                            </h4>
                            <p class="text-center text-gray-600">O atendimento ao Candidato está é disponível de segunda a sexta, das 08h00 às 17h00. Respostas por email podem levar até 48 horas.</p>
                        </div>
                    </div>

                    <!-- Card 6 - Informações Gerais -->
                    <div class="bg-white p-8 rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-300">
                        <div class="flex flex-col items-center">
                            <div class="w-16 h-16 flex items-center justify-center rounded-full bg-teal-100 text-teal-600 mb-6">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10" />
                                    <line x1="12" y1="16" x2="12" y2="12" />
                                    <line x1="12" y1="8" x2="12.01" y2="8" />
                                </svg>
                            </div>
                            <h4 class="text-xl font-bold mb-4 text-gray-800">
                                <a href="#" class="hover:text-teal-600 transition-colors">Grupo de Whatsapp</a>
                            </h4>
                            <p class="text-center text-gray-600">Acesse ao grupo do Whatsapp criado exclusivamente para apoio ao candidato. para tal clique no botão Apoio ao Candidato.</p>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <!-- faq section -->
		<section id="faq" class="text-gray-800 py-16">
			<div class="container mx-auto px-4 md:px-8 lg:px-12">
				<!-- Título da seção -->
				<h2 class="mb-5 text-xl font-extrabold text-center sm:text-xl text-[#044386]">
					Dúvidas Frequentes
				</h2>
				
				<!-- FAQ Container -->
				<div class="max-w-4xl mx-auto space-y-6">
					<!-- Item 1 -->
					<details class="group bg-white p-4 rounded-lg shadow-md">
						<summary class="flex items-center justify-between cursor-pointer py-2 text-[#044386] group-hover:text-[#EAB308] transition">
							<span class="text-lg font-semibold">Quais são os cursos oferecidos pela Universidade Save?</span>
							<svg class="w-6 h-6 text-[#EAB308] group-open:rotate-180 transform transition-transform" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
							</svg>
						</summary>
						<div class="mt-3 text-gray-700">
							<p>A Universidade Save oferece diversos cursos em várias áreas. Para mais informações consulte o Edital.</p>
						</div>
					</details>

					<!-- Item 1 -->
					<details class="group bg-white p-4 rounded-lg shadow-md">
						<summary class="flex items-center justify-between cursor-pointer py-2 text-[#044386] group-hover:text-[#EAB308] transition">
							<span class="text-lg font-semibold">Qual é o número da conta para fazer o depósito?</span>
							<svg class="w-6 h-6 text-[#EAB308] group-open:rotate-180 transform transition-transform" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
							</svg>
						</summary>
						<div class="mt-3 text-gray-700">
							<p>
								Não há número da conta para depósito.  O sistema gera automaticamente uma REFERÊNCIA e uma ENTIDADE que deve usar 
								para o pagamento da sua candidatura. Após realizar a pré-inscrição, acesse  o sistema pelo link <b><a href="https://admissao.unisave.ac.mz/acesso" >https://admissao.unisave.ac.mz/acesso</a></b> e baixe o guião de pagamento.
							</p>
						</div>
					</details>					
					
				</div>
			</div>
		</section>




		<div class="fixed bottom-4 right-4">
			<a href="https://abrir.link/lJBxw" 
			   class="flex items-center justify-center px-4 py-2 bg-green-500 text-white rounded-full shadow-lg hover:bg-secondary transition-transform transform hover:scale-105">
				<i class="fab fa-whatsapp text-2xl mr-2"></i>
				<span class="text-sm font-semibold">Apoio ao Candidato</span>
			</a>
		</div>



    <footer class="bg-primary text-white py-6">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-10 gap-8 mb-8">
                <!-- Apoio ao Candidato (40%) -->
                <div class="md:col-span-4 flex flex-col items-center md:items-start">
                    <h3 class="text-xl font-semibold mb-4 text-secondary">Apoio ao Candidato</h3>
                    <p class="text-sm mb-4 leading-relaxed items-center">
                        Em caso de necessidade de apoio, os candidatos podem contactar a Comissão de Exames de Admissão através dos contactos seguintes:
						<br> <b>856608521 / 876608520 848073141 / 878558282 </b>
                    </p>

                    <div class="flex space-x-4">
                        <a href="https://www.facebook.com/p/Universidade-Save-100063559124409/?locale=pt_BR" class="text-white hover:text-secondary transition duration-300 text-2xl">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="https://abrir.link/lJBxw" class="text-white hover:text-secondary transition duration-300 text-2xl">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                        <a href="#" class="text-white hover:text-secondary transition duration-300 text-2xl">
                            <i class="fab fa-youtube"></i>
                        </a>
                    </div>
                </div>
                <!-- Links Úteis (30%) -->
                <div class="md:col-span-3 flex flex-col items-center md:items-start">
                    <h4 class="text-xl font-semibold mb-4 text-secondary">Links Úteis</h4>
                        <p class="text-sm mb-2"><a href="#" class="text-white hover:text-secondary transition duration-300">Home</a></p>
                        <p class="text-sm mb-2"><a href="#about" class="text-white hover:text-secondary transition duration-300">Candidatura</a></p>
                        <p class="text-sm mb-2"><a href="#services" class="text-white hover:text-secondary transition duration-300">Edital 2025</a></p>
                        <p class="text-sm mb-2"><a href="#contact" class="text-white hover:text-secondary transition duration-300">Contactos</a></p>
                </div>
                <!-- Contactos (30%) -->
                <div class="md:col-span-3 flex flex-col items-center md:items-start">
                    <h4 class="text-xl font-semibold mb-4 text-secondary">Contactos</h4>
                    <p class="text-sm mb-2">Universidade Save</p>
                    <p class="text-sm mb-2">Chongoene, Moçambique</p>
                    <p class="text-sm mb-2">Tel: <a href="tel:+258870687108" class="underline hover:text-secondary transition duration-300">+258 876 608 520</a></p>
                    <p class="text-sm mb-2">Email: <a href="mailto:admissao@unisave.ac.mz" class="underline hover:text-secondary transition duration-300">admissao@unisave.ac.mz</a></p>
                </div>
            </div>
            <!-- Rodapé inferior -->
            <div class="border-t border-secondary pt-6 text-center">
                <p class="text-sm">&copy; 2024 Universidade Save. Todos os direitos reservados.</p>
            </div>
        </div>
    </footer>



		
		<script>
			document.addEventListener('DOMContentLoaded', function() {
				const mobileNavToggle = document.getElementById('mobile-nav-toggle');
				const navbar = document.getElementById('navbar');
				const header = document.getElementById('header');

				mobileNavToggle.addEventListener('click', function() {
					navbar.classList.toggle('active');
					mobileNavToggle.classList.toggle('active');
					mobileNavToggle.innerHTML = navbar.classList.contains('active') 
						? '<i class="fas fa-times text-2xl"></i>' 
						: '<i class="fas fa-bars text-2xl"></i>';
				});

				window.addEventListener('scroll', function() {
					if (window.scrollY > 100) {
						header.classList.add('scrolled');
					} else {
						header.classList.remove('scrolled');
					}
				});
			});
		</script>		

        <script>
            // Smooth scrolling for anchor links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    e.preventDefault();
                    document.querySelector(this.getAttribute('href')).scrollIntoView({
                        behavior: 'smooth'
                    });
                });
            });

            // Mobile menu toggle
            const mobileMenuButton = document.createElement('button');
            mobileMenuButton.classList.add('md:hidden', 'text-accent', 'hover:text-secondary', 'transition');
            mobileMenuButton.innerHTML = `
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        `;

            const navigation = document.querySelector('nav > div');
            navigation.appendChild(mobileMenuButton);

            const mobileMenu = document.querySelector('nav > div > div');
            mobileMenuButton.addEventListener('click', () => {
                mobileMenu.classList.toggle('hidden');
                mobileMenu.classList.toggle('flex');
                mobileMenu.classList.toggle('flex-col');
                mobileMenu.classList.toggle('absolute');
                mobileMenu.classList.toggle('top-full');
                mobileMenu.classList.toggle('left-0');
                mobileMenu.classList.toggle('right-0');
                mobileMenu.classList.toggle('bg-white');
                mobileMenu.classList.toggle('shadow-md');
                mobileMenu.classList.toggle('p-4');
            });
        </script>


</body>

</html>