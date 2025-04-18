<div>
    <div class="py-4 sm:py-12">
        <div class="max-w-10xl mx-auto px-4 sm:px-10 lg:px-10">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4">
                    <!-- Seção de Boas-vindas -->
                    <section class="mb-4">
                        <h5 class="font-bold dark:text-gray-100 mb-2">
                            Bem-vindo, <span class="text-blue-600">{{ strtoupper(Auth::user()->name) }}</span> (Código do Candidato: <span class="text-blue-600"> {{ Auth::user()->candidate->id }}</span>)
                        </h5>
                    </section>

                    <!-- Seção de Progresso -->
                    @php
                    $progresso = 50; // Valor padrão
                    $mensagem = 'A sua candidatura já iniciou, Clique em "<span class="text-blue-800"><b>Guia de Pagamento</b></span>" para Baixar a Referencia de pagamento. Caso deseje alterar os dados da candidatura, clique em "<span class="text-blue-800"><b>Editar Dados</b></span>". <b>Antes de efectuar o pagamento</b>, Poderá actualizar os dados da candidatura';
                    $cor = 'red';

                    if (Auth::user()->candidate && Auth::user()->candidate->payment && Auth::user()->candidate->payment->status == 0) {
                    $progresso = 75;
                    $mensagem = 'Seu Guia de pagamento foi gerado, Efectue o pagamento e aguarde 72h pela confirmação no sistema.<span class="text-red-800"><b>. NÃO Edite seus dados, após efectuar o pagamento</b></span>';
                    $cor = 'yellow';
                    }

                    if (Auth::user()->candidate && Auth::user()->candidate->payment && Auth::user()->candidate->payment->status == 1) {
                    $progresso = 100;
                    $mensagem = 'A sua Pré-Inscrição foi concluida!';
                    $cor = 'green';
                    }
                    @endphp

                    @php
                    // verifica se o candidato tem ou nao uma referencia, se não assume null.
                    $candidate = Auth::user()->candidate;
                    $payment = $candidate ? $candidate->payment()->first() : null;
                    @endphp
                    <section class="mb-4">
                        <div class="flex flex-col space-y-4">
                            <div class="w-full">
                                <x-ts-progress :percent="$progresso" :color="$cor" floating lg />
                            </div>
                            <div class="w-full">
                                <div class="bg-{{ $cor }}-100 border-l-4 border-{{ $cor }}-600 text-{{ $cor }}-700 p-4" role="alert">
                                    <p class="font-bold">Estado da Candidatura</p>
                                    <p>{!! $mensagem !!}</p>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- Tabs Section -->
                    <div x-data="{ activeTab: 'applicationDetails' }" class="mt-8">
                        <div class="border-b border-gray-200 dark:border-gray-700">
                            <nav class="-mb-px flex space-x-8">
                                @if($candidate->juryDistributions->isNotEmpty())
                                <a href="#" @click.prevent="activeTab = 'examDetails'"
                                    :class="{ 'border-indigo-500 text-indigo-600': activeTab === 'examDetails', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'examDetails' }"
                                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                                    Detalhes do Exame
                                </a>
                                @endif
                                <a href="#" @click.prevent="activeTab = 'applicationDetails'" :class="{ 'border-indigo-500 text-indigo-600': activeTab === 'applicationDetails', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'applicationDetails' }" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                                    Detalhes da Candidatura
                                </a>
                            </nav>
                        </div>

                        <!-- Exam Details Tab -->

                        @if($candidate->juryDistributions->isNotEmpty())
                        <div x-show="activeTab === 'examDetails'" class="mt-6">
                            <h2 class="text-center text-2xl font-bold leading-9 tracking-tight text-gray-900">
                                Local e Sala de Realização de Exames de Admissão
                            </h2>
                            <hr>
                            <center>
                                <span>
                                    <h4>Dados dos seus Exames</h4>
                                </span>
                            </center>
                            <hr>
                            @if (session()->has('error'))
                            <div class="mt-4 p-4 text-sm text-red-700 bg-red-100 rounded-lg">
                                {{ session('error') }}
                            </div>
                            @endif

                            <div class="mt-6">
                                <div class="mt-8">
                                    <div class="mt-4 overflow-x-auto">
                                        <div class="bg-white shadow-sm rounded-lg p-6">
                                            <div x-data="{ activeExamTab: 'exam1' }">
                                                <div class="border-b border-gray-200">
                                                    <nav class="-mb-px flex space-x-8">
                                                        <a href="#" @click.prevent="activeExamTab = 'exam1'"
                                                            :class="{ 'border-indigo-500 text-indigo-600': activeExamTab === 'exam1', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeExamTab !== 'exam1' }"
                                                            class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                                                            Exame 1
                                                        </a>
                                                        <a href="#" @click.prevent="activeExamTab = 'exam2'"
                                                            :class="{ 'border-indigo-500 text-indigo-600': activeExamTab === 'exam2', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeExamTab !== 'exam2' }"
                                                            class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                                                            Exame 2
                                                        </a>
                                                    </nav>
                                                </div>

                                                <!-- Exame 1 -->
                                                <div x-show="activeExamTab === 'exam1'" class="mt-6">
                                                    @if(isset($candidate->juryDistributions[0]))
                                                    @php $distribution = $candidate->juryDistributions[0]; @endphp
                                                    <div class="mb-6 border-b border-gray-300 pb-4 space-y-2">
                                                        <p class="text-sm text-gray-600"><strong>DISCIPLINA:</strong> {{ $distribution->examSubject->name ?? 'N/A' }}</p>
                                                        <p class="text-sm text-gray-600"><strong>DATA:</strong>
                                                            {{ $distribution->examSubject->exam_date ? \Carbon\Carbon::parse($distribution->examSubject->exam_date)->format('d/m/Y') : 'N/A' }}
                                                        </p>
                                                        <p class="text-sm text-gray-600"><strong>PROVÍNCIA:</strong> {{ $distribution->province->name ?? 'N/A' }}</p>
                                                        <p class="text-sm text-gray-600"><strong>Hora de chegada:</strong>
                                                            {{ $distribution->examSubject->arrival_time ? \Carbon\Carbon::parse($distribution->examSubject->arrival_time)->format('H:i') : 'N/A' }}
                                                        </p>
                                                        <p class="text-sm text-gray-600"><strong>Início:</strong>
                                                            {{ $distribution->examSubject->start_time ? \Carbon\Carbon::parse($distribution->examSubject->start_time)->format('H:i') : 'N/A' }}
                                                        </p>
                                                        <p class="text-sm text-gray-600"><strong>LOCAL:</strong> {{ $distribution->room->school->name ?? 'N/A' }}</p>
                                                        <p class="text-sm text-gray-600"><strong>SALA:</strong> {{ $distribution->room->name ?? 'N/A' }}</p>
                                                    </div>
                                                    @endif
                                                </div>

                                                <!-- Exame 2 -->
                                                <div x-show="activeExamTab === 'exam2'" class="mt-6">
                                                    @if(isset($candidate->juryDistributions[1]))
                                                    @php $distribution = $candidate->juryDistributions[1]; @endphp
                                                    <div class="mb-6 border-b border-gray-300 pb-4 space-y-2">
                                                        <p class="text-sm text-gray-600"><strong>DISCIPLINA:</strong> {{ $distribution->examSubject->name ?? 'N/A' }}</p>
                                                        <p class="text-sm text-gray-600"><strong>DATA:</strong>
                                                            {{ $distribution->examSubject->exam_date ? \Carbon\Carbon::parse($distribution->examSubject->exam_date)->format('d/m/Y') : 'N/A' }}
                                                        </p>
                                                        <p class="text-sm text-gray-600"><strong>PROVÍNCIA:</strong> {{ $distribution->province->name ?? 'N/A' }}</p>
                                                        <p class="text-sm text-gray-600"><strong>Hora de chegada:</strong>
                                                            {{ $distribution->examSubject->arrival_time ? \Carbon\Carbon::parse($distribution->examSubject->arrival_time)->format('H:i') : 'N/A' }}
                                                        </p>
                                                        <p class="text-sm text-gray-600"><strong>Início:</strong>
                                                            {{ $distribution->examSubject->start_time ? \Carbon\Carbon::parse($distribution->examSubject->start_time)->format('H:i') : 'N/A' }}
                                                        </p>
                                                        <p class="text-sm text-gray-600"><strong>LOCAL:</strong> {{ $distribution->room->school->name ?? 'N/A' }}</p>
                                                        <p class="text-sm text-gray-600"><strong>SALA:</strong> {{ $distribution->room->name ?? 'N/A' }}</p>
                                                    </div>
                                                    @endif
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif





                        <!-- Application Details Tab -->
                        <div x-show="activeTab === 'applicationDetails'" class="mt-6">
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                                Detalhes da Candidatura
                            </h4>
                            <div class="w-full bg-white dark:bg-gray-700 shadow-md rounded-lg">
                                <!-- Mobile View (< 768px) -->
                                <div class="block md:hidden">
                                    <div class="p-4">
                                        <div class="space-y-4">
                                            <div class="space-y-2">
                                                <div class="text-sm font-medium text-gray-900 dark:text-gray-200">
                                                    {{ strtoupper(Auth::user()->candidate->course->name ?? '-') }}
                                                </div>
                                                <div class="flex flex-col space-y-1">
                                                    <span class="text-sm text-gray-500 dark:text-gray-300">
                                                        <span class="font-medium">Extensão:</span>
                                                        {{ strtoupper(Auth::user()->candidate->university->name ?? '-') }}
                                                    </span>
                                                    <span class="text-sm text-gray-500 dark:text-gray-300">
                                                        <span class="font-medium">Regime:</span>
                                                        {{ strtoupper(Auth::user()->candidate->regime->name ?? '-') }}
                                                    </span>
                                                </div>
                                            </div>
                                            <!-- Botões em grid para mobile -->
                                            <div class="grid grid-cols-1 gap-2">
                                                <x-ts-button xs wire:click="$toggle('detailsForm')" class="w-full justify-center">
                                                    Detalhes
                                                </x-ts-button>
                                                @if($payment && $payment->status < 1 || !$payment)
                                                    <x-ts-button xs wire:click="$toggle('editForm')" color="blue" class="w-full justify-center">
                                                    Editar Dados
                                                    </x-ts-button>
                                                    @endif
                                                    @if($payment && $payment->status == 1)
                                                    <x-ts-button xs color="green" href="{{ route('enrollment.confirmation') }}" class="w-full justify-center">
                                                        Baixar Confirmação da Inscrição
                                                    </x-ts-button>
                                                    @else
                                                    <x-ts-button xs color="amber" href="{{ route('enrollment.download') }}" class="w-full justify-center">
                                                        Baixar Guia de Pagamento
                                                    </x-ts-button>
                                                    @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Desktop View (≥ 768px) -->
                                <div class="hidden md:block overflow-x-auto">
                                    <table class="w-full divide-y divide-gray-200 dark:divide-gray-600">
                                        <thead class="bg-gray-50 dark:bg-gray-600">
                                            <tr>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                    Curso
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                    Extensão
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                    Regime
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                    Acções
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white dark:bg-gray-700 divide-y divide-gray-200 dark:divide-gray-600">
                                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                                                <td class="px-6 py-4">
                                                    <span class="text-sm font-medium text-gray-900 dark:text-gray-200">
                                                        {{ strtoupper(Auth::user()->candidate->course->name ?? '-') }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4">
                                                    <span class="text-sm text-gray-500 dark:text-gray-300">
                                                        {{ strtoupper(Auth::user()->candidate->university->name ?? '-') }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4">
                                                    <span class="text-sm text-gray-500 dark:text-gray-300">
                                                        {{ strtoupper(Auth::user()->candidate->regime->name ?? '-') }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4">
                                                    <div class="flex flex-row gap-2">
                                                        <x-ts-button xs wire:click="$toggle('detailsForm')">
                                                            Detalhes
                                                        </x-ts-button>
                                                        @if($payment && $payment->status < 1 || !$payment)
                                                            <x-ts-button xs wire:click="$toggle('editForm')" color="blue">
                                                            Editar Dados
                                                            </x-ts-button>
                                                            @endif
                                                            @if($payment && $payment->status == 1)
                                                            <x-ts-button xs color="green" href="{{ route('enrollment.confirmation') }}">
                                                                Baixar Confirmação da Inscrição
                                                            </x-ts-button>
                                                            @else
                                                            <x-ts-button xs color="amber" href="{{ route('enrollment.download') }}">
                                                                Baixar Guia de Pagamento
                                                            </x-ts-button>
                                                            @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if(Auth::user()->candidate->regime_id > 1)
                    <section class="mb-4">
                        <div class="flex flex-col space-y-4">
                            <div class="w-full">
                                <div class="bg-gray-100 border-l-4 border-gray-600 text-gray-700 p-4" role="alert">
                                    <p class="font-bold text-blue-700">DOCUMENTOS DE CANDIDATURA</p>
                                    <p class="mt-2 mb-2">
                                        Caro Candidato ao Regime <b>EAD, POS-LABORAL ou SEMI-PRESENCIAL</b>, após a confirmação do pagamento, submeta os seguintes documentos:
                                    </p>
                                    <ul class="list-disc pl-8 space-y-2 p-10">
                                        <li class="text-sm">Formulário de Pré-Inscrição com o Estado da Inscrição Confirmada</li>
                                        <li class="text-sm">Cartão ou declaração do NUIT (Número Único de Identificação Tributária)</li>
                                        <li class="text-sm">Um (1) dos seguintes documentos de identificação:</li>
                                        <li class="text-sm ml-5">1) Bilhete de Identidade (BI) ou Talão de BI</li>
                                        <li class="text-sm ml-5">2) Cartão de eleitor</li>
                                        <li class="text-sm ml-5">3) Carta de condução ou</li>
                                        <li class="text-sm ml-5">4) Passaporte ou DIRE, para estrangeiros</li>
                                        <li class="text-sm">Curriculum Vitae resumido em no máximo 1 página (contendo: <b>Dados Biográficos, Académicos e Profissionais</b>).</li>
                                    </ul>
                                    <p class="mt-2 mb-2">
                                        <b>NOTA:</b> submeta no <b>formato PDF (único ficheiro)</b> com o seguinte nome: <b>NrCandidato_Nome (ex.10001_Antonio_Pedro)</b>
                                    </p>
                                    <p class="mt-5 text-center">
                                        <a href="https://abrir.link/Hgeuf" class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300">
                                            @if($payment && $payment->status == 1)
                                            <x-ts-button xs color="blue">SUBMETER DOCUMENTOS</x-ts-button>
                                            @else
                                            <x-ts-button xs color="blue" disabled>SUBMETER DOCUMENTOS</x-ts-button>
                                            @endif
                                        </a>
                                        <span class="text-red-600"></span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </section>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Detalhes -->
    <x-ts-slide wire="detailsForm" size="5xl" style="display: none !important;">
        <x-slot name="title">
            <h2 class="text-2xl font-bold mb-4 text-gray-800 dark:text-gray-100">Detalhes da Candidatura</h2>
        </x-slot>

        <div class="max-w-4xl mx-auto px-4 sm:px-6">
            <div x-data="{ activeTab: 'personal' }">
                <!-- Abas - Layout Mobile -->
                <div class="md:hidden mb-6">
                    <select
                        x-model="activeTab"
                        class="w-full p-2 border border-gray-200 rounded-md bg-white dark:bg-gray-800 dark:border-gray-700 
                               text-gray-700 dark:text-gray-200 shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 
                               focus:border-indigo-500 transition-colors duration-200">
                        <option value="personal">Dados Pessoais</option>
                        <option value="contact">Documentação</option>
                        <option value="academic">Candidatura</option>
                    </select>
                </div>

                <!-- Abas - Layout Desktop -->
                <div class="hidden md:block bg-gray-50 dark:bg-gray-800 rounded-lg mb-6 border border-gray-200 dark:border-gray-700">
                    <ul class="flex flex-wrap gap-2 p-2">
                        <li class="flex-1 sm:flex-none">
                            <a
                                href="#personal"
                                @click.prevent="activeTab = 'personal'"
                                :class="{ 
                                    'bg-white dark:bg-gray-700 text-indigo-600 dark:text-indigo-400 shadow-sm border-gray-200 dark:border-gray-600': activeTab === 'personal',
                                    'bg-transparent text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700': activeTab !== 'personal'
                                }"
                                class="px-4 py-2.5 rounded-md transition duration-200 ease-in-out inline-block text-sm font-medium w-full sm:w-auto text-center">
                                Dados Pessoais
                            </a>
                        </li>
                        <li class="flex-1 sm:flex-none">
                            <a
                                href="#contact"
                                @click.prevent="activeTab = 'contact'"
                                :class="{ 
                                    'bg-white dark:bg-gray-700 text-indigo-600 dark:text-indigo-400 shadow-sm border-gray-200 dark:border-gray-600': activeTab === 'contact',
                                    'bg-transparent text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700': activeTab !== 'contact'
                                }"
                                class="px-4 py-2.5 rounded-md transition duration-200 ease-in-out inline-block text-sm font-medium w-full sm:w-auto text-center">
                                Documentação
                            </a>
                        </li>
                        <li class="flex-1 sm:flex-none">
                            <a
                                href="#academic"
                                @click.prevent="activeTab = 'academic'"
                                :class="{ 
                                    'bg-white dark:bg-gray-700 text-indigo-600 dark:text-indigo-400 shadow-sm border-gray-200 dark:border-gray-600': activeTab === 'academic',
                                    'bg-transparent text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700': activeTab !== 'academic'
                                }"
                                class="px-4 py-2.5 rounded-md transition duration-200 ease-in-out inline-block text-sm font-medium w-full sm:w-auto text-center">
                                Candidatura
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Conteúdo das Abas -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                    <!-- Dados Pessoais -->
                    <div x-show="activeTab === 'personal'" class="p-6">
                        <h3 class="text-lg font-semibold mb-6 text-gray-800 dark:text-gray-100 pb-2 border-b border-gray-200 dark:border-gray-700">
                            Dados Pessoais
                        </h3>
                        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nome Completo</dt>
                                <dd class="text-sm text-gray-800 dark:text-gray-100">{{ Auth::user()->name }}</dd>
                            </div>
                            <div class="space-y-2">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Data de Nascimento</dt>
                                <dd class="text-sm text-gray-800 dark:text-gray-100">{{ Auth::user()->candidate->birthdate ?? '-' }}</dd>
                            </div>
                            <div class="space-y-2">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nacionalidade</dt>
                                <dd class="text-sm text-gray-800 dark:text-gray-100">{{ Auth::user()->candidate->nationality ?? '-' }}</dd>
                            </div>
                            <div class="space-y-2">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Gênero</dt>
                                <dd class="text-sm text-gray-800 dark:text-gray-100">{{ Auth::user()->candidate->gender ?? '-' }}</dd>
                            </div>
                            <div class="space-y-2">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Estado Civil</dt>
                                <dd class="text-sm text-gray-800 dark:text-gray-100">{{ Auth::user()->candidate->marital_status ?? '-' }}</dd>
                            </div>
                            <div class="space-y-2">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Residência</dt>
                                <dd class="text-sm text-gray-800 dark:text-gray-100">{{ Auth::user()->candidate->province->name ?? '-' }}</dd>
                            </div>
                        </dl>
                    </div>

                    <!-- Contacto e Documentação -->
                    <div x-show="activeTab === 'contact'" class="p-6">
                        <h3 class="text-lg font-semibold mb-6 text-gray-800 dark:text-gray-100 pb-2 border-b border-gray-200 dark:border-gray-700">
                            Contactos
                        </h3>
                        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Telefone Principal</dt>
                                <dd class="text-sm text-gray-800 dark:text-gray-100">{{ Auth::user()->candidate->cell1 ?? '-' }}</dd>
                            </div>
                            <div class="space-y-2">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Telefone Alternativo</dt>
                                <dd class="text-sm text-gray-800 dark:text-gray-100">{{ Auth::user()->candidate->cell2 ?: '-' }}</dd>
                            </div>
                            <div class="space-y-2">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">E-mail</dt>
                                <dd class="text-sm text-gray-800 dark:text-gray-100">{{ Auth::user()->candidate->email ?: '-' }}</dd>
                            </div>
                            <div class="space-y-2">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tipo de Documento</dt>
                                <dd class="text-sm text-gray-800 dark:text-gray-100">{{ strtoupper(Auth::user()->candidate->document_type  ?? '-') }}</dd>
                            </div>
                            <div class="space-y-2">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Número do Documento</dt>
                                <dd class="text-sm text-gray-800 dark:text-gray-100">{{ Auth::user()->candidate->document_number  ?? '-' }}</dd>
                            </div>
                            <div class="space-y-2">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">NUIT</dt>
                                <dd class="text-sm text-gray-800 dark:text-gray-100">{{ Auth::user()->candidate->nuit ?: '-' }}</dd>
                            </div>
                        </dl>
                    </div>

                    <!-- Dados Acadêmicos -->
                    <div x-show="activeTab === 'academic'" class="p-6">
                        <h3 class="text-lg font-semibold mb-6 text-gray-800 dark:text-gray-100 pb-2 border-b border-gray-200 dark:border-gray-700">
                            Candidatura
                        </h3>
                        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tipo de Ensino Pré-Universitário</dt>
                                <dd class="text-sm text-gray-800 dark:text-gray-100">{{ Auth::user()->candidate->pre_university_type ?? '-' }} ou Equivalente</dd>
                            </div>
                            <div class="space-y-2">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Ano de Conclusão</dt>
                                <dd class="text-sm text-gray-800 dark:text-gray-100">{{ Auth::user()->candidate->pre_university_year ?? '-' }}</dd>
                            </div>
                            <div class="space-y-2">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Local a que se candidata</dt>
                                <dd class="text-sm text-gray-800 dark:text-gray-100">{{ strtoupper(Auth::user()->candidate->university->name ?? '-') }}</dd>
                            </div>
                            <div class="space-y-2">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Regime</dt>
                                <dd class="text-sm text-gray-800 dark:text-gray-100">{{ strtoupper(Auth::user()->candidate->regime->name ?? '-') }}</dd>
                            </div>
                            <div class="space-y-2">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Curso</dt>
                                <dd class="text-sm text-gray-800 dark:text-gray-100">{{ strtoupper(Auth::user()->candidate->course->name ?? '-') }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </x-ts-slide>

    <!-- Slide de edição -->

    <x-ts-slide wire="editForm" size="5xl">
        @if ($success == 0)
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6">
                        <div class="bg-white shadow rounded-lg mb-6">
                            <div class="px-6 py-4 border-b border-gray-200">
                                <h5 class="text-blue-600 font-semibold">Editar Dados da Pré-Inscrição</h5>
                            </div>
                            <div class="px-6 py-4 text-black">
                                <ul class="list-disc pl-5 space-y-2">
                                    <li class="text-sm">Atualize apenas os campos que deseja modificar</li>
                                    <li class="text-sm">Mantenha os dados verdadeiros pois estes serão exigidos para o acesso à sala de exame</li>
                                </ul>
                            </div>
                        </div>

                        <div class="mt-3">
                            <x-ts-step wire:model="currentStep" panels class="bg-blue-600 rounded-lg shadow-md">
                                <!-- Step 1: Dados Pessoais -->
                                <x-ts-step.items step="1" title="Identificação do Candidato" description="Dados Pessoais">
                                    <div class="p-4">
                                        <div class="grid lg:grid-cols-3 gap-6">
                                            <x-ts-input wire:model="surname" label="Apelido *" />
                                            <x-ts-input wire:model="name" label="Outros nomes *" />
                                            <x-ts-date wire:model="birthdate" format="DD [de] MMMM [de] YYYY" label="Data de nascimento" />
                                        </div>

                                        <div class="grid lg:grid-cols-3 gap-6 mt-3">
                                            <x-ts-select.styled
                                                wire:model="nationality"
                                                label="Nacionalidade *"
                                                :options="$nationalityOptions"
                                                select="label:label|value:value"
                                                searchable />

                                            <x-ts-select.styled
                                                wire:model="gender"
                                                label="Gênero *"
                                                :options="$genderOptions"
                                                select="label:label|value:value"
                                                searchable />

                                            <x-ts-select.styled
                                                wire:model="marital_status"
                                                label="Estado Civil *"
                                                :options="$maritalStatusOptions"
                                                select="label:label|value:value"
                                                searchable />
                                        </div>

                                        <div class="grid lg:grid-cols-3 gap-6 mt-3">
                                            <x-ts-select.styled
                                                wire:model="province_id"
                                                label="Província de Residência *"
                                                :options="$provinces"
                                                select="name:label|id:value"
                                                searchable />
                                            <x-ts-select.styled
                                                wire:model="special_need_id"
                                                label="Tem alguma Necessidades Educativas Especiais? *"
                                                :options="$specialNeeds"
                                                select="name:label|id:value"
                                                searchable />
                                            <x-ts-select.styled
                                                wire:model="document_type"
                                                label="Tipo de Documento *"
                                                :options="$documentTypeOptions"
                                                select="label:label|value:value"
                                                searchable />
                                        </div>

                                        <div class="grid lg:grid-cols-3 gap-6 mt-3">
                                            <x-ts-input wire:model="document_number" label="Numero de Documento *" />
                                            <x-ts-input wire:model="nuit" label="NUIT *" />
                                            <x-ts-input wire:model="cell1" label="Nr Telefone Principal *" />
                                        </div>

                                        <div class="grid lg:grid-cols-3 gap-6 mt-3">
                                            <x-ts-input wire:model="cell2" label="Nr Telefone Alternativo" />
                                            <x-ts-input wire:model="email" label="E-mail" />
                                        </div>
                                    </div>
                                </x-ts-step.items>

                                <!-- Step 2: Dados da Candidatura -->
                                <x-ts-step.items step="2" title="Dados da candidatura" description="Informações Acadêmicas">
                                    <div class="p-4">
                                        <div class="grid lg:grid-cols-3 gap-6">
                                            <x-ts-select.styled
                                                wire:model="pre_university_type"
                                                label="Tipo de Ensino Pré-Universitário *"
                                                :options="[
                                                    ['label' => '12ª - Grupo A (ou equivalente)', 'value' => '12ª - Grupo A'],
                                                    ['label' => '12ª - Grupo B (ou equivalente)', 'value' => '12ª - Grupo B'],
                                                    ['label' => '12ª - Grupo C (ou equivalente)', 'value' => '12ª - Grupo C'],
                                                ]"
                                                select="label:label|value:value" />
                                            <x-ts-input
                                                x-mask="9999"
                                                wire:model="pre_university_year"
                                                label="Ano da Conclusão do ensino Pré-Universitário:*" />

                                            <div>
                                                <label class="text-gray-800 text-sm font-medium inline-block">Local a que se candidata</label>
                                                <select
                                                    wire:model.live="university_id"
                                                    class="block w-full p-2 border rounded-md bg-white dark:bg-gray-800 text-black dark:text-white">
                                                    <option value="">Selecione</option>
                                                    @foreach($query3 as $id => $name)
                                                    <option value="{{ $id }}">{{ $name }}</option>
                                                    @endforeach
                                                </select>
                                                @error('university_id')<p class="text-red-600">{{ $message }}</p>@enderror
                                            </div>

                                            <div>
                                                <label class="text-gray-800 text-sm font-medium inline-block">Regime a que se candidata</label>
                                                <select
                                                    wire:model.live="regime_id"
                                                    class="block w-full p-2 border rounded-md bg-white dark:bg-gray-800 text-black dark:text-white"
                                                    @if(!$university_id) disabled @endif>
                                                    <option value="">Selecione</option>
                                                    @foreach($query4 as $id => $name)
                                                    <option value="{{ $id }}">{{ $name }}</option>
                                                    @endforeach
                                                </select>
                                                @error('regime_id')<p class="text-red-600">{{ $message }}</p>@enderror
                                            </div>

                                            <x-ts-select.styled
                                                wire:model.live="course_id"
                                                label="Curso a que se candidata *"
                                                :options="$availableCourses"
                                                select="label:label|value:value"
                                                searchable
                                                :disabled="empty($availableCourses)" />

                                            @if($showLocalExam)
                                            <x-ts-select.styled
                                                <x-ts-select.styled
                                                wire:model.live="local_exam"
                                                label="Local onde irá Realizar os Exames *"
                                                :options="$provinces"
                                                select="name:label|id:value"
                                                searchable />
                                            @endif
                                        </div>
                                    </div>
                                </x-ts-step.items>
                            </x-ts-step>

                            <!-- Navigation Buttons -->
                            <div class="mt-2 p-6 flex justify-between items-center">
                                @if($currentStep > 1)
                                <button
                                    wire:click="setAndDecrementStep"
                                    wire:loading.attr="disabled"
                                    wire:target="setAndDecrementStep"
                                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-yellow-600 disabled:opacity-50 disabled:cursor-not-allowed">
                                    <span wire:loading.remove wire:target="setAndDecrementStep">Anterior</span>
                                    <span wire:loading wire:target="setAndDecrementStep">..</span>
                                </button>
                                @else
                                <div></div> <!-- Espaço vazio para manter o alinhamento -->
                                @endif

                                @if($currentStep < 2)
                                    <button
                                    wire:click="setAndIncrementStep"
                                    wire:loading.attr="disabled"
                                    wire:target="setAndIncrementStep"
                                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-yellow-600 disabled:opacity-50 disabled:cursor-not-allowed">
                                    <span wire:loading.remove wire:target="setAndIncrementStep">Próximo</span>
                                    <span wire:loading wire:target="setAndIncrementStep">...</span>
                                    </button>
                                    @else
                                    <button
                                        wire:click="submit"
                                        type="submit"
                                        wire:loading.attr="disabled"
                                        wire:target="submit"
                                        class="px-4 py-2 bg-yellow-600 text-white rounded-md hover:bg-blue-600 disabled:opacity-50 disabled:cursor-not-allowed">
                                        <span wire:loading.remove wire:target="submit">Finalizar</span>
                                        <span wire:loading wire:target="submit">...</span>
                                    </button>
                                    @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
        @if ($success == true)
        <div class="max-w-2xl mx-auto p-8 text-center">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden p-8">
                <!-- Success Icon -->
                <div class="mb-6">
                    <div class="mx-auto w-16 h-16 bg-green-100 rounded-full flex items-center justify-center">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                </div>

                <!-- Success Message -->
                <h3 class="text-2xl font-bold text-green-600 mb-4">
                    Edição Concluída com Sucesso!
                </h3>

                <div class="space-y-3 text-gray-600 mb-8">
                    <p>Dados da candidatura atualizados com sucesso!..</p>
                    <p>Baixe o Guião de pagamento Actualizado</p>
                </div>

                <!-- Action Button -->
                <div class="mt-8">
                    <a href="{{ route('enrollment.download') }}"
                        class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors duration-200 ease-in-out shadow-sm hover:shadow focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <span>Guião de Pagamento Actualizado</span>
                        <svg class="ml-2 -mr-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
        @endif
    </x-ts-slide>
</div>