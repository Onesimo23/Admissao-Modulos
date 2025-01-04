<div x-data>
        <div class="py-4 sm:py-12">
            <div class="max-w-10xl mx-auto px-4 sm:px-10 lg:px-10">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-4">
                        <!-- Seção de Boas-vindas -->
                        <section class="mb-2">
                            <h5 class="font-bold  dark:text-gray-100 mb-2">
                                Bem-vindo, <span class="text-blue-600">{{ strtoupper(Auth::user()->name) }}</span> (Código do Candidato: <span class="text-blue-600"> {{ Auth::user()->candidate->id}}</span>)
                            </h5>
                        </section>


                        <!-- Seção de Progresso -->

                        @php
							$progresso = 50; // Valor padrão
							$mensagem = 'A sua candidatura já iniciou, Clique em "<span class="text-blue-800"><b>Guia de Pagamento</b></span>" para Baixar a Referencia de pagamento. Caso deseje alterar os dados da candidatura
							, clique em "<span class="text-blue-800"><b>Editar Dados</b></span>. Poderá actualizar os dados da candidatura, até a confirmação do pagamento"';
							$cor = 'red';

							if (Auth::user()->candidate && Auth::user()->candidate->payment && Auth::user()->candidate->payment->status == 0) {
							$progresso = 75;
							$mensagem = 'Seu Guia de pagamento foi gerado, Efectue o pagamento e  aguarde 48h pela confirmação no sistema.';
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
                        <section class="mb-2">
                            <div class="flex flex-col space-y-4">
                                <div class="w-full">
                                    <x-ts-progress :percent="$progresso" :color="$cor" floating lg/>
	                             </div>
                                <div class="w-full">
                                    <div class="bg-{{ $cor }}-100 border-l-4 border-{{ $cor }}-600 text-{{ $cor }}-700 p-4" role="alert">
                                        <p class="font-bold">Estado da Candidatura</p>
                                        <p>{!! $mensagem !!}</p>
                                    </div>
                                </div>
                            </div>
                        </section>

                        <!-- Seção de Próximos Passos -->

                        <!-- Seção de Detalhes da Candidatura com tabela responsiva -->
						<section class="mt-4">
							<h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
								Detalhes da Candidatura
							</h4>
							<!-- Card Container -->
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
												<x-ts-button xs @click="$slideOpen('basic-modal')" class="w-full justify-center">
													Detalhes
												</x-ts-button>
												@if($payment && $payment->status < 1)
												<x-ts-button xs @click="$slideOpen('basic-slide')" color="blue"  class="w-full justify-center">
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
														<x-ts-button xs @click="$slideOpen('basic-modal')">
															Detalhes
														</x-ts-button>
														@if($payment && $payment->status < 1)
														<x-ts-button xs @click="$slideOpen('basic-slide')" color="blue" >
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
						</section>
						@if(Auth::user()->candidate->regime_id >1)
                        <section class="mb-2">
                            <div class="flex flex-col space-y-4">
                                <div class="w-full">
	                             </div>
                                <div class="w-full">
                                    <div class="bg-gray-100 border-l-4 border-gray-600 text-gray-700 p-4" role="alert">
                                        <p class="font-bold">NOTA</p>
                                        <p>Após a confirmação do pagamento, submeta os documentos da candidatura clicando em : <a href="" class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300"><x-ts-button xs color="blue" disabled>Documentos da candidatura</x-ts-button></a> </p>
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
	<x-ts-slide id="basic-modal" size="5xl" style="display: none !important;">
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
							   focus:border-indigo-500 transition-colors duration-200"
					>
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
								<dd class="text-sm text-gray-800 dark:text-gray-100">{{ Auth::user()->candidate->marital_status ?? '-' }}</dd>
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

    <x-ts-slide id="basic-slide" size="5xl">
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
                                                :options="[ ['label' => 'Moçambicana', 'value' => 'Moçambicana'], ['label' => 'Estrangeira', 'value' => 'Estrangeira'] ]"
                                                select="label:label|value:value"
                                                searchable
                                                :selected="$nationality" />
                                            <x-ts-select.styled
                                                wire:model="gender"
                                                label="Gênero *"
                                                :options="[ ['label' => 'Masculino', 'value' => 'Masculino'], ['label' => 'Feminino', 'value' => 'Feminino'] ]"
                                                select="label:label|value:value"
                                                searchable
                                                :selected="$gender" />
                                            <x-ts-select.styled
                                                wire:model="marital_status"
                                                label="Estado Civil *"
                                                :options="[ ['label' => 'Solteiro(a)', 'value' => 'Solteiro(a)'], ['label' => 'Casado(a)', 'value' => 'Casado(a)'], ['label' => 'Viúvo(a)', 'value' => 'Viúvo(a)'], ['label' => 'Divorciado(a)', 'value' => 'Divorciado(a)'] ]"
                                                select="label:label|value:value"
                                                searchable
                                                :selected="$marital_status" />
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
                                                :options="[ ['label' => 'BI', 'value' => 'BI'], ['label' => 'Passaporte', 'value' => 'Passaporte'] ]"
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
                                                :options="[ ['label' => '12ª - Grupo A (ou equivalente)', 'value' => '12ª - Grupo A'], ['label' => '12ª - Grupo B (ou equivalente)', 'value' => '12ª - Grupo B'] ]"
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
                                                @error('university_id')<p class="text-danger">{{ $message }}</p>@enderror
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
                                                @error('regime_id')<p class="text-danger">{{ $message }}</p>@enderror
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
                                                wire:model.live="local_exam"
                                                label="Local onde irá Realizar os Exames *"
                                                :options="$provinces"
                                                select="name:label|name:label"
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
										class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-yellow-600 disabled:opacity-50 disabled:cursor-not-allowed"
									>
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
										class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-yellow-600 disabled:opacity-50 disabled:cursor-not-allowed"
									>
										<span wire:loading.remove wire:target="setAndIncrementStep">Próximo</span>
										<span wire:loading wire:target="setAndIncrementStep">...</span>
									</button>
								@else
									<button 
										wire:click="submit" 
										type="submit"
										wire:loading.attr="disabled"
										wire:target="submit"
										class="px-4 py-2 bg-yellow-600 text-white rounded-md hover:bg-blue-600 disabled:opacity-50 disabled:cursor-not-allowed"
									>
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
    </x-ts-slide>

</div>