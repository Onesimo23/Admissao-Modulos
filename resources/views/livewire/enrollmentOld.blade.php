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
                                        </div>
                                        <div class="grid lg:grid-cols-3 gap-6 mt-3">
                                            <x-ts-select.styled wire:model="document_type" label="Tipo de Documento *" :options="[
                                                ['label' => 'BI', 'value' => 'BI'],
                                                ['label' => 'Passaporte', 'value' => 'Passaporte'],
                                            ]" select="label:label|value:value" searchable />
                                            <x-ts-input wire:model="document_number" label="Numero de Documento *" />
                                            <x-ts-input wire:model="nuit" label="NUIT *" />
                                        </div>
                                        <div class="grid lg:grid-cols-3 gap-6 mt-3">
                                            <x-ts-input wire:model="cell1" label="Nr Telefone Principal *" />
                                            <x-ts-input wire:model="cell2" label="Nr Telefone Alternativo" />
                                            <x-ts-input wire:model="email" label="E-mail *" />
                                        </div>

                            </div>					
							
                        </x-ts-step.items>
						@endif
                        @if($currentStep===2)
                        <x-ts-step.items step="2" title="Dados da candidatura" description="Informações Acadêmicas">
						
                            <div class="p-4">
                                    <div class="grid lg:grid-cols-3 gap-6 mt-3">
                                        <x-ts-select.styled wire:model="pre_university_type" label="Tipo de Ensino Pré-Universitário *" :options="[
                                            ['label' => '12ª - Grupo A (ou equivalente)', 'value' => '12ª - Grupo A'],
                                            ['label' => '12ª - Grupo B (ou equivalente)', 'value' => '12ª - Grupo B'],
                                        ]" select="label:label|value:value" searchable />
                                        <x-ts-select.styled wire:model="pre_university_province_id" label="Província do ensino Pré-Universitário *" :options="$provinces" select="name:label|id:value" searchable />
                                        <x-ts-select.styled wire:model="pre_university_school_id" label="Escola do ensino Pré-Universitário *" :options="$preUniversitySchools" select="name:label|id:value" searchable />
                                    </div>
                                    <div class="grid lg:grid-cols-3 gap-6 mt-3">
                                        <x-ts-input wire:model="pre_university_year" label="Ano da Conclusão do ensino Pré-Universitário:*"/>
                                        <x-ts-input wire:model="local_exam" label="Local onde irá Realizar os Exames *"/>
                                        <x-ts-select.styled wire:model="university_id" label="Universidade a que se candidata *" :options="$universities" select="name:label|id:value" searchable />
                                    </div>
                                    <div class="grid lg:grid-cols-3 gap-6 mt-3">
                                        <x-ts-select.styled wire:model="regime" label="Regime a que se candidata *" :options="[
                                            ['label' => 'Laboral', 'value' => 'Laboral'],
                                            ['label' => 'Pós-Laboral', 'value' => 'Pós-Laboral'],
                                        ]" select="label:label|value:value" searchable />
                                        <x-ts-select.styled wire:model="course_id" label="Curso a que se candidata *" :options="$courses" select="name:label|id:value" searchable />
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
						<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
							<!-- Bloco de Dados Pessoais -->
							<div class="bg-white p-0 rounded-lg shadow-md border border-secondary">
								<h4 class="font-semibold mb-4 text-xl text-blue-600">Dados Pessoais:</h4>
								<dl class="space-y-2">
									<div class="flex">
										<dt class="w-1/2 font-semibold text-right pr-4">Nome completo:</dt>
										<dd class="w-1/2">{{ $name }} {{ $surname }} </dd>
									</div>
									<div class="flex">
										<dt class="w-1/2 font-semibold text-right pr-4">Data de nascimento:</dt>
										<dd class="w-1/2">{{ $birthdate }}</dd>
									</div>
									<div class="flex">
										<dt class="w-1/2 font-semibold text-right pr-4">Nacionalidade:</dt>
										<dd class="w-1/2">{{ $nationality }}</dd>
									</div>
									<div class="flex">
										<dt class="w-1/2 font-semibold text-right pr-4">Gênero:</dt>
										<dd class="w-1/2">{{ $gender }}</dd>
									</div>
									<div class="flex">
										<dt class="w-1/2 font-semibold text-right pr-4">Estado Civil:</dt>
										<dd class="w-1/2">{{ $marital_status }}</dd>
									</div>
									<div class="flex">
										<dt class="w-1/2 font-semibold text-right pr-4">Província:</dt>
										<dd class="w-1/2">{{ $province_id }}</dd>
									</div>
									<div class="flex">
										<dt class="w-1/2 font-semibold text-right pr-4">Cidade/Distrito:</dt>
										<dd class="w-1/2">{{ $province_district_id }}</dd>
									</div>
									<div class="flex">
										<dt class="w-1/2 font-semibold text-right pr-4">Necessidades Especiais:</dt>
										<dd class="w-1/2">{{ $special_need_id }}</dd>
									</div>
									<div class="flex">
										<dt class="w-1/2 font-semibold text-right pr-4">Documento:</dt>
										<dd class="w-1/2">{{ $document_type }} - {{ $document_number }}</dd>
									</div>
									<div class="flex">
										<dt class="w-1/2 font-semibold text-right pr-4">NUIT:</dt>
										<dd class="w-1/2">{{ $nuit }}</dd>
									</div>
									<div class="flex">
										<dt class="w-1/2 font-semibold text-right pr-4">Telefone:</dt>
										<dd class="w-1/2">{{ $cell1 }}</dd>
									</div>
									<div class="flex">
										<dt class="w-1/2 font-semibold text-right pr-4">E-mail:</dt>
										<dd class="w-1/2">{{ $email }}</dd>
									</div>
								</dl>
							</div>
							<!-- Bloco de Dados da Candidatura -->
							<div class="bg-white p-0 rounded-lg shadow-md border border-secondary">
								<h4 class="font-semibold mb-4 text-xl text-blue-600">Dados da Candidatura:</h4>
								<dl class="space-y-2">
									<div class="flex">
										<dt class="w-1/2 font-semibold text-right pr-4">Tipo de Ensino:</dt>
										<dd class="w-1/2">{{ $pre_university_type }}</dd>
									</div>
									<div class="flex">
										<dt class="w-1/2 font-semibold text-right pr-4">Província:</dt>
										<dd class="w-1/2">{{ $pre_university_province_id }}</dd>
									</div>
									<div class="flex">
										<dt class="w-1/2 font-semibold text-right pr-4">Escola:</dt>
										<dd class="w-1/2">{{ $pre_university_school_id }}</dd>
									</div>
									<div class="flex">
										<dt class="w-1/2 font-semibold text-right pr-4">Ano de Conclusão:</dt>
										<dd class="w-1/2">{{ $pre_university_year }}</dd>
									</div>
									<div class="flex">
										<dt class="w-1/2 font-semibold text-right pr-4">Local do Exame:</dt>
										<dd class="w-1/2">{{ $local_exam }}</dd>
									</div>
									<div class="flex">
										<dt class="w-1/2 font-semibold text-right pr-4">Universidade:</dt>
										<dd class="w-1/2">{{ $university_id }}</dd>
									</div>
									<div class="flex">
										<dt class="w-1/2 font-semibold text-right pr-4">Regime:</dt>
										<dd class="w-1/2">{{ $regime }}</dd>
									</div>
									<div class="flex">
										<dt class="w-1/2 font-semibold text-right pr-4">Curso:</dt>
										<dd class="w-1/2">{{ $course_id }}</dd>
									</div>
								</dl>
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
                            <div class="p-6 text-center">

								<h3 class="text-2xl font-bold text-green-700 mb-4">Inscrição Concluída com Sucesso!</h3>
                                <h5>Parabéns, <strong>{{ $name }} {{ $surname }}</strong></h5>
                                <h5>A sua candidatura foi registada com sucesso...</h5>
                                <h5>Seu Usuário|Nr de Candidato é <strong>{{ $candidateNumber ?? '12345' }}</strong> e a senha é <strong>{{$cell1}}</strong>.</h5>
                                <h5>Acesse ao sistema para obter a ficha de Inscrição contendo dados para o pagamento</h5>								
								
								<div class="mt-5">
								<a href="" class="bg-blue-600">Acessar o sistema</a>
								</div>							
                            </div>
					@endif
                </div>
            </div>
        </div>
    </div>
</div>

