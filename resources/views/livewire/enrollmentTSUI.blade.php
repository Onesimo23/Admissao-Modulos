<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
		

            <div class="p-6">
				@if ($success == 0)
				
				<div class="bg-white shadow rounded-lg">
					<div class="px-6 py-4 border-b border-gray-200">
						<h5 class="text-blue-600 font-semibold text-blue-700">Instruções para a Pré-Inscrição !</h5>
					</div>
					<div class="px-6 py-4 text-black">
						<ul class="list-disc pl-5 space-y-2">
							<li class="text-sm">Preencha com atenção todos os campos obrigatórios</li>
							<li class="text-sm">Introduza dados verdadeiros pois estes serão exigidos para o acesso à sala de exame.</li>
							<li class="text-sm">No fim do processo de registo, ser-lhe-á indicado um Usuário e Senha para poder entrar no Sistema onde poderá acompanhar o estado da sua Inscrição</li>
						</ul>
					</div>
				</div>				
						
                <div class="mt-3">
                    <x-ts-step wire:model="step" panels class="bg-blue-600 rounded-lg shadow-md">
					@if($currentStep===1)
						<x-ts-step.items step="1" title="Identificação do Candidato" description="Dados Pessoais">
					
                            <div class="p-4">
                                        <div class="grid lg:grid-cols-3 gap-6">
                                            <x-ts-input wire:model="surname" label="Apelido *" />
                                            <x-ts-input wire:model="name" label="Outros nomes *" />
                                            <x-ts-date wire:model="birthdate" label="Data de nascimento *" />
                                        </div>
                                        <div class="grid lg:grid-cols-3 gap-6 mt-3">
                                            <x-ts-select.styled wire:model="nationality" label="Nacionalidade *" :options="[
                                                ['label' => 'Moçambicana', 'value' => 'Moçambicana'],
                                                ['label' => 'Estrangeira', 'value' => 'Estrangeira'],
                                            ]" select="label:label|value:value" searchable />
                                            <x-ts-select.styled wire:model="gender" label="Gênero *" :options="[
                                                ['label' => 'Masculino', 'value' => 'Masculino'],
                                                ['label' => 'Feminino', 'value' => 'Feminino'],
                                            ]" select="label:label|value:value" searchable />
                                            <x-ts-select.styled wire:model="marital_status" label="Estado Civil *" :options="[
                                                ['label' => 'Solteiro(a)', 'value' => 'Solteiro(a)'],
                                                ['label' => 'Casado(a)', 'value' => 'Casado(a)'],
                                                ['label' => 'Viúvo(a)', 'value' => 'Viúvo(a)'],
                                                ['label' => 'Divorciado(a)', 'value' => 'Divorciado(a)'],
                                            ]" select="label:label|value:value" searchable />
                                        </div>
                                        <div class="grid lg:grid-cols-3 gap-6 mt-3">
                                            <x-ts-select.styled wire:model="province_id" label="Província de Residência *" :options="$provinces" select="name:label|id:value" searchable />
                                            <x-ts-select.styled wire:model="special_need_id" label="Tem alguma Necessidades Educativas Especiais? *" :options="$specialNeeds" select="name:label|id:value" searchable />
                                            <x-ts-select.styled wire:model="document_type" label="Tipo de Documento *" :options="[
                                                ['label' => 'BI', 'value' => 'BI'],
                                                ['label' => 'Passaporte', 'value' => 'Passaporte'],
                                            ]" select="label:label|value:value" searchable />											
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
					@endif
                    @if($currentStep===2)
                        <x-ts-step.items step="2" title="Dados da candidatura" description="Informações Acadêmicas">
                            <div class="p-4">
								<div class="grid lg:grid-cols-3 gap-6 mt-3">
									<!-- Select para o tipo de ensino pré-universitário -->
									<x-ts-select.styled 
										wire:model="pre_university_type" 
										label="Tipo de Ensino Pré-Universitário *" 
										:options="[
											['label' => '12ª - Grupo A (ou equivalente)', 'value' => '12ª - Grupo A'],
											['label' => '12ª - Grupo B (ou equivalente)', 'value' => '12ª - Grupo B'],
										]" 
										select="label:label|value:value" 
										searchable 
									/>
									<!-- Input para o ano de conclusão do ensino pré-universitário -->
									<x-ts-input wire:model="pre_university_year" label="Ano da Conclusão do ensino Pré-Universitário:*"/>

									<!-- Select para a universidade -->
									<x-ts-select.styled 
										wire:model.live="university_id" 
										label="Local a que se candidata *" 
										:options="$universities" 
										select="name:label|id:value" 
										searchable 
									/>
								</div>

								<div class="grid lg:grid-cols-3 gap-6 mt-3">
									<!-- Select para o regime -->
									<x-ts-select.styled 
										wire:model.live="regime_id" 
										label="Regime a que se candidata *" 
										:options="$availableRegimes" 
										select="label:label|value:value" 
										searchable 
										:disabled="empty($availableRegimes)"
									/>

									<!-- Select para o curso -->
									<x-ts-select.styled 
										wire:model.live="course_id" 
										label="Curso a que se candidata *" 
										:options="$availableCourses" 
										select="label:label|value:value" 
										searchable 
										:disabled="empty($availableCourses)"
									/>

									<!-- Select para o local de exame, se aplicável -->
									@if($this->showLocalExam)
										<x-ts-select.styled 
											wire:model.live="local_exam" 
											label="Local onde irá Realizar os Exames *" 
											:options="$provinces" 
											select="name:label|id:value" 
											searchable 
										/>
									@endif
								</div>

                            </div>
						</x-ts-step.items>													
					@endif	
                    @if($currentStep===3)
                        <x-ts-step.items step="3" title="Confirmação dos Dados" description="Revisão">
						
							<div class="p-0">
								<h3 class="text-xl font-semibold text-blue-600 mb-4 text-center">Confirmação dos Dados</h3>
								<p class="mb-4 text-center">Por favor, revise cuidadosamente todos os dados fornecidos antes de Finalizar.</p>

							<!-- Grid Responsivo para os blocos de informações -->
							<div class="max-w-7xl mx-auto p-6 space-y-6">
								<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
									<!-- Personal Information Card -->
									<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
										<div class="border-b border-gray-100 p-6">
											<div class="flex items-center gap-3">
												<div class="h-10 w-1 bg-blue-600 rounded-full"></div>
												<h2 class="text-xl font-semibold text-gray-800">Dados Pessoais</h2>
											</div>
										</div>
										
										<div class="p-6">
											<dl class="grid gap-1">
												<div class="grid grid-cols-2 items-center hover:bg-gray-50 p-2 rounded-lg transition-colors">
													<dt class="text-sm font-semibold text-gray-600">Nome completo</dt>
													<dd class="text-sm text-gray-900">{{ $name }} {{ $surname }}</dd>
												</div>
												
												<div class="grid grid-cols-2 items-center hover:bg-gray-50 p-2 rounded-lg transition-colors">
													<dt class="text-sm font-semibold text-gray-600">Data de nascimento</dt>
													<dd class="text-sm text-gray-900">{{ $birthdate }}</dd>
												</div>
												
												<div class="grid grid-cols-2 items-center hover:bg-gray-50 p-2 rounded-lg transition-colors">
													<dt class="text-sm font-semibold text-gray-600">Nacionalidade</dt>
													<dd class="text-sm text-gray-900">{{ $nationality }}</dd>
												</div>
												
												<div class="grid grid-cols-2 items-center hover:bg-gray-50 p-2 rounded-lg transition-colors">
													<dt class="text-sm font-semibold text-gray-600">Gênero</dt>
													<dd class="text-sm text-gray-900">{{ $gender }}</dd>
												</div>
												
												<div class="grid grid-cols-2 items-center hover:bg-gray-50 p-2 rounded-lg transition-colors">
													<dt class="text-sm font-semibold text-gray-600">Estado Civil</dt>
													<dd class="text-sm text-gray-900">{{ $marital_status }}</dd>
												</div>
												
												<div class="grid grid-cols-2 items-center hover:bg-gray-50 p-2 rounded-lg transition-colors">
													<dt class="text-sm font-semibold text-gray-600">Província</dt>
													<dd class="text-sm text-gray-900">{{ $province_name }}</dd>
												</div>
												
												<div class="grid grid-cols-2 items-center hover:bg-gray-50 p-2 rounded-lg transition-colors">
													<dt class="text-sm font-semibold text-gray-600">Necessidades Especiais</dt>
													<dd class="text-sm text-gray-900">{{ $special_need_name }}</dd>
												</div>
												
												<div class="grid grid-cols-2 items-center hover:bg-gray-50 p-2 rounded-lg transition-colors">
													<dt class="text-sm font-semibold text-gray-600">Documento</dt>
													<dd class="text-sm text-gray-900">{{ $document_type }} - {{ $document_number }}</dd>
												</div>
												
												<div class="grid grid-cols-2 items-center hover:bg-gray-50 p-2 rounded-lg transition-colors">
													<dt class="text-sm font-semibold text-gray-600">NUIT</dt>
													<dd class="text-sm text-gray-900">{{ $nuit }}</dd>
												</div>
												
												<div class="grid grid-cols-2 items-center hover:bg-gray-50 p-2 rounded-lg transition-colors">
													<dt class="text-sm font-semibold text-gray-600">Telefone</dt>
													<dd class="text-sm text-gray-900">{{ $cell1 }}</dd>
												</div>
												
												<div class="grid grid-cols-2 items-center hover:bg-gray-50 p-2 rounded-lg transition-colors">
													<dt class="text-sm font-semibold text-gray-600">E-mail</dt>
													<dd class="text-sm text-gray-900">{{ $email }}</dd>
												</div>
											</dl>
										</div>
									</div>

									<!-- Application Information Card -->
									<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
										<div class="border-b border-gray-100 p-6">
											<div class="flex items-center gap-3">
												<div class="h-10 w-1 bg-blue-600 rounded-full"></div>
												<h2 class="text-xl font-semibold text-gray-800">Dados da Candidatura</h2>
											</div>
										</div>
										
										<div class="p-6">
											<dl class="grid gap-4">
												<div class="grid grid-cols-2 items-center hover:bg-gray-50 p-2 rounded-lg transition-colors">
													<dt class="text-sm font-semibold text-gray-600">Tipo de Ensino</dt>
													<dd class="text-sm text-gray-900">{{ $pre_university_type }}</dd>
												</div>
												
												<div class="grid grid-cols-2 items-center hover:bg-gray-50 p-2 rounded-lg transition-colors">
													<dt class="text-sm font-semibold text-gray-600">Ano de Conclusão</dt>
													<dd class="text-sm text-gray-900">{{ $pre_university_year }}</dd>
												</div>
												
												<div class="grid grid-cols-2 items-center hover:bg-gray-50 p-2 rounded-lg transition-colors">
													<dt class="text-sm font-semibold text-gray-600">Local do Exame</dt>
													<dd class="text-sm text-gray-900">{{ $local_exam_name }}</dd>
												</div>
												
												<div class="grid grid-cols-2 items-center hover:bg-gray-50 p-2 rounded-lg transition-colors">
													<dt class="text-sm font-semibold text-gray-600">Universidade</dt>
													<dd class="text-sm text-gray-900">{{ $university_name }}</dd>
												</div>
												
												<div class="grid grid-cols-2 items-center hover:bg-gray-50 p-2 rounded-lg transition-colors">
													<dt class="text-sm font-semibold text-gray-600">Regime</dt>
													<dd class="text-sm text-gray-900">{{ $regime_name }}</dd>
												</div>
												
												<div class="grid grid-cols-2 items-center hover:bg-gray-50 p-2 rounded-lg transition-colors">
													<dt class="text-sm font-semibold text-gray-600">Curso</dt>
													<dd class="text-sm text-gray-900">{{ $course_name }}</dd>
												</div>
											</dl>
										</div>
									</div>
								</div>
							</div>
							</div>
							</x-ts-step.items>
							@endif
			
							<div class="mt-2 p-6 flex justify-between items-center">					
								@if($currentStep > 1)
								<button wire:click="setAndDecrementStep"  class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-yellow-600">Anterior</button>
								@else
								<div></div> <!-- Espaço vazio para manter o alinhamento -->
								@endif
								
								@if($currentStep < $total_steps)
									<button  wire:click="setAndIncrementStep" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-yellow-600">Próximo</button>
								@else
									<button wire:click="submit" type ="submit" class="px-4 py-2 bg-yellow-600 text-white rounded-md hover:bg-blue-600">Finalizar</button>	
								@endif
							</div>
						
                    </x-ts-step>
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
									Inscrição Concluída com Sucesso!
								</h3>

								<div class="space-y-3 text-gray-600 mb-8">
									<p class="text-lg">
										Parabéns, <span class="font-semibold text-gray-900">{{ $name }} {{ $surname }}</span>
									</p>
									<p>A sua candidatura foi registada com sucesso...</p>
									<div class="bg-gray-50 p-4 rounded-lg inline-block mx-auto">
										<p>Seu Usuário|Nr de Candidato é 
											<span class="font-semibold text-gray-900">{{ $candidateNumber ?? '12345' }}</span>
										</p>
										<p>Senha: <span class="font-semibold text-gray-900">{{$cell1}}</span></p>
									</div>
									<p>Acesse ao sistema para obter a ficha de Inscrição contendo dados para o pagamento</p>
								</div>

								<!-- Action Button -->
								<div class="mt-8">
									<a href="" 
									   class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors duration-200 ease-in-out shadow-sm hover:shadow focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
										<span>Acessar o sistema</span>
										<svg class="ml-2 -mr-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
											<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
										</svg>
									</a>
								</div>
							</div>
						</div>
					@endif
                </div>
            </div>
        </div>
    </div>
</div>

