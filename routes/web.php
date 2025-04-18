<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PaymentUploadController;
use App\Http\Controllers\EnrollmentController;
use App\Livewire\Profile;
use App\Livewire\Enrollment;
use App\Livewire\CandidateDashboard;
use App\Http\Controllers\PasswordResetController;
use App\Livewire\Livewire\Reset;
use App\Livewire\CandidateIdRecovery;
use App\Livewire\PaymentGuideLookup;
use App\Livewire\RegistrationStatusLookup;
use App\Livewire\Schools;
use App\Livewire\Rooms;
use App\Livewire\Monitors;
use App\Livewire\JuryDistribution;
use App\Livewire\Disciplines;
use App\Livewire\SearchCandidateJury;
use App\Livewire\Admin\Dashboard;
use App\Models\Candidate;
use App\Livewire\Admin\Gestao;

Route::middleware('guest')->group(function () {
	Route::view('/', 'index')->name('index');
});
Route::view('/', 'index')->name('index');
Route::get('/pre-inscricao', Enrollment\Index::class)->name('enrollment');
Route::get('/recuperar', CandidateIdRecovery::class)->name('recover-id');
Route::get('/redifinicao', Reset::class)->name('reset');
Route::get('/guia-pagamento', PaymentGuideLookup::class)->name('payment-guide');
Route::get('/estado-inscricao', RegistrationStatusLookup::class)->name('registration-status');
Route::get('/upload-payments', [PaymentUploadController::class, 'showUploadForm'])->name('upload.form');
Route::post('/upload-payments', [PaymentUploadController::class, 'uploadAndVerifyPayments'])->name('upload.payments');
Route::get('/consulta', SearchCandidateJury::class)->name('search.candidate.jury');
Route::middleware(['auth', 'verified'])->group(function () {
	Route::get('/painel', CandidateDashboard::class)->name('dashboard');
	Route::put('/enrollment/update', [EnrollmentController::class, 'update'])->name('enrollment.update');
	Route::get('/generate-reference', [PaymentController::class, 'createReference']);
	Route::get('enrollment/download', [PaymentController::class, 'downloadReference'])->name('enrollment.download');
	Route::get('enrollment/confimation', [PaymentController::class, 'downloadConfirmation'])->name('enrollment.confirmation');

	// Rotas do administrador com layout de administrador

});
//corrigir as rotas apara acessoa apenas do admin
Route::middleware(['auth',])->group(function () {
	Route::get('/admin', Dashboard::class)->name('admin.dashboard');
	Route::get('/gestao', Gestao::class)->name('admin.gestao');	
	Route::get('/schools', Schools::class)->name('schools.index');
	Route::get('/salas', Rooms::class)->name('class-models');
	Route::get('/monitores', Monitors::class)->name('monitors.index');
	Route::get('/disciplines', Disciplines::class)->name('disciplines.index');
	Route::get('/jury-distributions', JuryDistribution::class)->name('jury.distributions');
});

Route::get('/candidates/data', function () {
	return datatables()->of(Candidate::query()->with('course:id,name')) // Carregar o curso relacionado
		->addColumn('course_name', function ($candidate) {
			return $candidate->course->name ?? 'Sem Curso';
		})
		->addColumn('actions', function ($candidate) {
			return '<button class="bg-yellow-500 text-white px-2 py-1 rounded">Editar</button>
                    <button class="bg-red-500 text-white px-2 py-1 rounded" onclick="Livewire.emit(\'deleteCandidate\', ' . $candidate->id . ')">Eliminar</button>';
		})
		->rawColumns(['actions'])
		->make(true);
})->name('candidates.data');

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
	->middleware('auth')
	->name('logout');

require __DIR__ . '/auth.php';
